@extends('layouts.reports')
@section('title')
    <title>APOTEK MEDBOX | Data Expiry Date</title>
@endsection

@section('headertitle')
    <h1>REPORTS EXPIRY DATE</h1>
@endsection

@section('navlist')
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('reports.hutang') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Hutang</p>
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
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('reports.penjualan') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Penjualan</p>
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
                    <h3 class="card-title">Expiry Date</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control select2bs4" id="cbo_periodeexpired" style="width: 100%;">
                                    <option value="semua">Semua</option>
                                    <option value="sudah_expired">Sudah Expired</option>
                                    <option value="30_hari_sebelum_expired">30 Hari Sebelum Expired</option>
                                    <option value="15_hari_sebelum_expired">15 Hari Sebelum Expired</option>
                                    <option value="7_hari_sebelum_expired">7 Hari Sebelum Expired</option>
                                    <option value="berdasarkan_tanggal_expired">Berdasarkan Tanggal Expired</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group cbo-filter-periode-expired" id="cbo_berdasarkan_tanggal_expired">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="dtp_expirydate">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_periodeexpired">Submit</button>
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
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            //Date range picker
            $('#dtp_expirydate').daterangepicker();

            //Buat hidden date picker kalau bukan berdasarkan_tanggal_expired
            $("div#cbo_berdasarkan_tanggal_expired").hide();

            $("#tbl_expirydate").DataTable({
                // "dom": '<"top"f><Brt><"bottom"ip>',
                "dom": 'Bfrtip',
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                "processing": true,
                // "serverSide": true,
                "ajax": {
                    "url": '{{ route('reports.getexpirydate') }}',
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}",
                        kriteria: document.querySelector('#cbo_periodeexpired').value,
                        isiFilter: ""
                    },
                    // dataSrc: '',
                    "xhrFields": {
                        withCredentials: true
                    }
                },
                "columns": [{
                    "data": "entiti"
                }, {
                    "data": "sku"
                }, {
                    "data": "namabarang"
                }, {
                    "data": "jumlah",
                    render: $.fn.DataTable.render.number(',', '.', 2, '')
                }, {
                    "data": "satk"
                }, {
                    "data": "golongan"
                }, {
                    "data": "kategori"
                }, {
                    "data": "nobatch"
                }, {
                    "data": "ed",
                    render: $.fn.DataTable.render.moment('D MMM YYYY')
                }, {
                    "data": "harimenujuexpired"
                }, {
                    "data": "pabrik"
                }, {
                    "data": "jenis"
                }, {
                    "data": "discontinue"
                }],
                /* columnDefs: [{
                    targets: [3],
                    render: $.fn.DataTable.render.number(',', '.', 2, '')
                }, {
                    targets: [8],
                    render: $.fn.DataTable.render.moment('D MMM YYYY')
                }], */
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
                    let numFormat = $.fn.DataTable.render.number(',', '.', 0, '').display;
                    $(api.column(3).footer()).html(numFormat(subTotalJumlah) + '(' + numFormat(
                        grandTotalJumlah) + ')');
                },
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_expirydate_wrapper .col-md-6:eq(0)');
        });

        //FILTER
        const btnPeriodeExpired = document.querySelector('#btn_periodeexpired');
        btnPeriodeExpired.addEventListener('click', refreshExpiryDate);
        const cboPeriodeExpired = document.querySelector('#cbo_periodeexpired');
        cboPeriodeExpired.onchange = function() {
            let periodeExpired = cboPeriodeExpired.value;
            $("div.cbo-filter-periode-expired").hide();
            $("#cbo_" + periodeExpired).show();
        };

        function refreshExpiryDate() {
            let filterPeriodeExpired = cboPeriodeExpired.value;
            let isiFilterPeriodeExpired;
            if (filterPeriodeExpired == "berdasarkan_tanggal_expired") {
                isiFilterPeriodeExpired = document.querySelector('#dtp_expirydate').value;
            }
            $("#tbl_expirydate").DataTable().context[0].ajax.data._token = "{{ csrf_token() }}";
            $("#tbl_expirydate").DataTable().context[0].ajax.data.kriteria = filterPeriodeExpired;
            $("#tbl_expirydate").DataTable().context[0].ajax.data.isiFilter = isiFilterPeriodeExpired;
            $("#tbl_expirydate").DataTable().clear().draw();
            $("#tbl_expirydate").DataTable().ajax.url('{{ route('reports.getexpirydate') }}').load();
        };
    </script>
@endsection
