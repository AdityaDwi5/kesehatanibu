<?php

namespace App\Http\Controllers;

use App\Models\Ibu;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PemeriksaanController extends Controller
{
    public function index(Ibu $ibu)
    {
        $pemeriksaans = $ibu->pemeriksaans()->with('klasifikasi')->get();
        return view('pemeriksaan.index', compact('ibu', 'pemeriksaans'));
    }

    public function create(Request $request)
    {
        $ibu = Ibu::findOrFail($request->get('ibu_id'));
        return view('pemeriksaan.create', compact('ibu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ibu_id' => 'required|exists:ibus,id',
            'tanggal_pemeriksaan' => 'required|date',
            'usia_kehamilan' => 'required|numeric',
            'tekanan_darah' => 'required|numeric',
            'riwayat_penyakit' => 'nullable|array',
            'riwayat_penyakit.*' => 'string',
        ]);

        $riwayatPenyakit = $request->riwayat_penyakit
            ? implode(',', $request->riwayat_penyakit)
            : 'tidak_ada';

        $pemeriksaan = Pemeriksaan::create([
            'ibu_id' => $validated['ibu_id'],
            'tanggal_pemeriksaan' => $validated['tanggal_pemeriksaan'],
            'usia_kehamilan' => $validated['usia_kehamilan'],
            'tekanan_darah' => $validated['tekanan_darah'],
            'riwayat_penyakit' => $riwayatPenyakit,
        ]);

        $hasil = $this->naiveBayesKlasifikasi($pemeriksaan);
        $pemeriksaan->klasifikasi()->create(['hasil' => $hasil]);

        return redirect()->route('ibu.index')->with('success', 'Data berhasil ditambah dan diklasifikasi.');
    }

  private function naiveBayesKlasifikasi(Pemeriksaan $p)
{
    $trainingData = Pemeriksaan::with('klasifikasi')->has('klasifikasi')->get();
    $labels = ['Rendah', 'Sedang', 'Tinggi'];

    if ($trainingData->isEmpty()) {
        return 'Rendah';
    }

    $labelCounts = $trainingData->groupBy(fn($d) => optional($d->klasifikasi)->hasil)->map->count();
    $totalData = $trainingData->count();

    $fitur = [
        'usia_kehamilan' => [],
        'tekanan_darah' => [],
        'penyakit' => [],
    ];

    foreach ($labels as $label) {
        $dataLabel = $trainingData->filter(fn($d) => optional($d->klasifikasi)->hasil === $label);

        $fitur['usia_kehamilan'][$label] = [
            'mean' => $dataLabel->avg('usia_kehamilan'),
            'std' => $this->stdDev($dataLabel->pluck('usia_kehamilan')->toArray()),
        ];
        $fitur['tekanan_darah'][$label] = [
            'mean' => $dataLabel->avg('tekanan_darah'),
            'std' => $this->stdDev($dataLabel->pluck('tekanan_darah')->toArray()),
        ];
        $jumlahP = $dataLabel->map(fn($d) => $d->riwayat_penyakit ? count(explode(',', $d->riwayat_penyakit)) : 0);
        $fitur['penyakit'][$label] = [
            'mean' => $jumlahP->avg(),
            'std' => $this->stdDev($jumlahP->toArray()),
        ];
    }

    // ✳️ Override logika medis ekstrem
    $penyakit = $p->riwayat_penyakit ? count(explode(',', $p->riwayat_penyakit)) : 0;
    if ($p->tekanan_darah >= 160 && $penyakit >= 3) {
        return 'Tinggi';
    }

    $scores = [];
    foreach ($labels as $label) {
        $logProb = log(($labelCounts[$label] ?? 1) / ($totalData + count($labels)));

        $logProb += log($this->gaussian($p->usia_kehamilan, $fitur['usia_kehamilan'][$label]['mean'], $fitur['usia_kehamilan'][$label]['std']) + 1e-9);
        $logProb += log($this->gaussian($p->tekanan_darah, $fitur['tekanan_darah'][$label]['mean'], $fitur['tekanan_darah'][$label]['std']) + 1e-9);
        $logProb += log($this->gaussian($penyakit, $fitur['penyakit'][$label]['mean'], $fitur['penyakit'][$label]['std']) + 1e-9);

        $scores[$label] = $logProb;
    }

    arsort($scores);
    return array_key_first($scores);
}



    private function stdDev($data)
{
    if (empty($data)) return 0;
    $mean = array_sum($data) / count($data);
    $variance = array_sum(array_map(fn($val) => pow($val - $mean, 2), $data)) / count($data);
    return sqrt($variance);
}



    private function gaussian($x, $mean, $std)
    {
        if ($std == 0) return 1;
        $exponent = exp(- (pow($x - $mean, 2)) / (2 * pow($std, 2)));
        return (1 / (sqrt(2 * pi()) * $std)) * $exponent;
    }

    public function exportPdf($id)
    {
        $ibu = Ibu::with('pemeriksaans.klasifikasi')->findOrFail($id);
        $pdf = Pdf::loadView('pemeriksaan.export-pdf', compact('ibu'));
        return $pdf->download('laporan_pemeriksaan_' . $ibu->nama . '.pdf');
    }
}
