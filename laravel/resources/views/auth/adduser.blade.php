@extends('layouts.auth')
@section('title')
    <title>APOTEK MEDBOX | Add User</title>
@endsection

@section('headertitle')
    <span>
        <h1>USERS
        </h1>
        Add User. <a href="{{ route('auth.users') }}"><i class="fas fa-angle-double-left"></i> Back to all
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
        <div class="col-md-6">
        </div>
    </div>
@endsection
