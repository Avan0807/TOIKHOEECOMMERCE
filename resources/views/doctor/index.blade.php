@extends('doctor.layouts.master')

@section('main-content')
<div class="container-fluid">
    @include('doctor.layouts.notification')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Trang của bác sĩ</h1>
    </div>

    <!-- Biểu đồ thu nhập -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu đồ thu nhập</h6>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="myAreaChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Biểu đồ Số lượng đặt khám và Dữ liệu bổ sung -->
    <div class="row">
        <!-- Biểu đồ hình tròn -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Số lượng người đặt khám</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="myPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ cột -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Biểu đồ bổ sung</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .sidebar {
        background-color: #0924ec !important;
        background-image: linear-gradient(113deg, #314aff 10%, #60616f 100%) !important;
        background-size: cover !important;
    }
    .chart-area, .chart-pie, .chart-bar {
        position: relative;
        height: 300px;
    }
</style>
@endpush

@push('scripts')
<script type="text/javascript">
    // **Biểu đồ Thu Nhập**
    const data_keys = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
    const data_values = [5000000, 6000000, 5500000, 7000000, 8000000, 7500000, 8500000, 9000000, 9500000, 10000000, 11000000, 12000000]; // Số liệu thu nhập từng tháng (đơn vị VNĐ)


    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data_keys,
            datasets: [{
                label: "Thu nhập (VNĐ)",
                data: data_values,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + 'đ';
                        }
                    }
                }]
            }
        }
    });

    // **Biểu đồ Hình Tròn: Số lượng người đặt khám**
    const pieLabels = ['Khám tổng quát', 'Khám chuyên khoa', 'Khám xét nghiệm'];
    const pieData = [60, 30, 10]; // Số lượng người đặt khám từng loại

    var pieCtx = document.getElementById("myPieChart");
    var myPieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'], // Màu sắc từng phần
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'], // Màu khi hover
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return pieLabels[tooltipItem.dataIndex] + ': ' + pieData[tooltipItem.dataIndex] + ' người';
                        }
                    }
                }
            }
        }
    });

    // **Biểu đồ Cột: Dữ liệu bổ sung**
    const barLabels = ['Dịch vụ A', 'Dịch vụ B', 'Dịch vụ C', 'Dịch vụ D'];
    const barData = [150, 200, 180, 220]; // Dữ liệu số lượng

    var barCtx = document.getElementById("myBarChart");
    var myBarChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [{
                label: "Số lượng sử dụng dịch vụ",
                data: barData,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'], // Màu từng cột
                borderColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value + ' lần';
                        }
                    }
                }]
            }
        }
    });
</script>
@endpush
