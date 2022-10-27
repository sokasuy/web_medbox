@extends('layouts.chart')
@section('title')
    <title>APOTEK MEDBOX | Buying Power</title>
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
            {{-- PENJUALAN DAILY --}}
            <!-- /.card -->
        </div>
        <!-- /.col (LEFT) -->

        <!-- /.col (RIGHT) -->
        <div class="col-md-6">
            <!-- LINE CHART -->
            {{-- BUYING POWER HOURLY --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Buying Power (Hourly)
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#reservationdate" />
                                    <div class="input-group-append" data-target="#reservationdate"
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
            <!-- /.card -->
        </div>
        <!-- /.col (RIGHT) -->
    </div>
    <!-- /.row -->
@endsection

@section('jsbawah')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', (event) => {
            //Date range picker
            $('#dtp_buyingpowerdaily').daterangepicker();

            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });
        });

        //DAILY BUYING POWER
        // function buyingPowerDaily() {
        let labelDailyBuyingPower = {{ Js::from($labels['dailybuyingpower']) }};
        let dataDailyBuyingPower = {{ Js::from($data['dailybuyingpower']) }};
        const dataDailyBuyingPowerChart = {
            labels: labelDailyBuyingPower,
            datasets: [{
                label: 'Buying Power',
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
                label: 'Buying Power',
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
    </script>
@endsection
