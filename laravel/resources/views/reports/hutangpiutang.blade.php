@extends('layouts.reports')
@section('title')
    <title>APOTEK MEDBOX | Data Hutang Piutang</title>
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
            <a href="{{ route('reports.hutangpiutang') }}" class="nav-link active">
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
    <li class="breadcrumb-item active">Data Hutang Piutang</li>
@endsection

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hutang Piutang</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="tbl_hutangpiutang" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ENTITI</th>
                                <th>KODE TRANSAKSI</th>
                                <th>TANGGAL</th>
                                <th>NO FAKTUR</th>
                                <th>TGL FAKTUR</th>
                                <th>KODE KONTAK</th>
                                <th>PERUSAHAAN</th>
                                <th>TOTAL HUTANG</th>
                                <th>SISA HUTANG</th>
                                <th>JANGKA WAKTU</th>
                                <th>TGL JATUH TEMPO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->entiti }}</td>
                                    <td>{{ $d->kodetransaksi }}</td>
                                    <td>{{ $d->tanggal }}</td>
                                    <td>{{ $d->nofaktur }}</td>
                                    <td>{{ $d->tglfaktur }}</td>
                                    <td>{{ $d->kodekontak }}</td>
                                    <td>{{ $d->perusahaan }}</td>
                                    <td>{{ $d->total }}</td>
                                    <td>{{ $d->hutang }}</td>
                                    <td>{{ $d->jangkawaktu }}</td>
                                    <td>{{ $d->tgljatuhtempo }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
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
            $("#tbl_hutangpiutang").DataTable({
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                columnDefs: [{
                    targets: [7, 8],
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, {
                    targets: [2, 4, 10],
                    render: $.fn.dataTable.render.moment('D MMM YYYY')
                }],
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();

                    // Remove the formatting to get integer data for summation
                    let intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };

                    // Total over all pages
                    let grandTotalHutang = api
                        .column(7)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    let subTotalHutang = api
                        .column(7, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over all pages
                    let grandTotalSisaHutang = api
                        .column(8)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    let subTotalSisaHutang = api
                        .column(8, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer with a subtotal
                    let numFormat = $.fn.dataTable.render.number(',', '.', 2, '').display;
                    $(api.column(7).footer()).html(numFormat(subTotalHutang) + '(' + numFormat(
                        grandTotalHutang) + ')');
                    $(api.column(8).footer()).html(numFormat(subTotalSisaHutang) + '(' + numFormat(
                        grandTotalSisaHutang) + ')');
                },
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_hutangpiutang_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
