@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    Daily Sales — {{ $record->sales_date->format('F d, Y') }}
                </h1>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-light-primary" onclick="window.print()">
                    <i class="fa fa-print me-1"></i> Print
                </button>
                <a href="{{ route('daily-sales.index') }}" class="btn btn-sm btn-light">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- Summary card --}}
            <div class="row g-5 mb-5" id="printable_summary">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Summary</h3>
                            <div class="card-toolbar">
                                <span class="badge {{ $record->close_type === 'auto' ? 'badge-light-warning' : 'badge-light-primary' }} me-2">
                                    {{ ucfirst($record->close_type) }}-closed
                                </span>
                                <span class="text-muted fs-7">
                                    by {{ $record->closer?->name ?? 'System' }}
                                    at {{ $record->closed_at?->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <div class="border rounded p-4 text-center">
                                        <div class="text-muted fs-7 mb-1">Branch</div>
                                        <div class="fw-bold fs-5">{{ $record->branch->branch_name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-4 text-center">
                                        <div class="text-muted fs-7 mb-1">Total Orders</div>
                                        <div class="fw-bolder fs-2 text-primary">{{ $record->transaction_count }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-4 text-center">
                                        <div class="text-muted fs-7 mb-1">Total Sales</div>
                                        <div class="fw-bolder fs-2 text-success">₱{{ number_format($record->total_sales, 2) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-4 text-center">
                                        <div class="text-muted fs-7 mb-1">Payments Collected</div>
                                        <div class="fw-bolder fs-2 text-info">₱{{ number_format($record->total_payments, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            @if($record->notes)
                            <div class="mt-4 p-3 bg-light rounded">
                                <span class="fw-semibold">Notes:</span> {{ $record->notes }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payments made on this day --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Payments Made on This Day</h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-info fs-7">{{ $daily_payments->count() }} payment(s)</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle mb-0">
                            <thead>
                                <tr class="fw-bold text-muted fs-7 text-uppercase">
                                    <th class="ps-5">#</th>
                                    <th>Job Order</th>
                                    <th>Customer</th>
                                    <th>Method</th>
                                    <th class="text-end">Amount Paid</th>
                                    <th class="text-end">Change</th>
                                    <th class="text-end pe-5">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $payment_methods = \App\Models\TransactionPayment::PAYMENT_METHODS;
                                    $method_badge = [
                                        'cash'          => 'badge-light-success',
                                        'gcash'         => 'badge-light-primary',
                                        'bank_transfer' => 'badge-light-info',
                                        'maya'          => 'badge-light-warning',
                                        'check'         => 'badge-light-secondary',
                                        'others'        => 'badge-light-dark',
                                    ];
                                @endphp
                                @forelse($daily_payments as $pay)
                                <tr>
                                    <td class="ps-5 text-muted fs-7">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('transactions.show', $pay->transaction_id) }}" class="fw-semibold text-primary">
                                            {{ $pay->transaction->transaction_number ?? '#'.$pay->transaction_id }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($pay->transaction?->customer)
                                            {{ $pay->transaction->customer->last_name }}, {{ $pay->transaction->customer->first_name }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php $m = $pay->payment_method ?? 'cash'; @endphp
                                        <span class="badge {{ $method_badge[$m] ?? 'badge-light' }}">
                                            {{ $payment_methods[$m] ?? ucfirst($m) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-success">₱{{ number_format($pay->amount_paid, 2) }}</td>
                                    <td class="text-end text-muted">₱{{ number_format($pay->change_amount ?? 0, 2) }}</td>
                                    <td class="text-end pe-5 text-muted fs-7">{{ $pay->created_at->format('h:i A') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-6">No payments recorded on this day.</td></tr>
                                @endforelse
                            </tbody>
                            @if($daily_payments->count() > 0)
                            <tfoot>
                                <tr class="fw-bold bg-light-subtle">
                                    <td class="ps-5" colspan="4">Total</td>
                                    <td class="text-end text-success">₱{{ number_format($daily_payments->sum('amount_paid'), 2) }}</td>
                                    <td class="text-end text-muted">₱{{ number_format($daily_payments->sum('change_amount'), 2) }}</td>
                                    <td class="pe-5"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{-- Transactions table --}}
            <div class="card" id="printable_transactions">
                <div class="card-header"><h3 class="card-title fw-bold">Transactions on This Day</h3></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle mb-0">
                            <thead>
                                <tr class="fw-bold text-muted fs-7 text-uppercase">
                                    <th class="ps-5">Job Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Payment Status</th>
                                    <th class="text-end pe-5 no-print">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $payment_statuses = \App\Models\Transaction::PAYMENT_STATUS; @endphp
                                @forelse($transactions as $txn)
                                @php
                                    $paid = $txn->payments->where('status','accepted')->sum('amount_paid');
                                @endphp
                                <tr>
                                    <td class="ps-5 fw-semibold">{{ $txn->transaction_number }}</td>
                                    <td>{{ $txn->customer?->last_name }}, {{ $txn->customer?->first_name }}</td>
                                    <td class="fw-bold">₱{{ number_format($txn->total_amount, 2) }}</td>
                                    <td class="text-success">₱{{ number_format($paid, 2) }}</td>
                                    <td class="{{ $txn->balance > 0 ? 'text-danger' : 'text-muted' }}">₱{{ number_format($txn->balance, 2) }}</td>
                                    <td>
                                        @php
                                            $badge_map = ['unpaid'=>'badge-light-danger','partial'=>'badge-light-warning','paid'=>'badge-light-success','canceled'=>'badge-light-dark'];
                                        @endphp
                                        <span class="badge {{ $badge_map[$txn->payment_status] ?? 'badge-light' }}">
                                            {{ $payment_statuses[$txn->payment_status] ?? $txn->payment_status }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-5 no-print">
                                        <a href="{{ route('transactions.show', $txn->id) }}" class="btn btn-xs btn-light-primary">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-6">No transactions on this day.</td></tr>
                                @endforelse
                            </tbody>
                            @if($transactions->count() > 0)
                            <tfoot>
                                <tr class="fw-bold bg-light-subtle">
                                    <td class="ps-5" colspan="2">Totals</td>
                                    <td>₱{{ number_format($transactions->sum('total_amount'), 2) }}</td>
                                    <td class="text-success">₱{{ number_format($transactions->sum(fn($t) => $t->payments->where('status','accepted')->sum('amount_paid')), 2) }}</td>
                                    <td class="text-danger">₱{{ number_format($transactions->sum('balance'), 2) }}</td>
                                    <td></td>
                                    <td class="no-print"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('jslinks')
<style>
@media print {
    #kt_aside, #kt_toolbar, .no-print { display: none !important; }
    body, html { background: white !important; }
}
</style>
@endsection
