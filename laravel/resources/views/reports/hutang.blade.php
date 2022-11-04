@extends('layouts.reports')
@section('title')
    <title>APOTEK MEDBOX | Data Hutang Piutang</title>
@endsection

@section('navlist')
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('reports.hutang') }}" class="nav-link active">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Hutang</p>
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
    <li class="breadcrumb-item active">Data Hutang</li>
@endsection

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hutang</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2bs4" id="cbo_periodejatuhtempo" style="width: 100%;">
                                    <option value="semua">Semua</option>
                                    <option value="sudah_jatuh_tempo">Sudah Jatuh Tempo</option>
                                    <option value="30_hari_sebelum_jatuh_tempo">30 Hari Sebelum Jatuh Tempo</option>
                                    <option value="15_hari_sebelum_jatuh_tempo">15 Hari Sebelum Jatuh Tempo</option>
                                    <option value="7_hari_sebelum_jatuh_tempo">7 Hari Sebelum Jatuh Tempo</option>
                                    <option value="berdasarkan_tanggal_jatuh_tempo">Berdasarkan Tanggal Jatuh Tempo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group cbo-filter-periode-jatuh_tempo" id="cbo_berdasarkan_tanggal_jatuh_tempo">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="dtp_jatuhtempo">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_periodejatuhtempo">Submit</button>
                        </div>
                    </div>
                    <table id="tbl_hutang" class="table table-bordered table-striped">
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
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            //Date range picker
            $('#dtp_jatuhtempo').daterangepicker();

            //Buat hidden date picker kalau bukan berdasarkan_tanggal_expired
            $("div#cbo_berdasarkan_tanggal_jatuh_tempo").hide();

            $("#tbl_hutang").DataTable({
                "dom": 'Bfrtip',
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                "processing": true,
                "ajax": {
                    "url": '{{ route('reports.gethutang') }}',
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}",
                        kriteria: document.querySelector('#cbo_periodejatuhtempo').value,
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
                    "data": "kodetransaksi"
                }, {
                    "data": "tanggal",
                    render: $.fn.DataTable.render.moment('D MMM YYYY')
                }, {
                    "data": "nofaktur"
                }, {
                    "data": "tglfaktur",
                    render: $.fn.DataTable.render.moment('D MMM YYYY')
                }, {
                    "data": "kodekontak"
                }, {
                    "data": "perusahaan"
                }, {
                    "data": "total",
                    render: $.fn.DataTable.render.number(',', '.', 2, '')
                }, {
                    "data": "hutang",
                    render: $.fn.DataTable.render.number(',', '.', 2, '')
                }, {
                    "data": "jangkawaktu"
                }, {
                    "data": "tgljatuhtempo",
                    render: $.fn.dataTable.render.moment('D MMM YYYY')
                }],
                /* columnDefs: [{
                    targets: [7, 8],
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }, {
                    targets: [2, 4, 10],
                    render: $.fn.dataTable.render.moment('D MMM YYYY')
                }], */
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

        //FILTER
        const btnPeriodeJatuhTempo = document.querySelector('#btn_periodejatuhtempo');
        btnPeriodeJatuhTempo.addEventListener('click', refreshJatuhTempo);
        const cboPeriodeJatuhTempo = document.querySelector('#cbo_periodejatuhtempo');
        cboPeriodeJatuhTempo.onchange = function() {
            let periodeJatuhTempo = cboPeriodeJatuhTempo.value;
            $("div.cbo-filter-periode-jatuh-tempo").hide();
            $("#cbo_" + periodeJatuhTempo).show();
        };

        function refreshJatuhTempo() {
            let filterPeriodeJatuhTempo = cboPeriodeJatuhTempo.value;
            let isiFilterPeriodeJatuhTempo
            if (filterPeriodeJatuhTempo == "berdasarkan_tanggal_jatuh_tempo") {
                isiFilterPeriodeJatuhTempo = document.querySelector('#dtp_jatuhtempo').value;
            }
            $("#tbl_hutang").DataTable().context[0].ajax.data._token = "{{ csrf_token() }}";
            $("#tbl_hutang").DataTable().context[0].ajax.data.kriteria = filterPeriodeJatuhTempo;
            $("#tbl_hutang").DataTable().context[0].ajax.data.isiFilter = isiFilterPeriodeJatuhTempo;
            $("#tbl_hutang").DataTable().clear().draw();
            $("#tbl_hutang").DataTable().ajax.url('{{ route('reports.gethutang') }}').load();
        }
    </script>
@endsection
