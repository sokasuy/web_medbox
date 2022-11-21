@extends('layouts.auth')
@section('title')
    <title>APOTEK MEDBOX | Add User</title>
@endsection

@section('headertitle')
    <span>
        <h1>USERS
        </h1>
        Add User. <a href="{{ route('auth.users') }}"><i class="fas fa-angle-double-left"></i>&nbsp;Back to all
            <span>Users</span></a>
    </span>
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
    <li class="breadcrumb-item"><a href="{{ route('auth.users') }}">Users</a></li>
    <li class="breadcrumb-item active">Add User</li>
@endsection

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-7">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Register New User</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('auth.actionregister') }}">
                    @csrf
                    <div class="card-body">
                        {{-- NAMA --}}
                        <div class="form-group">
                            <label for="name">Full name</label>
                            <div class="input-group mb-3">
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" placeholder="Full name" required autocomplete="name"
                                    autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- EMAIL --}}
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <div class="input-group mb-3">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" placeholder="Email" required autocomplete="email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- ROLE --}}
                        <div class="form-group">
                            <label for="role">Role</label>
                            <div class="input-group mb-3">
                                <select class="form-control select2bs4placeholderrole" id="role" name="role"
                                    style="width: 100%;">
                                    <option></option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- PASSWORD --}}
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group mb-3">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="Password" required autocomplete="new-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- RETYPE PASSWORD --}}
                        <div class="form-group">
                            <label for="password-confirm"> Password Confirmation </label>
                            <div class="input-group mb-3">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" placeholder="Retype password" required
                                    autocomplete="new-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        {{-- <div class="input-group-prepend"> --}}
                        <div class="btn-group" role="group">
                            <button type="submit" class="btn btn-primary" data-value="save_and_back"><i
                                    class="fas fa-save"></i>&nbsp;Save and
                                back</button>
                            <button type="submit" class="btn btn-primary dropdown-toggle"
                                data-toggle="dropdown"></button>
                            <ul class="dropdown-menu">
                                <li class="dropdown-item" data-value="save_and_edit"><a href="#">Save and edit this
                                        item</a></li>
                                <li class="dropdown-item" data-value="save_and_new"><a href="#">Save and new
                                        item</a></li>
                            </ul>
                        </div>
                        {{-- </div> --}}
                        <button type="submit" class="btn btn-default float-right"><i
                                class="fas fa-ban"></i>&nbsp;Cancel</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@section('jsbawah')
    <script defer>
        document.addEventListener('DOMContentLoaded', (event) => {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4placeholderrole').select2({
                theme: 'bootstrap4',
                placeholder: "User Role",
                allowClear: true
            });
        });
    </script>
@endsection
