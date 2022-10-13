@extends('layouts.main')

@section('title')
    <title>APOTEK MEDBOX | Dashboard</title>
@endsection

@section('cssatas')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
    <!-- /.row -->
    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <!-- /.card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
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
                                <select class="form-control select2bs4placeholder" id="cbo_supplierpembelian"
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
                                <select class="form-control select2bs4" id="cbo_tahunpembelian" style="width: 100%;">
                                    @foreach ($dataCbo['tahunPembelian'] as $d)
                                        <option value="{{ $d->tahun }}"> {{ $d->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btnPurchaseChart">Submit</button>
                        </div>
                    </div>
                    <div class="tab-content p-0">
                        <!-- Morris chart - Purchase -->
                        <div class="chart tab-pane active" id="purchase-chart" style="position: relative; height: 250px;">
                            <canvas id="purchase-chart-canvas" height="155" style="height: 100%;">Your browser does not
                                support the canvas element.
                            </canvas>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- /.card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
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
                    <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="profit-loss-chart"
                            style="position: relative; height: 250px;">
                            <canvas id="profit-loss-chart-canvas" height="155" style="height: 100%;">Your browser does not
                                support the canvas element.</canvas>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </section>
        <!-- /.Left col -->

        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-6 connectedSortable">
            <!-- Map card -->
            <!-- /.card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
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
                    <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="sales-chart" style="position: relative; height: 250px;">
                            <canvas id="sales-chart-canvas" height="155" style="height: 100%;">Your browser does not
                                support the canvas element.</canvas>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- /.card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Grafik Obat Terlaris
                    </h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#bestseller-chart" data-toggle="tab">Line</a>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="bestseller-chart" style="position: relative; height: 250px;">
                            <canvas id="bestseller-chart-canvas" height="155" style="height: 100%;">Your browser does
                                not support the canvas element.</canvas>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </section>
        <!-- right col -->
    </div>
@endsection

@section('jsbawah')
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script type="text/javascript">
        //SELECT2
        //==========================================================================================
        // $(function() {
        //FUNGSINYA INI SAMA DENGAN DOMContentLoaded
        // });
        //==========================================================================================

        document.addEventListener('DOMContentLoaded', (event) => {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4placeholder').select2({
                theme: 'bootstrap4',
                placeholder: "SEMUA SUPPLIER",
                allowClear: true
            });
            $('.select2bs4').select2({
                theme: 'bootstrap4',
            });

            // purchaseChart();
            salesChart();
            profitLossChart();
            bestsellerChart();
        });

        //CHARTS
        //==========================================================================================
        // let monthName = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        //PURCHASE
        // function purchaseChart() {
        let labelsPurchase = {{ Js::from($labels['purchase']) }};
        let dataPurchase = {{ Js::from($data['purchase']) }};
        const dataPurchaseChart = {
            labels: labelsPurchase,
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
            document.getElementById('purchase-chart-canvas'),
            configPurchase
        );
        // };

        //SALES
        function salesChart() {
            let labelsSales = {{ Js::from($labels['sales']) }};
            let dataSales = {{ Js::from($data['sales']) }};
            const dataSalesChart = {
                labels: labelsSales,
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
                document.getElementById('sales-chart-canvas'),
                configSales
            );
        };

        //PROFIT and LOSS
        function profitLossChart() {
            let labelsProfit = {{ Js::from($labels['profit']) }};
            let dataProfit = {{ Js::from($data['profit']) }};
            const dataProfitChart = {
                labels: labelsProfit,
                datasets: [{
                    label: 'Laba-Rugi',
                    backgroundColor: 'rgb(36, 4, 4)',
                    borderColor: 'rgb(255, 54, 54)',
                    data: dataProfit,
                    pointBackgroundColor: 'rgb(36, 4, 4)',
                    pointRadius: 5,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: 'rgb(255,255,255)',
                    fill: false,
                    tension: 0.5
                }]
            };
            const configProfit = {
                type: 'line',
                data: dataProfitChart,
                options: {}
            };
            const myChartProfit = new Chart(
                document.getElementById('profit-loss-chart-canvas'),
                configProfit
            );
        };

        //BESTSELLER
        function bestsellerChart() {
            let labelsBestseller = {{ Js::from($labels['bestseller']) }};
            let dataBestseller = {{ Js::from($data['bestseller']) }};
            const dataBestsellerChart = {
                labels: labelsBestseller,
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
            const configBestseller = {
                type: 'line',
                data: dataBestsellerChart,
                options: {}
            };
            const myChartBestseller = new Chart(
                document.getElementById('bestseller-chart-canvas'),
                configBestseller
            );
        };
        //==========================================================================================

        //FILTER
        //==========================================================================================
        const btnPurchaseChart = document.querySelector('#btnPurchaseChart');
        btnPurchaseChart.addEventListener('click', refreshPurchaseChart);

        function refreshPurchaseChart() {
            let supplierpembelian = document.querySelector('#cbo_supplierpembelian').value;
            let tahunpembelian = document.querySelector('#cbo_tahunpembelian').value;
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
                        // alert(response.msg.labels);
                        // alert(response.msg.data);

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
        //==========================================================================================
    </script>
@endsection
