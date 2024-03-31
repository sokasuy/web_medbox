@extends('layouts.reports')
@section('title')
    <title>APOTEK MEDBOX | Data Summary Penjualan</title>
@endsection

@section('headertitle')
    <h1>REPORTS SUMMARY PENJUALAN</h1>
@endsection
@section('cssatas')
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
            <a href="{{ route('reports.penjualan') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Penjualan</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('reports.summarypenjualan') }}" class="nav-link active">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Summary Penjualan</p>
            </a>
        </li>
    </ul>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">Home</a></li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">Data Summary Penjualan</li>
@endsection

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Summary Penjualan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control select2bs4" id="cbo_periodepenjualan" style="width: 100%;">
                                    <option value="semua">Semua</option>
                                    <option value="berdasarkan_tahun">Berdasarkan tahun</option>
                                    <option value="berdasarkan_periode">Berdasarkan Periode</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <!-- ############# Tambahan Jhonatan ############# -->
                            <div class="form-group cbo-berdasarkan-tahun" id="cbo_berdasarkan_tahun">
                                <select class="form-control select2bs4" style="width: 100%;" id="periode_tahun">
                                    @foreach ($dataCbo['tahunPenjualan'] as $d)
                                        <option value="{{ $d->tahun }}"> {{ $d->tahun }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group cbo-berdasarkan-periode-awal" id="cbo_berdasarkan_periode_awal">
                                <select class="form-control select2bs4" style="width: 100%;" id="periode_bulan_awal">
                                    @foreach ($dataCbo['bulanPenjualan'] as $d)
                                        <option value="{{ $d->periode }}"> {{ $d->periode }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <!-- ############# Tambahan Jhonatan ############# -->
                            <div class="form-group cbo-berdasarkan-periode-akhir" id="cbo_berdasarkan_periode_akhir">
                                <select class="form-control select2bs4" style="width: 100%;" id="periode_bulan_akhir">
                                    @foreach ($dataCbo['bulanPenjualan'] as $d)
                                        <option value="{{ $d->periode }}"> {{ $d->periode }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <!-- ############# Tambahan Jhonatan ############# -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="select2bs4" multiple="multiple" data-placeholder="Pilih Grup Member"
                                    style="width: 100%;" id="cbo_filter_grup_member">
                                    @foreach ($dataCbo['grupMember'] as $d)
                                        <option> {{ $d->grupmember }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- ############# Tambahan Jhonatan ############# -->
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_summarypenjualan">Submit</button>
                        </div>
                    </div>
                    <table id="tbl_summary_penjualan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ENTITI</th>
                                <th>PERIODE</th>
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
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

    <!-- GRAFIK SUMMARY PENJUALAN -->
    <!-- ############# Tambahan Jhonatan ############# -->
    <div class="row">
        <!-- /.col -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Summary Penjualan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control select2bs4" id="cbo_grafik_summarypenjualan"
                                    style="width: 100%;">
                                    <option value="semua">Semua</option>
                                    <option value="berdasarkan_tahun">Berdasarkan tahun</option>
                                    <option value="berdasarkan_periode">Berdasarkan Periode</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <!-- ############# Tambahan Jhonatan ############# -->
                            <div class="form-group cbo-grafik-berdasarkan-tahun" id="cbo_grafik_berdasarkan_tahun">
                                <select class="form-control select2bs4" style="width: 100%;" id="periode_tahun_grafik">
                                    @foreach ($dataCbo['tahunPenjualan'] as $d)
                                        <option value="{{ $d->tahun }}"> {{ $d->tahun }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group cbo-grafik-berdasarkan-periode-awal"
                                id="cbo_grafik_berdasarkan_periode_awal">
                                <select class="form-control select2bs4" style="width: 100%;"
                                    id="periode_bulan_awal_grafik">
                                    @foreach ($dataCbo['bulanPenjualan'] as $d)
                                        <option value="{{ $d->periode }}"> {{ $d->periode }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <!-- ############# Tambahan Jhonatan ############# -->
                            <div class="form-group cbo-grafik-berdasarkan-periode-akhir"
                                id="cbo_grafik_berdasarkan_periode_akhir">
                                <select class="form-control select2bs4" style="width: 100%;"
                                    id="periode_bulan_akhir_grafik">
                                    @foreach ($dataCbo['bulanPenjualan'] as $d)
                                        <option value="{{ $d->periode }}"> {{ $d->periode }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"
                                id="btn_grafiksummarypenjualan">Submit</button>
                        </div>
                    </div>
                    <!-- tambahkan elemen canvas untuk chartjs -->
                    <canvas id="GrafikSummaryPenjualan" width="400" height="200"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <!-- ############# Tambahan Jhonatan ############# -->
@endsection

@section('jsbawah')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // $('.select2').select2();

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            //Date range picker
            // $('#dtp_penjualan').daterangepicker();
            // <!-- ############# Tambahan Jhonatan ############# -->
            //Buat hidden
            // $("div.cbo_berdasarkan_tanggal_penjualan").hide();
            $("div#cbo_berdasarkan_tahun").hide();
            $("div#cbo_berdasarkan_periode_awal").hide();
            $("div#cbo_berdasarkan_periode_akhir").hide();
            $("div#cbo_grafik_berdasarkan_tahun").hide();
            $("div#cbo_grafik_berdasarkan_periode_awal").hide();
            $("div#cbo_grafik_berdasarkan_periode_akhir").hide();

            // Logging to check if elements are found
            // console.log("Hidden elements:", $(".cbo_berdasarkan_tahun, .cbo_berdasarkan_periode_awal, .cbo_berdasarkan_periode_akhir").length);
            // <!-- ############# Tambahan Jhonatan ############# -->
            $("#tbl_summary_penjualan").DataTable({
                "dom": 'Bfrtip',
                "paging": true,
                "pageLength": 12,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                "processing": true,
                "ajax": {
                    "url": '{{ route('reports.getsummarypenjualan') }}',
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}",
                        kriteria: document.querySelector('#cbo_periodepenjualan').value,
                        // <!-- ############# Tambahan Jhonatan ############# -->
                        isiFilterPeriodeAwal: "",
                        isiFilterPeriodeAkhir: "",
                        isiFilterGrupMember: ""
                        // <!-- ############# Tambahan Jhonatan ############# -->
                    }
                },
                "columns": [{
                        "data": "entiti"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return row.bulan + ' ' + row.tahun;
                        }
                    },
                    {
                        "data": "total_penjualan",
                        "render": $.fn.DataTable.render.number(',', '.', 2, '')
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();

                    // Remove the formatting to get integer data for summation
                    let intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };

                    // Total over all pages
                    let grandTotalJumlah = api
                        .column(2)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    let subTotalJumlah = api
                        .column(2, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer with a subtotal
                    let numFormat = $.fn.dataTable.render.number(',', '.', 2, '').display;
                    $(api.column(2).footer()).html(numFormat(subTotalJumlah) + '(' + numFormat(
                        grandTotalJumlah) + ')');
                },

                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_summary_penjualan_wrapper .col-md-6:eq(0)');
            // <!-- ############# Tambahan Jhonatan ############# -->
            // Data penjualan per bulan
            // Membuat instansiasi grafik menggunakan data yang disediakan dari controller
            //  GrafikSummaryPenjualan;
            // Membuat instansiasi grafik menggunakan data yang disediakan dari controller
            var grafikCanvas = document.getElementById('GrafikSummaryPenjualan');
            if (grafikCanvas) {
                var ctx = grafikCanvas.getContext('2d');
                var GrafikSummaryPenjualan = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_column($dataChart[1][array_key_first($dataChart[1])], 'label')) ?>,
                        datasets: <?= json_encode($dataChart[0]) ?>
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } else {
                console.error("Elemen dengan id 'GrafikSummaryPenjualan' tidak ditemukan.");
            }
            // <!-- ############# Tambahan Jhonatan ############# -->
        });

        //FILTER
        const btnPeriodePenjualan = document.querySelector('#btn_summarypenjualan');
        btnPeriodePenjualan.addEventListener('click', refreshSummaryPenjualan);
        const cboPeriodePenjualan = document.querySelector('#cbo_periodepenjualan');
        cboPeriodePenjualan.onchange = function() {
            // <!-- ############# Tambahan Jhonatan ############# -->
            if (cboPeriodePenjualan.value === "berdasarkan_periode") {
                $(".cbo-berdasarkan-tahun").hide();
                $(".cbo-berdasarkan-periode-awal").show();
                $(".cbo-berdasarkan-periode-akhir").show();
            } else if (cboPeriodePenjualan.value === "berdasarkan_tahun") {
                $(".cbo-berdasarkan-tahun").show();
                $(".cbo-berdasarkan-periode-awal").hide();
                $(".cbo-berdasarkan-periode-akhir").hide();
            } else if (cboPeriodePenjualan.value === "semua") {
                $(".cbo-berdasarkan-tahun").hide();
                $(".cbo-berdasarkan-periode-awal").hide();
                $(".cbo-berdasarkan-periode-akhir").hide();
            }
        };

        function refreshSummaryPenjualan() {
            let filterPeriodePenjualan = cboPeriodePenjualan.value;
            let isiFilterPeriodeAwal;
            let isiFilterPeriodeAkhir;
            let isiFilterGrupMember;
            // <!-- ############# Tambahan Jhonatan ############# -->
            isiFilterGrupMember = $('#cbo_filter_grup_member').val();
            // <!-- ############# Tambahan Jhonatan ############# -->
            // alert(isiFilterReport2);
            if (filterPeriodePenjualan == "berdasarkan_tahun") {
                isiFilterPeriodeAwal = document.querySelector('#periode_tahun').value;
            } else if (filterPeriodePenjualan == "berdasarkan_periode") {
                isiFilterPeriodeAwal = document.querySelector('#periode_bulan_awal').value;
                isiFilterPeriodeAkhir = document.querySelector('#periode_bulan_akhir').value;
            }
            $("#tbl_summary_penjualan").DataTable().context[0].ajax.data._token = "{{ csrf_token() }}";
            $("#tbl_summary_penjualan").DataTable().context[0].ajax.data.kriteria = filterPeriodePenjualan;
            $("#tbl_summary_penjualan").DataTable().context[0].ajax.data.isiFilterPeriodeAwal = isiFilterPeriodeAwal;
            $("#tbl_summary_penjualan").DataTable().context[0].ajax.data.isiFilterPeriodeAkhir = isiFilterPeriodeAkhir;
            // <!-- ############# Tambahan Jhonatan ############# -->
            $("#tbl_summary_penjualan").DataTable().context[0].ajax.data.isiFilterGrupMember = isiFilterGrupMember;
            // <!-- ############# Tambahan Jhonatan ############# -->
            $("#tbl_summary_penjualan").DataTable().clear().draw();
            $("#tbl_summary_penjualan").DataTable().ajax.url('{{ route('reports.getsummarypenjualan') }}').load();
        };

        // <!-- ############# Tambahan Jhonatan ############# -->
        const cboGrafikSummaryPenjualan = document.querySelector('#cbo_grafik_summarypenjualan');
        cboGrafikSummaryPenjualan.onchange = function() {
            if (cboGrafikSummaryPenjualan.value === "berdasarkan_periode") {
                $(".cbo-grafik-berdasarkan-tahun").hide();
                $(".cbo-grafik-berdasarkan-periode-awal").show();
                $(".cbo-grafik-berdasarkan-periode-akhir").show();
            } else if (cboGrafikSummaryPenjualan.value === "berdasarkan_tahun") {
                $(".cbo-grafik-berdasarkan-tahun").show();
                $(".cbo-grafik-berdasarkan-periode-awal").hide();
                $(".cbo-grafik-berdasarkan-periode-akhir").hide();
            } else if (cboGrafikSummaryPenjualan.value === "semua") {
                $(".cbo-grafik-berdasarkan-tahun").hide();
                $(".cbo-grafik-berdasarkan-periode-awal").hide();
                $(".cbo-grafik-berdasarkan-periode-akhir").hide();
            }
        };
        // <!-- ############# Tambahan Jhonatan ############# -->
        const btnGrafikPeriodePenjualan = document.querySelector('#btn_grafiksummarypenjualan');
        btnGrafikPeriodePenjualan.addEventListener('click', refreshGrafikSummaryPenjualan);

        function refreshGrafikSummaryPenjualan() {
            let filterPeriodePenjualan = cboGrafikSummaryPenjualan.value;
            let isiFilter1;
            let isiFilter2;
            if (filterPeriodePenjualan == "berdasarkan_tahun") {
                isiFilter1 = document.querySelector('#periode_tahun_grafik').value;
            } else if (filterPeriodePenjualan == "berdasarkan_periode") {
                isiFilter1 = document.querySelector('#periode_bulan_awal_grafik').value;
                isiFilter2 = document.querySelector('#periode_bulan_akhir_grafik').value;
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('reports.getgrafiksummarypenjualan') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    kriteria: filterPeriodePenjualan,
                    isiFilter1: isiFilter1,
                    isiFilter2: isiFilter2
                },
                success: function(response) {
                    console.log(response);
                    if (response.status == 'ok') {
                        // Set labels for the chart
                        GrafikSummaryPenjualan.data.labels = response.data[0];
                        // Set datasets for the chart
                        GrafikSummaryPenjualan.data.datasets[0].data = response.data[0][0];
                        GrafikSummaryPenjualan.update(); // Finally update our chart
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });

        };
    </script>
@endsection
