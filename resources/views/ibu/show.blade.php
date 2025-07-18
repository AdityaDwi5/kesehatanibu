<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Calon Penerima Bantuan</title>
    @include('AdminLTE.head')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div id="wrapper">
        @include('AdminLTE.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-success">Data Ibu Hamil</h4>
                    </div>
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                </nav>

                <div class="container">
                    <h1>Detail Data Ibu</h1>

                    <table class="table table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $ibu->nama }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>{{ \Carbon\Carbon::parse($ibu->tanggal_lahir)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $ibu->alamat }}</td>
                        </tr>
                    </table>

                    <h3>Data Pemeriksaan</h3>
                     @if(auth()->user()->level == 'admin')
                    <a href="{{ route('pemeriksaan.create', ['ibu_id' => $ibu->id]) }}" class="btn btn-primary mb-3">Tambah Pemeriksaan</a>
                     @elseif(auth()->user()->level == 'kabid')
                    <a href="{{ route('pemeriksaan.exportPdf', $ibu->id) }}" class="btn btn-danger mb-3">Export PDF</a>
                    @endif
                    <table class="table table-bordered">
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
                            @foreach ($ibu->pemeriksaans as $pemeriksaan)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->format('d-m-Y') }}</td>
                                <td>{{ $pemeriksaan->usia_kehamilan }} minggu</td>
                                <td>{{ $pemeriksaan->tekanan_darah }} mmHg</td>
                                <td>
    @foreach(explode(',', $pemeriksaan->riwayat_penyakit) as $penyakit)
        <span class="badge badge-info">{{ ucfirst($penyakit) }}</span>
    @endforeach
</td>

                                <td>{{ $pemeriksaan->klasifikasi->hasil ?? 'Belum diklasifikasi' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h3>Grafik Klasifikasi Per Bulan</h3>
                    <canvas id="klasifikasiIbuChart" height="100"></canvas>




                    <a href="{{ route('ibu.index') }}" class="btn btn-success">Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);
        const labels = chartData.map(item => item.bulan);
        const rendah = chartData.map(item => item.rendah);
        const sedang = chartData.map(item => item.sedang);
        const tinggi = chartData.map(item => item.tinggi);

        const ctx = document.getElementById('klasifikasiIbuChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Rendah',
                        data: rendah,
                        backgroundColor: 'green'
                    },
                    {
                        label: 'Sedang',
                        data: sedang,
                        backgroundColor: 'orange'
                    },
                    {
                        label: 'Tinggi',
                        data: tinggi,
                        backgroundColor: 'red'
                    },
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Riwayat Klasifikasi Risiko Kesehatan'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>