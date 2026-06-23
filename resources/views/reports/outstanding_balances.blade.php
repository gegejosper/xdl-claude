@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Outstanding Balances</h1>
            </div>
            <button type="button" class="btn btn-sm btn-light-primary" onclick="window.print()">
                <i class="fa fa-print me-1"></i> Print
            </button>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- Filters (hidden on print) --}}
            <div class="card mb-5 no-print">
                <div class="card-body py-4">
                    <form method="GET" action="{{ route('reports.outstanding-balances') }}" class="row g-3 align-items-end">
                        @if($is_admin)
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7">Branch</label>
                            <select name="branch_id" class="form-select form-select-sm">
                                <option value="">All Branches</option>
                                @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7">Date From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7">Date To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fa fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Print header (visible only on print) --}}
            <div class="print-only mb-4" style="display:none">
                <h2 class="fw-bold text-center mb-1">{{ config('app.name') }}</h2>
                <p class="text-center text-muted mb-0">Outstanding Balances Report — Printed {{ now()->format('F d, Y h:i A') }}</p>
                <hr>
            </div>

            {{-- Summary totals --}}
            <div class="row g-4 mb-5 no-print">
                <div class="col-md-4">
                    <div class="card border-0 bg-light-danger">
                        <div class="card-body py-4 text-center">
                            <div class="text-muted fs-7 mb-1">Total Outstanding</div>
                            <div class="fw-bolder fs-2 text-danger">₱{{ number_format($total_balance, 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-light-warning">
                        <div class="card-body py-4 text-center">
                            <div class="text-muted fs-7 mb-1">Unpaid Orders</div>
                            <div class="fw-bolder fs-2 text-warning">{{ $transactions->where('payment_status','unpaid')->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-light-info">
                        <div class="card-body py-4 text-center">
                            <div class="text-muted fs-7 mb-1">Partially Paid Orders</div>
                            <div class="fw-bolder fs-2 text-info">{{ $transactions->where('payment_status','partial')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title fw-bold">
                        Outstanding Orders
                        <span class="text-muted fs-7 ms-2">({{ $transactions->count() }} records)</span>
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle mb-0">
                            <thead>
                                <tr class="fw-bold text-muted fs-7 text-uppercase">
                                    <th class="ps-5">Job Order #</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    @if($is_admin)<th>Branch</th>@endif
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th class="text-end pe-5 no-print">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $txn)
                                @php $paid = $txn->payments()->where('status','accepted')->sum('amount_paid'); @endphp
                                <tr>
                                    <td class="ps-5 fw-semibold">{{ $txn->transaction_number }}</td>
                                    <td class="text-muted fs-7">{{ $txn->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $txn->customer?->last_name }}, {{ $txn->customer?->first_name }}</div>
                                        <div class="text-muted fs-8">{{ $txn->customer?->mobile_num }}</div>
                                    </td>
                                    @if($is_admin)<td class="text-muted">{{ $txn->branch?->name ?? '—' }}</td>@endif
                                    <td class="fw-bold">₱{{ number_format($txn->total_amount, 2) }}</td>
                                    <td class="text-success">₱{{ number_format($paid, 2) }}</td>
                                    <td class="fw-bolder text-danger">₱{{ number_format($txn->balance, 2) }}</td>
                                    <td>
                                        @if($txn->payment_status === 'unpaid')
                                        <span class="badge badge-light-danger">Unpaid</span>
                                        @else
                                        <span class="badge badge-light-warning">Partial</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-5 no-print">
                                        <a href="{{ route('transactions.show', $txn->id) }}"
                                            class="btn btn-xs btn-light-primary" target="_blank">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $is_admin ? 9 : 8 }}" class="text-center text-muted py-6">
                                        No outstanding balances found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($transactions->count() > 0)
                            <tfoot>
                                <tr class="fw-bold bg-light-subtle">
                                    <td class="ps-5" colspan="{{ $is_admin ? 4 : 3 }}">TOTAL</td>
                                    <td>₱{{ number_format($transactions->sum('total_amount'), 2) }}</td>
                                    <td class="text-success">₱{{ number_format($transactions->sum(fn($t) => $t->payments->where('status','accepted')->sum('amount_paid')), 2) }}</td>
                                    <td class="text-danger fw-bolder">₱{{ number_format($total_balance, 2) }}</td>
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
    .print-only { display: block !important; }
    body, html { background: white !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
}
</style>
@endsection
