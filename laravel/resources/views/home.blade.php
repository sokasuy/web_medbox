@extends('layouts.main')

@section('title')
<title>APOTEK MEDBOX | Dashboard</title>
@endsection

@section('content')
<!-- /.row -->
<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
    <!-- Custom tabs (Charts with tabs)-->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i>
                Purchase
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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i>
                Sales
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

    </section>
    <!-- /.Left col -->
</div>
@endsection

@section('jsbawah')
<script type="text/javascript">
    // let year = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    let labelsPurchase =  {{ Js::from($labels['purchase']) }};
    let usersPurchase =  {{ Js::from($data['purchase']) }};

    // alert(labelsPurchase['bulan']));

    const dataPurchaseChart = {
    labels: labelsPurchase,
    datasets: [{
        label: 'Purchase',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: usersPurchase,
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
        label: 'Sales',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: usersSales,
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
