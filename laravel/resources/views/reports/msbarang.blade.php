@extends('layouts.reports')
@section('title')
    <title>APOTEK MEDBOX | Data Master Barang</title>
@endsection

@section('navlist')
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('msbarang.index') }}" class="nav-link active">
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
            <a href="{{ route('reports.expirydate') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Expiry Date</p>
            </a>
        </li>
    </ul>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">Home</a></li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">Data Master Barang</li>
@endsection

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Master Barang</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="tbl_msbarang" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ENTITI</th>
                                <th>SKU</th>
                                <th>BARCODE</th>
                                <th>NAMA BARANG</th>
                                <th>GOLONGAN</th>
                                <th>JENIS</th>
                                <th>SATK</th>
                                <th>KONV1</th>
                                <th>SATT</th>
                                <th>KONV2</th>
                                <th>SATB</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->entiti }}</td>
                                    <td>{{ $d->sku }}</td>
                                    <td>{{ $d->barcode }}</td>
                                    <td>{{ $d->namabarang }}</td>
                                    <td>{{ $d->golongan }}</td>
                                    <td>{{ $d->jenis }}</td>
                                    <td>{{ $d->satk }}</td>
                                    <td>{{ $d->konv1 }}</td>
                                    <td>{{ $d->satt }}</td>
                                    <td>{{ $d->konv2 }}</td>
                                    <td>{{ $d->satb }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ENTITI</th>
                                <th>SKU</th>
                                <th>BARCODE</th>
                                <th>NAMA BARANG</th>
                                <th>GOLONGAN</th>
                                <th>JENIS</th>
                                <th>SATK</th>
                                <th>KONV1</th>
                                <th>SATT</th>
                                <th>KONV2</th>
                                <th>SATB</th>
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
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            $("#tbl_msbarang").DataTable({
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_msbarang_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
