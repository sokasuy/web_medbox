@extends('layouts.main')

@section('title')
<title>APOTEK MEDBOX | Dashboard</title>
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
            <div class="tab-content p-0">
                <!-- Morris chart - Purchase -->
                <div class="chart tab-pane active" id="purchase-chart" style="position: relative; height: 300px;">
                    <canvas id="purchase-chart-canvas" height="173" style="height: 100%;">Your browser does not support the canvas element.</canvas>
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
                    style="position: relative; height: 300px;">
                    <canvas id="profit-loss-chart-canvas" height="172" style="height: 100%;">Your browser does not support the canvas element.</canvas>
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
                    <div class="chart tab-pane active" id="sales-chart"
                        style="position: relative; height: 300px;">
                        <canvas id="sales-chart-canvas" height="172" style="height: 100%;">Your browser does not support the canvas element.</canvas>
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
                            <a class="nav-link active" href="#most-popular-chart" data-toggle="tab">Line</a>
                        </li>
                    </ul>
                </div>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content p-0">
                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane active" id="most-popular-chart"
                        style="position: relative; height: 300px;">
                        <canvas id="most-popular-chart-canvas" height="172" style="height: 100%;">Your browser does not support the canvas element.</canvas>
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
<script type="text/javascript">
    // let monthName = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    let labelsPurchase =  {{ Js::from($labels['purchase']) }};
    let usersPurchase =  {{ Js::from($data['purchase']) }};

    const dataPurchaseChart = {
    labels: labelsPurchase,
    datasets: [{
        label: 'Pembelian',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(29, 18, 230)',
        data: usersPurchase,
        pointBackgroundColor:'rgb(255, 99, 132)',
        pointRadius:5,
        pointHoverRadius:5,
        pointHoverBackgroundColor:'rgb(255,255,255)',
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

    let labelsSales =  {{ Js::from($labels['sales']) }};
    let usersSales =  {{ Js::from($data['sales']) }};

    const dataSalesChart = {
    labels: labelsSales,
    datasets: [{
        label: 'Penjualan',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(51, 223, 242)',
        data: usersSales,
        pointBackgroundColor:'rgb(255, 99, 132)',
        pointRadius:5,
        pointHoverRadius:5,
        pointHoverBackgroundColor:'rgb(255,255,255)',
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
</script>
@endsection
