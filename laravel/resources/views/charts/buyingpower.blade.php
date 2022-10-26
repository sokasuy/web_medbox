@extends('layouts.chart')
@section('title')
    <title>APOTEK MEDBOX | Buying Power</title>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- LINE CHART -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Buying Power
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
                                    <input type="text" class="form-control float-right" id="dtp_buyingpowerharian">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="btn_buyingpowerharian">Submit</button>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="canvas_buyingpowerchart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">Your browser
                            does
                            not
                            support the canvas element.
                        </canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">

        </div>
        <!-- /.col (RIGHT) -->
    </div>
    <!-- /.row -->
@endsection

@section('jsbawah')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', (event) => {
            //Date range picker
            $('#dtp_buyingpowerharian').daterangepicker();
        });

        //SALES
        // function purchaseChart() {
        let labelSales = {{ Js::from($labels['sales']) }};
        let dataSales = {{ Js::from($data['sales']) }};
        const dataSalesChart = {
            labels: labelSales,
            datasets: [{
                label: 'Pembelian',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(29, 18, 230)',
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
            document.getElementById('canvas_buyingpowerchart'),
            configSales
        );
        // };
    </script>
@endsection
