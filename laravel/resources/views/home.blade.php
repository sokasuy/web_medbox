@extends('layouts.main')

@section('title')
    <title>APOTEK MEDBOX | Dashboard</title>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- /.row -->
    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <!-- /.card -->
            {{-- PURCHASE --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Pembelian Obat
                    </h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#purchase-chart" data-toggle="tab">Line</a>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <select class="form-control select2bs4placeholdersupplier" id="cbo_purchasesupplier"
                                    style="width: 100%;">
                                    <option></option>
                                    @foreach ($dataCbo['dataSupplier'] as $d)
                                        <option value="{{ $d->kodekontak }}"> {{ $d->perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2bs4" id="cbo_purchaseyear" style="width: 100%;">
                                    @foreach ($dataCbo['tahunPembelian'] as $d)
                                        <option value="{{ $d->tahun }}"> {{ $d->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_purchasechart">Submit</button>
                        </div>
                    </div>
                    <div class="tab-content p-0">
                        <!-- Morris chart - Purchase -->
                        <div class="chart tab-pane active" id="purchase-chart" style="position: relative; height: 250px;">
                            <canvas id="canvas_purchasechart" height="155" style="height: 100%;">Your browser does not
                                support the canvas element.
                            </canvas>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
            {{-- PURCHASE --}}
            <!-- /.card -->

            <!-- /.card -->
            {{-- PROFIT LOSS --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Laba / Rugi
                    </h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#profit-loss-chart" data-toggle="tab">Line</a>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2bs4" id="cbo_profitlossyear" style="width: 100%;">
                                    @foreach ($dataCbo['tahunPenjualan'] as $d)
                                        <option value="{{ $d->tahun }}"> {{ $d->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_profitlosschart">Submit</button>
                        </div>
                    </div>
                    <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="profit-loss-chart"
                            style="position: relative; height: 250px;">
                            <canvas id="canvas_profitlosschart" height="155" style="height: 100%;">Your browser does not
                                support the canvas element.</canvas>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
            {{-- PROFIT LOSS --}}
            <!-- /.card -->
        </section>
        <!-- /.Left col -->

        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-6 connectedSortable">
            <!-- Map card -->
            <!-- /.card -->
            {{-- SALES --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Penjualan Obat Apotek
                    </h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#sales-chart" data-toggle="tab">Line</a>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2bs4" id="cbo_salesyear" style="width: 100%;">
                                    @foreach ($dataCbo['tahunPenjualan'] as $d)
                                        <option value="{{ $d->tahun }}"> {{ $d->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_saleschart">Submit</button>
                        </div>
                    </div>
                    <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="sales-chart" style="position: relative; height: 250px;">
                            <canvas id="canvas_saleschart" height="155" style="height: 100%;">Your browser does not
                                support the canvas element.</canvas>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
            {{-- SALES --}}
            <!-- /.card -->

            <!-- /.card -->
            {{-- OBAT TERLARIS --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Obat Terlaris
                    </h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#bestseller-chart-line" data-toggle="tab">Line</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#bestseller-chart-doughnut" data-toggle="tab">Doughnut</a>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" id="cbo_bestsellerfiltercategory" style="width: 100%;">
                                    <option value="berdasarkan_tahun">Berdasarkan Tahun</option>
                                    <option value="berdasarkan_bulan">Berdasarkan Bulan</option>
                                    <option value="berdasarkan_tanggal">Berdasarkan Tanggal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group cbo-filter-kategori-bestseller" id="cbo_berdasarkan_tahun">
                                <select class="form-control select2bs4" id="cbo_bestselleryear" style="width: 100%;">
                                    @foreach ($dataCbo['tahunPenjualan'] as $d)
                                        <option value="{{ $d->tahun }}"> {{ $d->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group cbo-filter-kategori-bestseller" id="cbo_berdasarkan_bulan">
                                <select class="form-control select2bs4" id="cbo_bestsellermonth" style="width: 100%;">
                                    @foreach ($dataCbo['bulanPenjualan'] as $d)
                                        <option value="{{ $d->periode }}"> {{ $d->periode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group cbo-filter-kategori-bestseller" id="cbo_berdasarkan_tanggal">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="dtp_datebased">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_bestsellerchart">Submit</button>
                        </div>
                    </div>
                    <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="bestseller-chart-line"
                            style="position: relative; height: 250px;">
                            <canvas id="canvas_bestsellerchart_line" height="155" style="height: 100%;">Your browser
                                does
                                not support the canvas element.</canvas>
                        </div>
                        {{-- ######### dari jhonatan ######## --}}
                        <div class="chart tab-pane" id="bestseller-chart-doughnut"
                            style="position: relative; height: auto;">
                            <canvas id="canvas_bestsellerchart_doughnut" height="155" style="height: 100%;">Your
                                browser
                                does
                                not support the canvas element.</canvas>
                        </div>
                        {{-- ######### dari jhonatan ######## --}}
                    </div>
                </div><!-- /.card-body -->
            </div>
            {{-- OBAT TERLARIS --}}
            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>
@endsection

@section('jsbawah')
    <script type="text/javascript">
        //==========================================================================================
        // $(function() {
        //FUNGSINYA INI SAMA DENGAN DOMContentLoaded
        // });
        //==========================================================================================

        document.addEventListener('DOMContentLoaded', (event) => {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4placeholdersupplier').select2({
                theme: 'bootstrap4',
                placeholder: "SEMUA SUPPLIER",
                allowClear: true
            });
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            //Date range picker
            $('#dtp_datebased').daterangepicker();

            // purchaseChart();
            // salesChart();
            // profitLossChart();
            // bestsellerChart();

            $("div#cbo_berdasarkan_bulan").hide();
            $("div#cbo_berdasarkan_tanggal").hide();
        });

        // 4 Oktober 2023 untuk memindah combobox langsung ke tahun yang sekarang
        function selectElement(id, valueToSelect) {
            let element = document.getElementById(id);
            element.value = valueToSelect;
        }
        let tahunSekarang = {{ Js::from($tahunSekarang) }};
        selectElement('cbo_purchaseyear', tahunSekarang);
        selectElement('cbo_profitlossyear', tahunSekarang);
        selectElement('cbo_salesyear', tahunSekarang);
        selectElement('cbo_bestselleryear', tahunSekarang);

        //CHARTS
        //==========================================================================================
        // let monthName = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        //PURCHASE
        // function purchaseChart() {
        let labelPurchase = {{ Js::from($labels['purchase']) }};
        let dataPurchase = {{ Js::from($data['purchase']) }};
        const dataPurchaseChart = {
            labels: labelPurchase,
            datasets: [{
                label: 'Pembelian',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(29, 18, 230)',
                data: dataPurchase,
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configPurchase = {
            type: 'line',
            data: dataPurchaseChart,
            options: {}
        };
        const myChartPurchase = new Chart(
            document.getElementById('canvas_purchasechart'),
            configPurchase
        );
        // };

        //SALES
        // function salesChart() {
        let labelSales = {{ Js::from($labels['sales']) }};
        let dataSales = {{ Js::from($data['sales']) }};
        const dataSalesChart = {
            labels: labelSales,
            datasets: [{
                label: 'Penjualan',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(51, 223, 242)',
                data: dataSales,
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configSales = {
            type: 'line',
            data: dataSalesChart,
            options: {}
        };
        const myChartSales = new Chart(
            document.getElementById('canvas_saleschart'),
            configSales
        );
        // };

        //PROFIT and LOSS
        // function profitLossChart() {
        let labelProfitLoss = {{ Js::from($labels['profitloss']) }};
        let dataProfitLoss = {{ Js::from($data['profitloss']) }};
        const dataProfitLossChart = {
            labels: labelProfitLoss,
            datasets: [{
                label: 'Laba-Rugi',
                backgroundColor: 'rgb(36, 4, 4)',
                borderColor: 'rgb(255, 54, 54)',
                data: dataProfitLoss,
                pointBackgroundColor: 'rgb(36, 4, 4)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configProfitLoss = {
            type: 'line',
            data: dataProfitLossChart,
            options: {}
        };
        const myChartProfitLoss = new Chart(
            document.getElementById('canvas_profitlosschart'),
            configProfitLoss
        );
        // };

        //BESTSELLER
        // function bestsellerChart() {
        let labelBestseller = {{ Js::from($labels['bestseller']) }};
        let dataBestseller = {{ Js::from($data['bestseller']) }};
        const dataBestsellerChartLine = {
            labels: labelBestseller,
            datasets: [{
                label: 'Obat Terlaris',
                backgroundColor: 'rgb(1, 20, 0)',
                borderColor: 'rgb(33, 186, 20)',
                data: dataBestseller,
                pointBackgroundColor: 'rgb(1, 20, 0)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configBestsellerLine = {
            type: 'line',
            data: dataBestsellerChartLine,
            options: {}
        };
        const myChartBestsellerLine = new Chart(
            document.getElementById('canvas_bestsellerchart_line'),
            configBestsellerLine
        );
        // {{-- ######### dari jhonatan ######## --}}
        const dataBestsellerChartDoughnut = {
            labels: labelBestseller,
            datasets: [{
                label: 'Obat Terlaris',
                backgroundColor: ['rgb(255, 51, 51)', 'rgb(255, 252, 51)', 'rgb(193, 255, 51)',
                    'rgb(51, 255, 191)', 'rgb(51, 209, 255)', 'rgb(51, 98, 255)', 'rgb(163, 51, 255)',
                    'rgb(235, 51, 255)', 'rgb(255, 51, 180)', 'rgb(232, 240, 161)'
                ],
                borderColor: 'rgb(255, 255, 255)',
                borderWidth: 1,
                data: dataBestseller,
                hoverBorderColor: 'rgb(0, 0, 0)',
                // hoverBorderWidth: 4,
                hoverOffset: 8
            }]
        };
        const configBestsellerDoughnut = {
            type: 'doughnut',
            data: dataBestsellerChartDoughnut,
            options: {}
        };
        const myChartBestsellerDoughnut = new Chart(
            document.getElementById('canvas_bestsellerchart_doughnut'),
            configBestsellerDoughnut
        );
        // {{-- ######### dari jhonatan ######## --}}
        // };
        //==========================================================================================

        //FILTER
        //==========================================================================================
        //PEMBELIAN
        const btnPurchaseChart = document.querySelector('#btn_purchasechart');
        btnPurchaseChart.addEventListener('click', refreshPurchaseChart);
        //PENJUALAN
        const btnSalesChart = document.querySelector('#btn_saleschart');
        btnSalesChart.addEventListener('click', refreshSalesChart);
        //LABA/RUGI
        const btnProfitLossChart = document.querySelector('#btn_profitlosschart');
        btnProfitLossChart.addEventListener('click', refreshProfitLossChart);
        //OBAT TERLARIS
        const btnBestsellerChart = document.querySelector('#btn_bestsellerchart');
        btnBestsellerChart.addEventListener('click', refreshBestsellerChart);
        const cboKategoriFilterBestseller = document.querySelector('#cbo_bestsellerfiltercategory');
        cboKategoriFilterBestseller.onchange = function() {
            let filterBestsellerValue = cboKategoriFilterBestseller.value;
            $("div.cbo-filter-kategori-bestseller").hide();
            $("#cbo_" + filterBestsellerValue).show();
        };

        // cboKategoriFilterBestseller.addEventListener("change", () => {
        //     let filterBestsellerValue = cboKategoriFilterBestseller.value;
        //     $("div.cbo-filter-kategori-bestseller").hide();
        //     $("#cbo_" + filterBestsellerValue).show();
        // });
        // cboKategoriFilterBestseller.addEventListener('change', changeBestsellerCategory);

        function refreshPurchaseChart() {
            let supplierpembelian = document.querySelector('#cbo_purchasesupplier').value;
            let tahunpembelian = document.querySelector('#cbo_purchaseyear').value;
            if (!supplierpembelian) {
                supplierpembelian = "SEMUA";
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('home.refreshpurchasechart') }}',
                //bisa pakai cara echo ini
                // data: {
                //     '_token': '<?php echo csrf_token(); ?>',
                //     'supplier': supplierpembelian
                // },
                //Atau cara ini
                data: {
                    _token: "{{ csrf_token() }}",
                    supplier: supplierpembelian,
                    tahun: tahunpembelian
                },
                //atau bisa pakai cara ini
                // data:'no_bpp='+no_bpp,
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartPurchase.data.labels = response.msg.labels;
                        myChartPurchase.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartPurchase.update(); // finally update our chart
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });
        };

        function refreshSalesChart() {
            let tahunpenjualan = document.querySelector('#cbo_salesyear').value;
            $.ajax({
                type: 'POST',
                url: '{{ route('home.refreshsaleschart') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    tahun: tahunpenjualan
                },
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartSales.data.labels = response.msg.labels;
                        myChartSales.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartSales.update(); // finally update our chart
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });
        };

        function refreshProfitLossChart() {
            let tahunprofitloss = document.querySelector('#cbo_profitlossyear').value;
            $.ajax({
                type: 'POST',
                url: '{{ route('home.refreshprofitlosschart') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    tahun: tahunprofitloss
                },
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartProfitLoss.data.labels = response.msg.labels;
                        myChartProfitLoss.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartProfitLoss.update(); // finally update our chart
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });
        };

        function refreshBestsellerChart() {
            let filterBestsellerValue = cboKategoriFilterBestseller.value;
            let isiFilterBestsellerValue;
            let cboPeriodeBestsellerValue;
            let myArr = filterBestsellerValue.split("_");

            if (myArr[1] === "tahun") {
                cboPeriodeBestsellerValue = document.querySelector('#cbo_bestselleryear');
            } else if (myArr[1] === "bulan") {
                cboPeriodeBestsellerValue = document.querySelector('#cbo_bestsellermonth');
            } else if (myArr[1] === "tanggal") {
                cboPeriodeBestsellerValue = document.querySelector('#dtp_datebased');
            }
            isiFilterBestsellerValue = cboPeriodeBestsellerValue.value;
            $.ajax({
                type: 'POST',
                url: '{{ route('home.refreshbestsellerchart') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    kriteria: myArr[1],
                    isiFilter: isiFilterBestsellerValue
                },
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartBestsellerLine.data.labels = response.msg.labels;
                        myChartBestsellerLine.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartBestsellerLine.update(); // finally update our chart
                        // {{-- ######### dari jhonatan ######## --}}
                        myChartBestsellerDoughnut.data.labels = response.msg.labels;
                        myChartBestsellerDoughnut.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartBestsellerDoughnut.update(); // finally update our chart
                        // {{-- ######### dari jhonatan ######## --}}
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });
        };
        //==========================================================================================
    </script>
@endsection
