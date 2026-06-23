@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Reports</h1>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- Date Filter --}}
            <div class="card mb-5">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Date From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm"
                                value="{{ $date_from->toDateString() }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Date To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm"
                                value="{{ $date_to->toDateString() }}">
                        </div>
                        <div class="col-md-6 d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light">Reset</a>
                            <a href="{{ route('admin.dashboard') }}?date_from={{ now()->toDateString() }}&date_to={{ now()->toDateString() }}"
                                class="btn btn-sm btn-light-info">Today</a>
                            <a href="{{ route('admin.dashboard') }}?date_from={{ now()->startOfMonth()->toDateString() }}&date_to={{ now()->toDateString() }}"
                                class="btn btn-sm btn-light-info">This Month</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="row g-5 mb-5">
                <div class="col-md-3">
                    <div class="card text-white" style="background:linear-gradient(135deg,#CC0000,#7f0000)">
                        <div class="card-body">
                            <div class="fs-7 fw-semibold opacity-75">Total Sales</div>
                            <div class="fs-2 fw-bolder">₱{{ number_format($total_sales, 2) }}</div>
                            <div class="fs-8 opacity-75">{{ $transaction_count }} orders</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background:linear-gradient(135deg,#AA0000,#660000)">
                        <div class="card-body">
                            <div class="fs-7 fw-semibold opacity-75">Total Collected</div>
                            <div class="fs-2 fw-bolder">₱{{ number_format($total_collected, 2) }}</div>
                            <div class="fs-8 opacity-75">{{ $paid_count }} fully paid</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="/panel/reports/outstanding-balances">
                        <div class="card text-white" style="background:linear-gradient(135deg,#333333,#111111)">
                            <div class="card-body">
                                <div class="fs-7 fw-semibold opacity-75">Outstanding Balance</div>
                                <div class="fs-2 fw-bolder">₱{{ number_format($total_balance, 2) }}</div>
                                <div class="fs-8 opacity-75">{{ $unpaid_count }} unpaid · {{ $partial_count }} partial</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background:linear-gradient(135deg,#222222,#111111)">
                        <div class="card-body">
                            <div class="fs-7 fw-semibold opacity-75">Total Expenses</div>
                            <div class="fs-2 fw-bolder">₱{{ number_format($total_expenses + $total_purchases, 2) }}</div>
                            <div class="fs-8">Purch: ₱{{ number_format($total_purchases, 2) }} · Exp: ₱{{ number_format($total_expenses, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5">
                {{-- Net Income --}}
                <div class="col-md-4">
                    <div class="card mb-5">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Net Income</h3>
                        </div>
                        <div class="card-body">
                            @php $net = $total_collected - ($total_expenses + $total_purchases); @endphp
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Collected</span>
                                <span class="fw-bold text-success">₱{{ number_format($total_collected, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Purchases</span>
                                <span class="fw-bold text-danger">- ₱{{ number_format($total_purchases, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Expenses</span>
                                <span class="fw-bold text-danger">- ₱{{ number_format($total_expenses, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bolder fs-5">Net</span>
                                <span class="fw-bolder fs-4 {{ $net >= 0 ? 'text-success' : 'text-danger' }}">
                                    ₱{{ number_format($net, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Quick Stats</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">New Customers</span>
                                <span class="fw-bold">{{ $new_customers }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Paid Orders</span>
                                <span class="badge badge-light-success">{{ $paid_count }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Partially Paid</span>
                                <span class="badge badge-light-warning">{{ $partial_count }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Unpaid</span>
                                <span class="badge badge-light-danger">{{ $unpaid_count }}</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-light-primary w-100">
                                View All Transactions
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Daily Sales Chart --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Daily Sales</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="daily_sales_chart" height="280"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('jslinks')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
$(document).ready(function () {
    const daily_data = @json($daily_sales);
    const labels = Object.keys(daily_data);
    const values = Object.values(daily_data).map(v => parseFloat(v));

    new Chart(document.getElementById('daily_sales_chart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales (₱)',
                data: values,
                backgroundColor: 'rgba(204, 0, 0, 0.7)',
                borderColor: 'rgba(204, 0, 0, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => '₱' + parseFloat(ctx.raw).toFixed(2)
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => '₱' + v.toLocaleString() }
                }
            }
        }
    });
});
</script>
@endsection