<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analisis Risiko Kesehatan Ibu Hamil & Nifas</title>
    @include('AdminLTE.head')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div id="wrapper">
    @include('AdminLTE.sidebar')

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-success">Pemantauan Kesehatan Ibu</h4>
                </div>
            </nav>

            <div class="container">
                <h2>Analisis Risiko Kesehatan (Naïve Bayes)</h2>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Usia</th>
                            <th>Kehamilan Berisiko?</th>
                            <th>Tekanan Darah</th>
                            <th>Gula Darah</th>
                            <th>Hasil Klasifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataIbu as $ibu)
                        <tr>
                            <td>{{ $ibu->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($ibu->tanggal_lahir)->age }} tahun</td>
                            <td>{{ $ibu->hamil_berisiko ? 'Ya' : 'Tidak' }}</td>
                            <td>{{ $ibu->tekanan_darah ?? '-' }}</td>
                            <td>{{ $ibu->gula_darah ?? '-' }}</td>
                            <td>
                                {{-- Contoh hasil naive bayes --}}
                                @php
                                    $hasil = \App\Services\NaiveBayes::klasifikasi($ibu);
                                @endphp
                                <span class="badge badge-{{ $hasil['warna'] }}">
                                    {{ $hasil['label'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <a href="{{ route('ibu.index') }}" class="btn btn-secondary mt-3">Kembali ke Data Ibu</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
