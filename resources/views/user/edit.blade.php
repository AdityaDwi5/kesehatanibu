<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aset-Add</title>
    @include('AdminLTE.head')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div id="wrapper">
    @include('AdminLTE.sidebar')    
        <div class="container-fluid">
            <div class="card-body">

                <div class="text-center">

                    <p style="margin-left:0px; font-size: 24px;">Form Input Data Barang</p>
                </div>

                <form role="form" action="{{ route('user-update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ $user->name }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                        <label for="level">Level</label>
                            <select name="level" id="level" class="form-control">

                                <option value="">--Pilih Level--</option>
                                <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="kabid" {{ $user->level == 'kabid' ? 'selected' : '' }}>Kabid</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">Password <span class="text-info"> <small> * Isi jika ingin mengubah password </small> </span> </label>
                            <input type="password" class="form-control" style="border: 1;" id="password" name="password" placeholder="Ubah Password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" >Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="{{asset('AdminLTE/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('AdminLTE/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('AdminLTE/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('AdminLTE/js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
    <script src="{{asset('AdminLTE/vendor/chart.js/Chart.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{asset('AdminLTE/js/demo/chart-area-demo.js')}}"></script>
    <script src="{{asset('AdminLTE/js/demo/chart-pie-demo.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#lainnya').hide();
            $('#type_barang_id').change(function(e) {
                e.preventDefault();

                let data = $('#type_barang_id').find(':selected').text()

                if (data == 'Lainnya') {
                    $('#lainnya').show();
                } else {
                    $('#lainnya').hide();
                }
            });
        })
    </script>
</body>

</html>