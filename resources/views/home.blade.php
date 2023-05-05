<!doctype html>
<html lang="en">
<head>
@include('partials.head-css')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body data-layout="horizontal">
    <div id="layout-wrapper">
    @include('partials.horizontal')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12">
                <div class="col-md-6" style="border: 1px solid black;">
                <div style="text-align: center;">
  <h3>Daily Calls & Messages Leads</h3>
</div>
            <canvas id="barChart"></canvas>
</div>
</div>
            </div>
        </div>
        @include('partials.footer')
    </div>
</div>
@include('partials.right-sidebar')
@include('partials.vendor-scripts')
<script src="{{ asset('js/app.js') }}"></script>
<script>
var chartData = {!! json_encode($chartData) !!};
var ctx = document.getElementById('barChart').getContext('2d');
var barChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
        scales: {
            x: {
                type: 'category',
                stacked: true
            },
            y: {
                stacked: true
            }
        }
    }
});
    </script>
</body>
</html>