@extends('layouts.auth')
@section('title')
    <title>APOTEK MEDBOX | Data Users</title>
@endsection

@section('headertitle')
    <h1>DATA USER</h1>
@endsection

@section('navlist')
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('auth.users') }}" class="nav-link active">
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
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">Home</a></li>
    <li class="breadcrumb-item">Authentication</li>
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row mb-0">
                        <div class="col-md-3" style="margin-bottom: 23px;">
                            {{-- <button type="submit" class="btn btn-primary" id="btn_adduser">Add User</button> --}}
                            <a class="btn btn-primary btn-sm" href="{{ route('auth.adduser') }}" id="btn_adduser">
                                <i class="fas fa-plus">&nbsp Add User</i></a>
                        </div>
                    </div>
                    <table id="tbl_user" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>NAMA</th>
                                <th>EMAIL</th>
                                <th>CREATED AT</th>
                                <th>UPDATED AT</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>NAMA</th>
                                <th>EMAIL</th>
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
            var tblUser = $("#tbl_user").DataTable({
                "dom": 'Bfrtip',
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                "processing": true,
                "ajax": {
                    "url": '{{ route('auth.getuserslist') }}',
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    },
                    "xhrFields": {
                        withCredentials: true
                    }
                },
                "columns": [{
                    "data": "name"
                }, {
                    "data": "email"
                }, {
                    "data": "created_at",
                    render: $.fn.dataTable.render.moment('YYYY-MM-DDTHH:mm:ss.SSSSZ',
                        'D MMM YYYY HH:mm:ss')
                }, {
                    "data": "updated_at",
                    render: $.fn.dataTable.render.moment('YYYY-MM-DDTHH:mm:ss.SSSSZ',
                        'D MMM YYYY HH:mm:ss')
                }, {
                    "data": null,
                    // render: function(data, type, row) {
                    //     return '<a class="btn_changepassword btn btn-primary btn-sm" data-toggle="modal" href="#editusermodal"><i class="fas fa-user-edit">&nbsp Edit</i></a> <a class="btn btn-danger btn-sm" data-toggle="modal" href="#deleteusermodal"><i class="fas fa-trash-alt">&nbsp Delete</i></a>';
                    // }
                    "defaultContent": '<a class="btn_changepassword btn btn-primary btn-sm" data-toggle="modal" href="#editusermodal"><i class="fas fa-user-edit">&nbsp Change Password</i></a> <a class="btn_delete btn btn-danger btn-sm" data-toggle="modal" href="#deleteusermodal"><i class="fas fa-trash-alt">&nbsp Delete</i></a>'
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

        $('#tbl_user').on('click', '.btn_changepassword', function() {
            var row = $(this).closest('tr');

            var data = $("#tbl_user").DataTable().row(row).data().email;
            alert(data);
        });
        $('#tbl_user').on('click', '.btn_delete', function() {
            var row = $(this).closest('tr');

            var data = $("#tbl_user").DataTable().row(row).data().email;
            alert(data);
        });

        /* const btnAddUser = document.querySelector('#btn_adduser');
        btnAddUser.addEventListener('click', addUser);

        function addUser() {
            alert("tambahkan user");
        } */
    </script>
@endsection
