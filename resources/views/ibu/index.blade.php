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
                    <h1>Data Ibu Hamil & Nifas</h1>
                     @if(auth()->user()->level == 'admin')
                    <a href="{{ route('ibu.create') }}" class="btn btn-success mb-3">Tambah Data Ibu</a>
                    @endif
                    <table class="table table-bordered">
                        <thead>
    <tr>
        <th>Nama</th>
        <th>Tanggal Lahir</th>
        <th>Alamat</th>
        <th>Hasil Klasifikasi Terakhir</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    @foreach($ibus as $ibu)
    <tr>
        <td>{{ $ibu->nama }}</td>
        <td>{{ \Carbon\Carbon::parse($ibu->tanggal_lahir)->format('d-m-Y') }}</td>
        <td>{{ $ibu->alamat }}</td>
        <td>

           <span class="badge 
        @if($ibu->hasil_klasifikasi == 'Rendah') badge-success 
        @elseif($ibu->hasil_klasifikasi == 'Sedang') badge-warning 
        @elseif($ibu->hasil_klasifikasi == 'Tinggi') badge-danger 
        @else badge-secondary @endif">
        {{ $ibu->hasil_klasifikasi }}
    </span>
        </td>
        <td>
            @if(auth()->user()->level == 'admin')
                <a href="{{ route('ibu.show', $ibu->id) }}" class="btn btn-info btn-sm">Detail</a>
                <a href="{{ route('ibu.edit', $ibu->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('ibu.destroy', $ibu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Hapus</button>
                </form>
            @elseif(auth()->user()->level == 'kabid')
                <a href="{{ route('ibu.show', $ibu->id) }}" class="btn btn-info btn-sm">Detail</a>
            @endif
        </td>
    </tr>
    @endforeach
</tbody>

                    </table>
                
                </div>
            </div>
        </div>
    </div>