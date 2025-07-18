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
                        <h4 class="m-0 font-weight-bold text-primary">Data Ibu Hamil</h4>
                    </div>
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                </nav>

                <div class="container">
                    <h1>Tambah Pemeriksaan untuk: {{ $ibu->nama }}</h1>

                   <form action="{{ route('pemeriksaan.store', $ibu->id) }}" method="POST">
                        @csrf
                        <input type="" name="ibu_id" value="{{ $ibu->id }}">
                        <div class="mb-3">
                            <label>Tanggal Pemeriksaan</label>
                            <input type="date" name="tanggal_pemeriksaan" class="form-control" required value="{{ old('tanggal_pemeriksaan') }}">
                        </div>
                        <div class="mb-3">
                            <label>Usia Kehamilan (minggu)</label>
                            <input type="number" name="usia_kehamilan" class="form-control" required min="0" value="{{ old('usia_kehamilan') }}">
                        </div>
                        <div class="mb-3">
                            <label>Tekanan Darah</label>
                            <input type="number" name="tekanan_darah" class="form-control" required min="0" value="{{ old('tekanan_darah') }}">
                        </div>
                        <div class="mb-3">
                           <label>Riwayat Penyakit:</label><br>
@php
    $penyakitList = ['hipertensi', 'diabetes', 'jantung', 'asma', 'tidak_ada'];
@endphp
@foreach($penyakitList as $penyakit)
    <label>
        <input type="checkbox" name="riwayat_penyakit[]" value="{{ $penyakit }}">
        {{ ucfirst($penyakit) }}
    </label><br>
@endforeach
                        </div>
                        <button class="btn btn-primary" type="submit">Simpan</button>

                    </form>
                </div>
            </div>
        </div>
    </div>