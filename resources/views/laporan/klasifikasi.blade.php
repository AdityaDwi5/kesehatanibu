<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Klasifikasi Risiko</title>
    @include('AdminLTE.head')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .stat-box {
            border-radius: 10px;
            padding: 20px;
            color: white;
            text-align: center;
        }

        .bg-rendah {
            background-color: green;
        }

        .bg-sedang {
            background-color: orange;
        }

        .bg-tinggi {
            background-color: red;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        @include('AdminLTE.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-success">Laporan Klasifikasi Risiko Kesehatan Ibu</h4>
                    </div>
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                </nav>

                <div class="container mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-box bg-rendah">
                                <h5>Total Rendah</h5>
                                <h3>{{ $data->sum('rendah') }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box bg-sedang">
                                <h5>Total Sedang</h5>
                                <h3>{{ $data->sum('sedang') }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box bg-tinggi">
                                <h5>Total Tinggi</h5>
                                <h3>{{ $data->sum('tinggi') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container mb-5">
                    <h5 class="mb-3">Visualisasi Grafik Klasifikasi Risiko (Bulanan)</h5>
                    <canvas id="klasifikasiChart" height="100"></canvas>
                </div>

                <div class="container mb-5">
                    <h5 class="mb-3">Detail Data Klasifikasi Per Bulan</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Bulan</th>
                                <th>Jumlah Rendah</th>
                                <th>Jumlah Sedang</th>
                                <th>Jumlah Tinggi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                                <tr>
                                    <td>{{ $d->bulan }}</td>
                                    <td>{{ $d->rendah }}</td>
                                    <td>{{ $d->sedang }}</td>
                                    <td>{{ $d->tinggi }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const data = @json($data);
                    const labels = data.map(d => d.bulan);
                    const rendah = data.map(d => d.rendah);
                    const sedang = data.map(d => d.sedang);
                    const tinggi = data.map(d => d.tinggi);

                    const ctx = document.getElementById('klasifikasiChart').getContext('2d');
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
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Grafik Klasifikasi Risiko Ibu Hamil per Bulan'
                                },
                                legend: {
                                    position: 'top'
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
            </div>
        </div>
    </div>
</body>

</html>
