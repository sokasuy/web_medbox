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
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <select class="form-control select2bs4placeholder" id="cbo_pilihanperiode"
                                    style="width: 100%;">
                                    <option value="sudah_expired">Sudah Expired</option>
                                    <option value="30_hari_before_expired">30 Hari Sebelum Expired</option>
                                    <option value="15_hari_before_expired">15 Hari Sebelum Expired</option>
                                    <option value="7_hari_before_expired">7 Hari Sebelum Expired</option>
                                    <option value="berdasarkan_tanggal_expired">Berdasarkan Tanggal Expired</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_pilihperiode">Submit</button>
                        </div>
                    </div>
                    <table id="tbl_expirydate" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ENTITI</th>
                                <th>KODE OBAT</th>
                                <th>NAMA OBAT</th>
                                <th>STOK TERKECIL</th>
                                <th>SAT</th>
                                <th>GOLONGAN</th>
                                <th>KATEGORI</th>
                                <th>NO BATCH</th>
                                <th>TANGGAL EXPIRED</th>
                                <th>HARI SAMPAI EXPIRED</th>
                                <th>PABRIK</th>
                                <th>JENIS</th>
                                <th>DISCONTINUE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->entiti }}</td>
                                    <td>{{ $d->sku }}</td>
                                    <td>{{ $d->namabarang }}</td>
                                    <td>{{ $d->jumlah }}</td>
                                    <td>{{ $d->satk }}</td>
                                    <td>{{ $d->golongan }}</td>
                                    <td>{{ $d->kategori }}</td>
                                    <td>{{ $d->nobatch }}</td>
                                    <td>{{ $d->ed }}</td>
                                    <td>{{ $d->harimenujuexpired }}</td>
                                    <td>{{ $d->pabrik }}</td>
                                    <td>{{ $d->jenis }}</td>
                                    <td>{{ $d->discontinue }}</td>
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
            $("#tbl_expirydate").DataTable({
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                columnDefs: [{
                    targets: [3],
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, {
                    targets: [8],
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
                    let grandTotalJumlah = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    let subTotalJumlah = api
                        .column(3, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer with a subtotal
                    let numFormat = $.fn.dataTable.render.number(',', '.', 0, '').display;
                    $(api.column(3).footer()).html(numFormat(subTotalJumlah) + '(' + numFormat(
                        grandTotalJumlah) + ')');
                },
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_expirydate_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
