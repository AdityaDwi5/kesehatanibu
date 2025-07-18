<?php

namespace App\Http\Controllers;

use App\Models\Ibu;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IbuController extends Controller
{
    public function index()
    {
        $ibus = Ibu::with('pemeriksaans.klasifikasi')->get();

        foreach ($ibus as $ibu) {
            $ibu->hasil_klasifikasi = $this->klasifikasiNaiveBayes($ibu->pemeriksaans);
        }

        return view('ibu.index', compact('ibus'));
    }

    // Tampilkan form create
public function create()
{
    return view('ibu.create');
}

// Simpan data baru
public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        // tambahkan validasi field lain sesuai kebutuhan
    ]);

    Ibu::create($request->all());

    return redirect()->route('ibu.index')->with('success', 'Data ibu berhasil ditambahkan.');
}


    public function show($id)
    {
        $ibu = Ibu::with('pemeriksaans.klasifikasi')->findOrFail($id);

        $klasifikasiPerBulan = DB::table('pemeriksaans')
            ->join('klasifikasis', 'pemeriksaans.id', '=', 'klasifikasis.pemeriksaan_id')
            ->select(
                DB::raw("DATE_FORMAT(pemeriksaans.tanggal_pemeriksaan, '%Y-%m') as bulan"),
                'klasifikasis.hasil',
                DB::raw('count(*) as jumlah')
            )
            ->where('pemeriksaans.ibu_id', $id)
            ->groupBy('bulan', 'klasifikasis.hasil')
            ->orderBy('bulan')
            ->get()
            ->groupBy('bulan')
            ->map(function ($group) {
                return [
                    'rendah' => $group->where('hasil', 'Rendah')->sum('jumlah'),
                    'sedang' => $group->where('hasil', 'Sedang')->sum('jumlah'),
                    'tinggi' => $group->where('hasil', 'Tinggi')->sum('jumlah'),
                ];
            });

        $chartData = [];
        foreach ($klasifikasiPerBulan as $bulan => $data) {
            $chartData[] = [
                'bulan' => Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y'),
                'rendah' => $data['rendah'],
                'sedang' => $data['sedang'],
                'tinggi' => $data['tinggi'],
            ];
        }

        return view('ibu.show', compact('ibu', 'chartData'));
    }

    public function edit($id)
{
    $ibu = Ibu::findOrFail($id);
    return view('ibu.edit', compact('ibu'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        // tambah field lain yang sesuai
    ]);

    $ibu = Ibu::findOrFail($id);
    $ibu->update($request->all());

    return redirect()->route('ibu.index')->with('success', 'Data ibu berhasil diperbarui.');
}

