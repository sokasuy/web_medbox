@extends('layouts.master')
@section('title')
    <title>APOTEK MEDBOX | Data Master Barang</title>
@endsection

@section('headertitle')
    <h1>DATA MASTER</h1>
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
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">Home</a></li>
    <li class="breadcrumb-item">Master</li>
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
                                <th>HARGA</th>
                                <th>GOLONGAN</th>
                                <th>JENIS</th>
                                <th>SATK</th>
                                <th>KONV1</th>
                                <th>SATT</th>
                                <th>KONV2</th>
                                <th>SATB</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ENTITI</th>
                                <th>SKU</th>
                                <th>BARCODE</th>
                                <th>NAMA BARANG</th>
                                <th>HARGA</th>
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
                "dom": 'Bfrtip',
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                "processing": true,
                "ajax": {
                    "url": '{{ route('msbarang.getdata') }}',
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
                    "data": "sku"
                }, {
                    "data": "barcode"
                }, {
                    "data": "namabarang"
                }, {
                    "data": "hargak",
                    render: $.fn.DataTable.render.number(',', '.', 0, ''),
                    className: 'dt-body-right'
                }, {
                    "data": "golongan"
                }, {
                    "data": "jenis"
                }, {
                    "data": "satk"
                }, {
                    "data": "konv1"
                }, {
                    "data": "satt"
                }, {
                    "data": "konv2"
                }, {
                    "data": "satb"
                }],
                select: true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_msbarang_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
