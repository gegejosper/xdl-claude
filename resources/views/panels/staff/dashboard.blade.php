@extends('layouts.panel')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div>
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    Welcome, {{ Auth::user()->name }}
                </h1>
                <span class="text-muted fs-7">{{ now()->format('l, F d Y') }}</span>
            </div>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> New Job Order
            </a>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- ─── TODAY ────────────────────────────────────────────────── --}}
            <div class="mb-3 fw-bold text-uppercase text-muted fs-8">
                <i class="fa fa-sun-o me-1"></i> Today — {{ now()->format('M d, Y') }}
            </div>
            <div class="row g-4 mb-6">
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0" style="background:linear-gradient(135deg,#CC0000,#7f0000)">
                        <div class="card-body text-white">
                            <div class="fs-8 opacity-75 text-uppercase fw-semibold">Orders Today</div>
                            <div class="fs-1 fw-bolder mt-1">{{ $today_orders }}</div>
                            <div class="fs-8 opacity-60 mt-1">job orders created</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0" style="background:linear-gradient(135deg,#AA0000,#660000)">
                        <div class="card-body text-white">
                            <div class="fs-8 opacity-75 text-uppercase fw-semibold">Sales Today</div>
                            <div class="fs-3 fw-bolder mt-1">₱{{ number_format($today_sales, 2) }}</div>
                            <div class="fs-8 opacity-60 mt-1">total amount</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0" style="background:linear-gradient(135deg,#333333,#111111)">
                        <div class="card-body text-white">
                            <div class="fs-8 opacity-75 text-uppercase fw-semibold">Collected Today</div>
                            <div class="fs-3 fw-bolder mt-1">₱{{ number_format($today_paid, 2) }}</div>
                            <div class="fs-8 opacity-60 mt-1">payments received</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0" style="background:linear-gradient(135deg,#222222,#0d0d0d)">
                        <div class="card-body text-white">
                            <div class="fs-8 opacity-75 text-uppercase fw-semibold">Pending</div>
                            <div class="fs-1 fw-bolder mt-1">{{ $unpaid_orders + $partial_orders }}</div>
                            <div class="fs-8 opacity-60 mt-1">
                                {{ $unpaid_orders }} unpaid · {{ $partial_orders }} partial
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── THIS MONTH ───────────────────────────────────────────── --}}
            <div class="mb-3 fw-bold text-uppercase text-muted fs-8">
                <i class="fa fa-calendar me-1"></i> This Month — {{ now()->format('F Y') }}
            </div>
            <div class="row g-4 mb-6">
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted fs-8 text-uppercase fw-semibold">Total Orders</div>
                            <div class="fs-1 fw-bolder text-primary mt-1">{{ $month_orders }}</div>
                            <div class="text-muted fs-8 mt-1">job orders this month</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted fs-8 text-uppercase fw-semibold">Monthly Sales</div>
                            <div class="fs-3 fw-bolder text-dark mt-1">₱{{ number_format($month_sales, 2) }}</div>
                            <div class="text-muted fs-8 mt-1">total amount billed</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted fs-8 text-uppercase fw-semibold">Collected</div>
                            <div class="fs-3 fw-bolder text-success mt-1">₱{{ number_format($month_collected, 2) }}</div>
                            <div class="text-muted fs-8 mt-1">payments received</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted fs-8 text-uppercase fw-semibold">Outstanding</div>
                            <div class="fs-3 fw-bolder text-danger mt-1">₱{{ number_format($month_balance, 2) }}</div>
                            <div class="text-muted fs-8 mt-1">{{ $inqueue_orders }} in-queue</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 mb-5">

                {{-- ─── DAILY CHART ─────────────────────────────────────── --}}
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title fw-bold">Daily Sales — {{ now()->format('F Y') }}</h3>
                        </div>
                        <div class="card-body pt-2">
                            @if(count($chart_labels) > 0)
                                <canvas id="daily_chart" height="220"></canvas>
                            @else
                                <div class="text-center text-muted py-10">No data for this month yet.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ─── ORDER STATUS ────────────────────────────────────── --}}
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title fw-bold">Order Status</h3>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bullet bullet-dot bg-danger h-10px w-10px"></span>
                                    <span class="text-muted fw-semibold">Unpaid</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bolder text-danger fs-5">{{ $unpaid_orders }}</span>
                                    <a href="{{ route('transactions.index', ['payment_status' => 'unpaid']) }}"
                                        class="btn btn-xs btn-light-danger py-1 px-2 fs-8">View</a>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bullet bullet-dot bg-warning h-10px w-10px"></span>
                                    <span class="text-muted fw-semibold">Partially Paid</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bolder text-warning fs-5">{{ $partial_orders }}</span>
                                    <a href="{{ route('transactions.index', ['payment_status' => 'partial']) }}"
                                        class="btn btn-xs btn-light-warning py-1 px-2 fs-8">View</a>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bullet bullet-dot bg-info h-10px w-10px"></span>
                                    <span class="text-muted fw-semibold">In Queue</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bolder text-info fs-5">{{ $inqueue_orders }}</span>
                                    <a href="{{ route('transactions.index', ['claim_status' => 'in-queue']) }}"
                                        class="btn btn-xs btn-light-info py-1 px-2 fs-8">View</a>
                                </div>
                            </div>

                            @if($month_sales > 0)
                            @php $rate = round(($month_collected / $month_sales) * 100, 1); @endphp
                            <div class="mt-5">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fw-semibold fs-7">Collection Rate</span>
                                    <span class="fw-bold fs-7 {{ $rate >= 80 ? 'text-success' : ($rate >= 50 ? 'text-warning' : 'text-danger') }}">
                                        {{ $rate }}%
                                    </span>
                                </div>
                                <div class="progress h-6px">
                                    <div class="progress-bar {{ $rate >= 80 ? 'bg-success' : ($rate >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                        style="width:{{ min($rate, 100) }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <span class="text-muted fs-8">₱{{ number_format($month_collected, 0) }} collected</span>
                                    <span class="text-muted fs-8">₱{{ number_format($month_sales, 0) }} billed</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5">

                {{-- ─── UPCOMING DEADLINES ──────────────────────────────── --}}
                @if($upcoming_deadlines->count())
                <div class="col-lg-5">
                    <div class="card h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title fw-bold">
                                <i class="fa fa-clock-o text-danger me-2"></i>Upcoming Deadlines
                                <span class="badge badge-light-danger ms-2">{{ $upcoming_deadlines->count() }}</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0 pb-2">
                            @foreach($upcoming_deadlines as $d)
                            @php
                                $days_left = now()->startOfDay()->diffInDays($d->deadline, false);
                                $urgency   = $days_left === 0 ? 'danger' : ($days_left <= 2 ? 'warning' : 'info');
                            @endphp
                            <div class="d-flex align-items-center py-3 border-bottom">
                                <div class="me-3">
                                    <span class="badge badge-light-{{ $urgency }} fw-bold" style="min-width:50px">
                                        @if($days_left === 0) TODAY
                                        @elseif($days_left === 1) TMRW
                                        @else {{ $days_left }}d
                                        @endif
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('transactions.show', $d->id) }}"
                                        class="fw-bold text-gray-800 text-hover-primary d-block">
                                        {{ $d->transaction_number }}
                                    </a>
                                    <span class="text-muted fs-7">
                                        {{ $d->customer?->last_name }}, {{ $d->customer?->first_name }}
                                    </span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">₱{{ number_format($d->balance, 2) }}</div>
                                    <span class="badge badge-light-{{ $d->payment_status === 'unpaid' ? 'danger' : 'warning' }} fs-8">
                                        {{ $d->payment_status_label }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="card-footer py-3">
                            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-light-primary w-100">View All</a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ─── RECENT JOB ORDERS ───────────────────────────────── --}}
                <div class="col-lg-{{ $upcoming_deadlines->count() ? '7' : '12' }}">
                    <div class="card">
                        <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                            <h3 class="card-title fw-bold">Recent Job Orders</h3>
                            <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus me-1"></i> New
                            </a>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-2">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light fs-8 text-uppercase">
                                            <th class="ps-4">Job Order #</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                            <th>Payment</th>
                                            <th class="pe-4">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_orders as $txn)
                                        <tr>
                                            <td class="ps-4">
                                                <a href="{{ route('transactions.show', $txn->id) }}" class="fw-bold text-primary">
                                                    {{ $txn->transaction_number }}
                                                </a>
                                                @if($txn->deadline && $txn->deadline->lte(now()->addDays(3)) && $txn->claim_status === 'in-queue')
                                                    <span class="badge badge-light-danger ms-1 fs-9">Due {{ $txn->deadline->diffForHumans() }}</span>
                                                @endif
                                            </td>
                                            <td class="fw-semibold">{{ $txn->customer?->last_name }}, {{ $txn->customer?->first_name }}</td>
                                            <td class="fw-bold">₱{{ number_format($txn->total_amount, 2) }}</td>
                                            <td class="{{ $txn->balance > 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                                ₱{{ number_format($txn->balance, 2) }}
                                            </td>
                                            <td>
                                                @php $pc = match($txn->payment_status) {
                                                    'paid'    => 'badge-light-success',
                                                    'partial' => 'badge-light-warning',
                                                    default   => 'badge-light-danger',
                                                }; @endphp
                                                <span class="badge {{ $pc }}">{{ $txn->payment_status_label }}</span>
                                            </td>
                                            <td class="pe-4 text-muted fs-7">{{ $txn->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8 text-muted">
                                                <i class="fa fa-inbox fs-2 d-block mb-2"></i>
                                                No job orders yet.
                                                <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary mt-3 d-block mx-auto" style="width:fit-content">
                                                    Create First Order
                                                </a>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($recent_orders->count())
                            <div class="pt-3 border-top">
                                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-light-primary">
                                    View All Job Orders
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>{{-- end row --}}
        </div>
    </div>
</div>
@endsection

@section('jslinks')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
$(document).ready(function () {
    const labels = @json($chart_labels);
    const sales  = @json($chart_sales);
    const orders = @json($chart_orders);

    if (!labels.length) return;

    const ctx = document.getElementById('daily_chart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label:           'Sales (₱)',
                    data:            sales,
                    backgroundColor: 'rgba(204,0,0,0.7)',
                    borderColor:     'rgba(204,0,0,1)',
                    borderWidth:     1,
                    borderRadius:    3,
                    yAxisID:         'y_sales',
                },
                {
                    label:           'Orders',
                    data:            orders,
                    type:            'line',
                    borderColor:     '#e53935',
                    backgroundColor: 'rgba(229,57,53,0.1)',
                    borderWidth:     2,
                    pointRadius:     3,
                    tension:         0.3,
                    fill:            true,
                    yAxisID:         'y_orders',
                },
            ],
        },
        options: {
            responsive:          true,
            maintainAspectRatio: true,
            interaction:         { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.yAxisID === 'y_sales'
                            ? ' ₱' + parseFloat(ctx.raw).toLocaleString('en-PH', { minimumFractionDigits: 2 })
                            : ' ' + ctx.raw + ' orders',
                    },
                },
            },
            scales: {
                y_sales: {
                    type:        'linear',
                    position:    'left',
                    beginAtZero: true,
                    ticks:       { callback: v => '₱' + v.toLocaleString() },
                    grid:        { color: 'rgba(0,0,0,0.05)' },
                },
                y_orders: {
                    type:        'linear',
                    position:    'right',
                    beginAtZero: true,
                    ticks:       { stepSize: 1 },
                    grid:        { drawOnChartArea: false },
                },
                x: { grid: { display: false } },
            },
        },
    });
});
</script>
@endsection
