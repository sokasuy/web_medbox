@extends('layouts.reports')
@section('title')
    <title>APOTEK MEDBOX | Data Expiry Date</title>
@endsection

@section('navlist')
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('msbarang.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Master Barang</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('reports.hutangpiutang') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Hutang Piutang</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('reports.expirydate') }}" class="nav-link active">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Expiry Date</p>
            </a>
        </li>
    </ul>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">Home</a></li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">Data Expiry Date</li>
@endsection
