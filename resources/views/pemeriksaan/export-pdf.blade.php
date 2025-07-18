<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemeriksaan Ibu</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, h4 { margin: 0; }
    </style>
</head>
<body>
    <h2>Laporan Pemeriksaan</h2>
    <h4>Nama Ibu: {{ $ibu->nama }}</h4>
    <h4>Alamat: {{ $ibu->alamat }}</h4>
    <h4>Tanggal Lahir: {{ \Carbon\Carbon::parse($ibu->tanggal_lahir)->format('d-m-Y') }}</h4>

    <table>
        <thead>
            <tr>
                <th>Tanggal Pemeriksaan</th>
                <th>Usia Kehamilan</th>
                <th>Tekanan Darah</th>
                <th>Riwayat Penyakit</th>
                <th>Hasil Klasifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ibu->pemeriksaans as $p)
            <tr>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_pemeriksaan)->format('d-m-Y') }}</td>
                <td>{{ $p->usia_kehamilan }} minggu</td>
                <td>{{ $p->tekanan_darah }} mmHg</td>
                <td>{{ $p->riwayat_penyakit }}</td>
                <td>{{ $p->klasifikasi->hasil ?? 'Belum diklasifikasi' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