public function destroy($id)
{
    $ibu = Ibu::findOrFail($id);

    // Hapus pemeriksaan dan klasifikasi terkait jika diperlukan
    foreach ($ibu->pemeriksaans as $pemeriksaan) {
        $pemeriksaan->klasifikasi()->delete(); // jika relasi ada
        $pemeriksaan->delete();
    }

    $ibu->delete();

    return redirect()->route('ibu.index')->with('success', 'Data ibu berhasil dihapus.');
}

  private function klasifikasiNaiveBayes($pemeriksaans)
{
    if ($pemeriksaans->isEmpty()) return 'Belum diklasifikasi';

    $trainingData = Pemeriksaan::with('klasifikasi')->has('klasifikasi')->get();
    $labels = ['Rendah', 'Sedang', 'Tinggi'];

    $labelCounts = $trainingData->groupBy(fn($d) => optional($d->klasifikasi)->hasil)->map->count();
    $totalData = $trainingData->count();

    // Hitung statistik fitur
    $fitur = ['usia_kehamilan' => [], 'tekanan_darah' => [], 'penyakit' => []];
    foreach ($labels as $label) {
        $dataLabel = $trainingData->filter(fn($d) => optional($d->klasifikasi)->hasil === $label);

        $fitur['usia_kehamilan'][$label] = [
            'mean' => $dataLabel->avg('usia_kehamilan'),
            'std' => $this->std($dataLabel->pluck('usia_kehamilan')->toArray()),
        ];
        $fitur['tekanan_darah'][$label] = [
            'mean' => $dataLabel->avg('tekanan_darah'),
            'std' => $this->std($dataLabel->pluck('tekanan_darah')->toArray()),
        ];
        $penyakitCount = $dataLabel->map(fn($d) => $d->riwayat_penyakit ? count(explode(',', $d->riwayat_penyakit)) : 0);
        $fitur['penyakit'][$label] = [
            'mean' => $penyakitCount->avg(),
            'std' => $this->std($penyakitCount->toArray()),
        ];
    }

    // Pemeriksaan terakhir
    $last = $pemeriksaans->sortByDesc('tanggal_pemeriksaan')->first();
    $skorTerakhir = $this->hitungSkorNaiveBayes($last, $labels, $labelCounts, $totalData, $fitur);

    // Riwayat sebelumnya
    $histori = $pemeriksaans->where('id', '!=', $last->id);
    $skorHistoriGabung = ['Rendah' => 0, 'Sedang' => 0, 'Tinggi' => 0];

    if ($histori->isNotEmpty()) {
        foreach ($histori as $p) {
            $skor = $this->hitungSkorNaiveBayes($p, $labels, $labelCounts, $totalData, $fitur);
            foreach ($labels as $label) {
                $skorHistoriGabung[$label] += $skor[$label];
            }
        }
        foreach ($labels as $label) {
            $skorHistoriGabung[$label] /= $histori->count(); // rata-rata skor histori
        }
    }

    // Bobot: 90% pemeriksaan terakhir + 10% histori
    $gabungan = [];
    foreach ($labels as $label) {
        $gabungan[$label] = $skorTerakhir[$label] * 0.9 + $skorHistoriGabung[$label] * 0.1;
    }

    // Override jika semua pemeriksaan hasilnya “Rendah”
    $semuaRendah = $pemeriksaans->every(fn($p) => optional($p->klasifikasi)->hasil === 'Rendah');
    if ($semuaRendah) return 'Rendah';

    // Bisa juga tambah logika: jika tidak ada yang “Tinggi”, set ke “Sedang”
    $adaTinggi = $pemeriksaans->contains(fn($p) => optional($p->klasifikasi)->hasil === 'Tinggi');
    if (!$adaTinggi && !$semuaRendah) return 'Sedang';

    arsort($gabungan);
    return array_key_first($gabungan);
}



private function hitungSkorNaiveBayes($p, $labels, $labelCounts, $totalData, $fitur)
{
    $scores = [];

    $penyakit = $p->riwayat_penyakit ? count(explode(',', $p->riwayat_penyakit)) : 0;

    foreach ($labels as $label) {
        $logProb = log(($labelCounts[$label] ?? 1) / ($totalData + count($labels)));

        $logProb += log($this->gaussian($p->usia_kehamilan, $fitur['usia_kehamilan'][$label]['mean'], $fitur['usia_kehamilan'][$label]['std']) + 1e-9);
        $logProb += log($this->gaussian($p->tekanan_darah, $fitur['tekanan_darah'][$label]['mean'], $fitur['tekanan_darah'][$label]['std']) + 1e-9);
        $logProb += log($this->gaussian($penyakit, $fitur['penyakit'][$label]['mean'], $fitur['penyakit'][$label]['std']) + 1e-9);

        $scores[$label] = $logProb;
    }

    return $scores;
}

private function std($data)
{
    $mean = $this->mean($data);
    $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $data)) / (count($data) ?: 1);
    return sqrt($variance);
}

private function mean($data)
{
    return (count($data) === 0) ? 0 : array_sum($data) / count($data);
}

private function gaussian($x, $mean, $std)
{
    if ($std == 0) return 1;
    $exponent = exp(-0.5 * pow(($x - $mean) / $std, 2));
    return (1 / ($std * sqrt(2 * pi()))) * $exponent;
}



}
