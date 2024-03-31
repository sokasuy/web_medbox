@extends('layouts.reports')
@section('title')
    <title>APOTEK MEDBOX | Data Penjualan</title>
@endsection

@section('headertitle')
    <h1>REPORTS PENJUALAN</h1>
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
            <a href="{{ route('reports.expirydate') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Expiry Date</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('reports.penjualan') }}" class="nav-link active">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Penjualan</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('reports.summarypenjualan') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Summary Penjualan</p>
            </a>
        </li>
    </ul>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">Home</a></li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">Data Penjualan</li>
@endsection

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Penjualan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control select2bs4" id="cbo_periodepenjualan" style="width: 100%;">
                                    <option value="hari_ini">Hari Ini</option>
                                    <option value="3_hari">3 Hari - Hari ini</option>
                                    <option value="7_hari">7 Hari - Hari ini</option>
                                    <option value="14_hari">14 Hari - Hari ini</option>
                                    <option value="bulan_berjalan">Bulan ini</option>
                                    <option value="semua">Semua</option>
                                    <option value="berdasarkan_tanggal_penjualan">Berdasarkan Tanggal Penjualan</option>
                                </select>
                            </div>
                        </div>
                        <!-- ############# Tambahan Jhonatan ############# -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="select2bs4" multiple="multiple" data-placeholder="Pilih Grup Member"
                                    style="width: 100%;" id="cbo_filter_grup_member">
                                    @foreach ($grupmember as $d)
                                        <option> {{ $d->grupmember }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- ############# Tambahan Jhonatan ############# -->
                        <div class="col-md-2">
                            <div class="form-group cbo-filter-periode-penjualan" id="cbo_berdasarkan_tanggal_penjualan">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="dtp_penjualan">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_periodepenjualan">Submit</button>
                        </div>
                    </div>
                    <table id="tbl_penjualan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ENTITI</th>
                                <th>NO INVOICE</th>
                                <th>TANGGAL</th>
                                <th>PEMBAYARAN</th>
                                <th>SKU</th>
                                <th>NAMA BARANG</th>
                                <th>QTY</th>
                                <th>SATUAN</th>
                                <th>HARGA</th>
                                <th>JUMLAH</th>
                                <th>STATUS BARANG</th>
                                <th>ADD DATE</th>
                                <th>EDIT DATE</th>
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
            $('#dtp_penjualan').daterangepicker();

            //Buat hidden date picker kalau bukan berdasarkan_tanggal_expired
            $("div#cbo_berdasarkan_tanggal_penjualan").hide();

            $("#tbl_penjualan").DataTable({
                "dom": 'Bfrtip',
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                "processing": true,
                "ajax": {
                    "url": '{{ route('reports.getpenjualan') }}',
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}",
                        kriteriaPeriode: document.querySelector('#cbo_periodepenjualan').value,
                        isiFilterPeriode: "",
                        isiFilterGrupMember: ""
                    },
                    "xhrFields": {
                        withCredentials: true
                    }
                },
                "columns": [{
                    "data": "entiti"
                }, {
                    "data": "noinvoice"
                }, {
                    "data": "tanggal",
                    render: $.fn.DataTable.render.moment('D MMM YYYY')
                }, {
                    "data": "pembayaran"
                }, {
                    "data": "sku"
                }, {
                    "data": "namabarang"
                }, {
                    "data": "qty",
                    render: $.fn.DataTable.render.number(',', '.', 0, '')
                }, {
                    "data": "satuan"
                }, {
                    "data": "harga",
                    render: $.fn.DataTable.render.number(',', '.', 2, '')
                }, {
                    "data": "jumlah",
                    render: $.fn.DataTable.render.number(',', '.', 2, '')
                }, {
                    "data": "statusbarang"
                }, {
                    "data": "adddate",
                    render: $.fn.dataTable.render.moment('YYYY-MM-DDTHH:mm:ss.SSSSZ',
                        'D MMM YYYY HH:mm:ss')
                }, {
                    "data": "editdate",
                    render: $.fn.dataTable.render.moment('YYYY-MM-DDTHH:mm:ss.SSSSZ',
                        'D MMM YYYY HH:mm:ss')
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
                    let grandTotalJumlah = api
                        .column(9)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    let subTotalJumlah = api
                        .column(9, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer with a subtotal
                    let numFormat = $.fn.dataTable.render.number(',', '.', 2, '').display;
                    $(api.column(9).footer()).html(numFormat(subTotalJumlah) + '(' + numFormat(
                        grandTotalJumlah) + ')');
                },
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_penjualan_wrapper .col-md-6:eq(0)');
        });

        //FILTER
        const btnPeriodePenjualan = document.querySelector('#btn_periodepenjualan');
        btnPeriodePenjualan.addEventListener('click', refreshPenjualan);
        const cboPeriodePenjualan = document.querySelector('#cbo_periodepenjualan');
        cboPeriodePenjualan.onchange = function() {
            let periodePenjualan = cboPeriodePenjualan.value;
            $("div.cbo-filter-periode-penjualan").hide();
            $("#cbo_" + periodePenjualan).show();
        };

        function refreshPenjualan() {
            let filterPeriode = cboPeriodePenjualan.value;
            let isiFilterPeriode;
            let isiFilterGrupMember;
            if (filterPeriode == "berdasarkan_tanggal_penjualan") {
                isiFilterPeriode = document.querySelector('#dtp_penjualan').value;
            }
            isiFilterGrupMember = $('#cbo_filter_grup_member').val();
            $("#tbl_penjualan").DataTable().context[0].ajax.data._token = "{{ csrf_token() }}";
            $("#tbl_penjualan").DataTable().context[0].ajax.data.kriteriaPeriode = filterPeriode;
            $("#tbl_penjualan").DataTable().context[0].ajax.data.isiFilterPeriode = isiFilterPeriode;
            $("#tbl_penjualan").DataTable().context[0].ajax.data.isiFilterGrupMember = isiFilterGrupMember;
            $("#tbl_penjualan").DataTable().clear().draw();
            $("#tbl_penjualan").DataTable().ajax.url('{{ route('reports.getpenjualan') }}').load();
        };
    </script>
@endsection
