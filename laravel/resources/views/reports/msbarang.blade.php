@extends('layouts.reports')
{{-- @section('cssatas')
<!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection --}}
@section('title')
    <title>APOTEK MEDBOX | Data Master Barang</title>
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


{{-- @section('jsbawah')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
@endsection --}}
