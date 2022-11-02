@extends('layouts.charts')
@section('title')
    <title>APOTEK MEDBOX | Buying Power</title>
@endsection

@section('navlist')
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="#" class="nav-link active">
                <i class="far fa-circle nav-icon"></i>
                <p>Buying Power</p>
            </a>
        </li>
    </ul>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">Home</a></li>
    <li class="breadcrumb-item">Charts</li>
    <li class="breadcrumb-item active">BuyingPower</li>
@endsection

@section('content')
    <div class="row">
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
            <!-- LINE CHART -->
            {{-- BUYING POWER DAILY --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Buying Power (Daily)
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="dtp_buyingpowerdaily">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_buyingpowerdaily">Submit</button>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="canvas_buyingpowerdailychart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">Your browser
                            does
                            not
                            support the canvas element.
                        </canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            {{-- BUYING POWER DAILY --}}

            {{-- PENJUALAN DAILY --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Penjualan (Daily)
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="dtp_salesdaily">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_salesdaily">Submit</button>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="canvas_salesdailychart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">Your browser
                            does
                            not
                            support the canvas element.
                        </canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            {{-- PENJUALAN DAILY --}}

            {{-- JUMLAH TRANSAKSI DAILY --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Jumlah Transaksi (Daily)
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="dtp_transactiondaily">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_transactiondaily">Submit</button>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="canvas_transactiondailychart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">Your browser
                            does
                            not
                            support the canvas element.
                        </canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            {{-- JUMLAH TRANSAKSI DAILY --}}
            <!-- /.card -->
        </div>
        <!-- /.col (LEFT) -->

        <!-- /.col (RIGHT) -->
        <div class="col-md-6">
            <!-- LINE CHART -->
            {{-- BUYING POWER HOURLY --}}
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Buying Power (Hourly)
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group date" id="dp_buyingpowerhourly" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#dp_buyingpowerhourly" id="date_buyingpowerhourly" />
                                    <div class="input-group-append" data-target="#dp_buyingpowerhourly"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_buyingpowerhourly">Submit</button>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="canvas_buyingpowerhourlychart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">Your browser
                            does
                            not
                            support the canvas element.
                        </canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            {{-- BUYING POWER HOURLY --}}

            {{-- SALES HOURLY --}}
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Penjualan (Hourly)
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group date" id="dp_saleshourly" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#dp_saleshourly" id="date_saleshourly" />
                                    <div class="input-group-append" data-target="#dp_saleshourly"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_saleshourly">Submit</button>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="canvas_saleshourlychart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">Your browser
                            does
                            not
                            support the canvas element.
                        </canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            {{-- SALES HOURLY --}}

            {{-- TRANSACTION HOURLY --}}
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Transaksi (Hourly)
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group date" id="dp_transactionhourly" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#dp_transactionhourly" id="date_transactionhourly" />
                                    <div class="input-group-append" data-target="#dp_transactionhourly"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_transactionhourly">Submit</button>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="canvas_transactionhourlychart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">Your browser
                            does
                            not
                            support the canvas element.
                        </canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            {{-- SALES HOURLY --}}
            <!-- /.card -->
        </div>
        <!-- /.col (RIGHT) -->
    </div>
    <!-- /.row -->
@endsection

@section('jsbawah')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', (event) => {
            //<!-- daterangepicker -->
            $('#dtp_buyingpowerdaily').daterangepicker({
                startDate: moment().subtract(30, 'day')
                // endDate: moment().startOf('hour').add(32, 'hour'),
                // locale: {
                //     format: 'Y-M-d'
                // }
            });
            $('#dtp_salesdaily').daterangepicker({
                startDate: moment().subtract(30, 'day')
            });
            $('#dtp_transactiondaily').daterangepicker({
                startDate: moment().subtract(30, 'day')
            });

            // <!-- Tempusdominus Bootstrap 4 -->
            $('#dp_buyingpowerhourly').datetimepicker({
                defaultDate: moment(),
                format: 'L'
            });
            $('#dp_saleshourly').datetimepicker({
                defaultDate: moment(),
                format: 'L'
            });
            $('#dp_transactionhourly').datetimepicker({
                defaultDate: moment(),
                format: 'L'
            });

            // const dtpBuyingPowerDaily = document.querySelector('#dtp_buyingpowerdaily');
            // dtpBuyingPowerDaily.value = "09/28/2022 - 10/28/2022"
        });

        //DAILY BUYING POWER
        // function buyingPowerDaily() {
        let labelDailyBuyingPower = {{ Js::from($labels['dailybuyingpower']) }};
        let dataDailyBuyingPower = {{ Js::from($data['dailybuyingpower']) }};
        const dataDailyBuyingPowerChart = {
            labels: labelDailyBuyingPower,
            datasets: [{
                label: 'Buying Power (Daily)',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(29, 18, 230)',
                data: dataDailyBuyingPower,
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configDailyBuyingPower = {
            type: 'line',
            data: dataDailyBuyingPowerChart,
            options: {}
        };
        const myChartDailyBuyingPower = new Chart(
            document.getElementById('canvas_buyingpowerdailychart'),
            configDailyBuyingPower
        );
        // };

        //HOURLY BUYING POWER
        // function buyingPowerHourly() {
        let labelHourlyBuyingPower = {{ Js::from($labels['hourlybuyingpower']) }};
        let dataHourlyBuyingPower = {{ Js::from($data['hourlybuyingpower']) }};
        const dataHourlyBuyingPowerChart = {
            labels: labelHourlyBuyingPower,
            datasets: [{
                label: 'Buying Power (Hourly)',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(51, 223, 242)',
                data: dataHourlyBuyingPower,
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configHourlyBuyingPower = {
            type: 'line',
            data: dataHourlyBuyingPowerChart,
            options: {}
        };
        const myChartHourlyBuyingPower = new Chart(
            document.getElementById('canvas_buyingpowerhourlychart'),
            configHourlyBuyingPower
        );
        // };

        //DAILY SALES
        // function salesDaily() {
        let labelDailySales = {{ Js::from($labels['dailysales']) }};
        let dataDailySales = {{ Js::from($data['dailysales']) }};
        const dataDailySalesChart = {
            labels: labelDailySales,
            datasets: [{
                label: 'Penjualan (Daily)',
                backgroundColor: 'rgb(36, 4, 4)',
                borderColor: 'rgb(255, 54, 54)',
                data: dataDailySales,
                pointBackgroundColor: 'rgb(36, 4, 4)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configDailySales = {
            type: 'line',
            data: dataDailySalesChart,
            options: {}
        };
        const myChartDailySales = new Chart(
            document.getElementById('canvas_salesdailychart'),
            configDailySales
        );
        // };

        //HOURLY SALES
        // function salesHourly() {
        let labelHourlySales = {{ Js::from($labels['hourlysales']) }};
        let dataHourlySales = {{ Js::from($data['hourlysales']) }};
        const dataHourlySalesChart = {
            labels: labelHourlySales,
            datasets: [{
                label: 'Penjualan (Hourly)',
                backgroundColor: 'rgb(1, 20, 0)',
                borderColor: 'rgb(33, 186, 20)',
                data: dataHourlySales,
                pointBackgroundColor: 'rgb(1, 20, 0)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configHourlySales = {
            type: 'line',
            data: dataHourlySalesChart,
            options: {}
        };
        const myChartHourlySales = new Chart(
            document.getElementById('canvas_saleshourlychart'),
            configHourlySales
        );
        // };

        //DAILY TRANSACTION
        // function transactionDaily() {
        let labelDailyTransaction = {{ Js::from($labels['dailytransaction']) }};
        let dataDailyTransaction = {{ Js::from($data['dailytransaction']) }};
        const dataDailyTransactionChart = {
            labels: labelDailyTransaction,
            datasets: [{
                label: 'Transaksi (Daily)',
                backgroundColor: 'rgb(6, 36, 33)',
                borderColor: 'rgb(2, 250, 225)',
                data: dataDailyTransaction,
                pointBackgroundColor: 'rgb(6, 36, 33)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configDailyTransaction = {
            type: 'line',
            data: dataDailyTransactionChart,
            options: {}
        };
        const myChartDailyTransaction = new Chart(
            document.getElementById('canvas_transactiondailychart'),
            configDailyTransaction
        );
        // };

        //HOURLY TRANSACTION
        // function salesHourly() {
        let labelHourlyTransaction = {{ Js::from($labels['hourlytransaction']) }};
        let dataHourlyTransaction = {{ Js::from($data['hourlytransaction']) }};
        const dataHourlyTransactionChart = {
            labels: labelHourlyTransaction,
            datasets: [{
                label: 'Transaksi (Hourly)',
                backgroundColor: 'rgb(56, 25, 19)',
                borderColor: 'rgb(247, 102, 5)',
                data: dataHourlyTransaction,
                pointBackgroundColor: 'rgb(56, 25, 19)',
                pointRadius: 5,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgb(255,255,255)',
                fill: false,
                tension: 0.5
            }]
        };
        const configHourlyTransaction = {
            type: 'line',
            data: dataHourlyTransactionChart,
            options: {}
        };
        const myChartHourlyTransaction = new Chart(
            document.getElementById('canvas_transactionhourlychart'),
            configHourlyTransaction
        );
        // };

        //FILTER
        //==========================================================================================
        //DAILY BUYING POWER
        const btnBuyingPowerDailyChart = document.querySelector('#btn_buyingpowerdaily');
        btnBuyingPowerDailyChart.addEventListener('click', refreshBuyingPowerDailyChart);
        //HOURLY BUYING POWER
        const btnBuyingPowerHourlyChart = document.querySelector('#btn_buyingpowerhourly');
        btnBuyingPowerHourlyChart.addEventListener('click', refreshBuyingPowerHourlyChart);
        //DAILY SALES
        const btnSalesDailyChart = document.querySelector('#btn_salesdaily');
        btnSalesDailyChart.addEventListener('click', refreshSalesDailyChart);
        // HOURLY SALES
        const btnSalesHourlyChart = document.querySelector('#btn_saleshourly');
        btnSalesHourlyChart.addEventListener('click', refreshSalesHourlyChart);
        // DAILY TRANSACTION
        const btnTransactionDailyChart = document.querySelector('#btn_transactiondaily');
        btnTransactionDailyChart.addEventListener('click', refreshTransactionDailyChart);
        // HOURLY TRANSACTION
        const btnTransactionHourlyChart = document.querySelector('#btn_transactionhourly');
        btnTransactionHourlyChart.addEventListener('click', refreshTransactionHourlyChart);

        function refreshBuyingPowerDailyChart() {
            let dtrange = document.querySelector('#dtp_buyingpowerdaily').value;
            $.ajax({
                type: 'POST',
                url: '{{ route('charts.refreshdailybuyingpowerchart') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    isiFilter: dtrange
                },
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartDailyBuyingPower.data.labels = response.msg.labels;
                        myChartDailyBuyingPower.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartDailyBuyingPower.update(); // finally update our chart
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });
        };

        function refreshBuyingPowerHourlyChart() {
            let dpicker = document.querySelector('#date_buyingpowerhourly').value;
            $.ajax({
                type: 'POST',
                url: '{{ route('charts.refreshhourlybuyingpowerchart') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    isiFilter: dpicker
                },
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartHourlyBuyingPower.data.labels = response.msg.labels;
                        myChartHourlyBuyingPower.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartHourlyBuyingPower.update(); // finally update our chart
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });
        };

        function refreshSalesDailyChart() {
            let dtrange = document.querySelector('#dtp_salesdaily').value;
            // alert(dtrange);
            $.ajax({
                type: 'POST',
                url: '{{ route('charts.refreshdailysaleschart') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    isiFilter: dtrange
                },
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartDailySales.data.labels = response.msg.labels;
                        myChartDailySales.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartDailySales.update(); // finally update our chart
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });
        };

        function refreshSalesHourlyChart() {
            let dpicker = document.querySelector('#date_saleshourly').value;
            $.ajax({
                type: 'POST',
                url: '{{ route('charts.refreshhourlysaleschart') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    isiFilter: dpicker
                },
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartHourlySales.data.labels = response.msg.labels;
                        myChartHourlySales.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartHourlySales.update(); // finally update our chart
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });
        };

        function refreshTransactionDailyChart() {
            let dtrange = document.querySelector('#dtp_transactiondaily').value;
            // alert(dtrange);
            $.ajax({
                type: 'POST',
                url: '{{ route('charts.refreshdailytransactionchart') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    isiFilter: dtrange
                },
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartDailyTransaction.data.labels = response.msg.labels;
                        myChartDailyTransaction.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartDailyTransaction.update(); // finally update our chart
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    console.log(response);
                }
            });
        };

        function refreshTransactionHourlyChart() {
            let dpicker = document.querySelector('#date_transactionhourly').value;
            $.ajax({
                type: 'POST',
                url: '{{ route('charts.refreshhourlytransactionchart') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    isiFilter: dpicker
                },
                success: function(response) {
                    if (response.status == 'ok') {
                        myChartHourlyTransaction.data.labels = response.msg.labels;
                        myChartHourlyTransaction.data.datasets[0].data = response.msg
                            .data; // or you can iterate for multiple datasets
                        myChartHourlyTransaction.update(); // finally update our chart
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
