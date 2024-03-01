@extends('layouts.auth')
@section('title')
    <title>APOTEK MEDBOX | Customers</title>
@endsection

@section('headertitle')
    <h1>CUSTOMERS</h1>
@endsection

@section('navlist')
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('auth.users') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Users</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Roles</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Permission</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('auth.customers') }}" class="nav-link active">
                <i class="far fa-circle nav-icon"></i>
                <p>Customers</p>
            </a>
        </li>
    </ul>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">Home</a></li>
    <li class="breadcrumb-item">Authentication</li>
    <li class="breadcrumb-item active">Customers</li>
@endsection

{{-- @section('cssatas')
    <style>
        img .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }
    </style>
@endsection --}}

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customers</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="showinfo"></div>
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <div class="row mb-0">
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control select2bs4" id="cbo_periodepelangganterdaftar"
                                    style="width: 100%;">
                                    <option value="hari_ini">Hari Ini</option>
                                    <option value="3_hari">3 Hari - Hari ini</option>
                                    <option value="7_hari">7 Hari - Hari ini</option>
                                    <option value="14_hari">14 Hari - Hari ini</option>
                                    <option value="bulan_berjalan">Bulan ini</option>
                                    <option value="semua">Semua</option>
                                    <option value="berdasarkan_tanggal_pelanggan_terdaftar">Berdasarkan Tanggal Pelanggan
                                        Terdaftar
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group cbo-filter-periode-pelanggan-terdaftar"
                                id="cbo_berdasarkan_tanggal_pelanggan_terdaftar">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="dtp_pelangganterdaftar">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_daftarkanpelanggan">Submit</button>
                        </div>
                    </div>
                    <table id="tbl_customers" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ENTITY</th>
                                <th>KODE KONTAK</th>
                                <th>NAMA</th>
                                <th>TELEPON</th>
                                <th>CREATED AT</th>
                                <th>UPDATED AT</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        {{-- <div class="modal container fade" id="containermodal" tabindex="-1" role="basic"
                            aria-hidden="true">
                            <div class="modal-content" id="contentmodal">
                                <img src="{{ asset('assets/images/loading-buffering.gif') }}" height='200px' />
                            </div>
                        </div> --}}
                        <!-- Modal -->
                        <div class="modal fade" id="modal_container" tabindex="-1" role="dialog"
                            aria-labelledby="modal_containerlabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                <div class="modal-content" id="modal_content">
                                    <div class="overlay">
                                        <i class="fas fa-2x fa-sync fa-spin"></i>
                                    </div>
                                    {{-- <img src="{{ asset('assets/images/loading-buffering.gif') }}" height='150px'
                                        width='150px' class="center" /> --}}
                                </div>
                            </div>
                        </div>

                        <tfoot>
                            <tr>
                                <th>ENTITY</th>
                                <th>KODE KONTAK</th>
                                <th>NAMA</th>
                                <th>TELEPON</th>
                                <th>CREATED AT</th>
                                <th>UPDATED AT</th>
                                <th>ACTION</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('jsbawah')
    <script defer>
        document.addEventListener('DOMContentLoaded', (event) => {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            //Date range picker
            $('#dtp_pelangganterdaftar').daterangepicker();

            //Buat hidden date picker kalau bukan berdasarkan_tanggal_expired
            $("div#cbo_berdasarkan_tanggal_pelanggan_terdaftar").hide();

            var tblCustomers = $("#tbl_customers").DataTable({
                "dom": 'Bfrtip',
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                "processing": true,
                "ajax": {
                    "url": '{{ route('auth.getcustomerslist') }}',
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    },
                    "xhrFields": {
                        withCredentials: true
                    }
                },
                "columns": [{
                    "data": "entiti"
                }, {
                    "data": "kodekontak"
                }, {
                    "data": "kontak"
                }, {
                    "data": "hp"
                }, {
                    "data": "created_at",
                    render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss',
                        'D MMM YYYY HH:mm:ss')
                }, {
                    "data": "updated_at",
                    // render: $.fn.dataTable.render.moment('YYYY-MM-DDTHH:mm:ss',
                    //     'D MMM YYYY HH:mm:ss')
                }, {
                    "data": null,
                    // render: function(data, type, row) {
                    //     return '<a class="btn_changepassword btn btn-primary btn-sm" data-toggle="modal" href="#editusermodal"><i class="fas fa-user-edit">&nbsp Edit</i></a> <a class="btn btn-danger btn-sm" data-toggle="modal" href="#deleteusermodal"><i class="fas fa-trash-alt">&nbsp Delete</i></a>';
                    // }
                    "defaultContent": '<a class="btn_insertcustomer btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_container"><i class="fas fa-plus">&nbsp Insert Customer</i></a>'
                    // "defaultContent": '<input type="button" class="btn_changepassword" value="Ganti Password"/><input type="button" class="btn_delete" value="Delete"/>'
                }],
                select: true,
                // fnInitComplete: function(oSettings, json) {
                //     //CHANGE PASSWORD BUTTON
                //     const btnChangePassword = document.querySelector('.btn_changepassword');
                //     btnChangePassword.addEventListener('click', changePassword);
                // },
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_user_wrapper .col-md-6:eq(0)');
        });

        //FILTER
        const cboPeriodePelangganTerdaftar = document.querySelector('#cbo_periodepelangganterdaftar');
        cboPeriodePelangganTerdaftar.onchange = function() {
            let periodePelangganTerdaftar = cboPeriodePelangganTerdaftar.value;
            // alert(periodePelangganTerdaftar);
            $("div.cbo-filter-periode-pelanggan-terdaftar").hide();
            $("#cbo_" + periodePelangganTerdaftar).show();
        };

        const btnDaftarkanPelanggan = document.querySelector('#btn_daftarkanpelanggan');
        // btnDaftarkanPelanggan.addEventListener('click', refreshPenjualan);
    </script>
@endsection
