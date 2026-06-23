@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar d-print-none" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Payment Receipt</h1>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-primary" onclick="window.print()">
                    <i class="fa fa-print me-1"></i> Print
                </button>
                <a href="{{ route('transactions.show', $payment->transaction_id) }}" class="btn btn-sm btn-light">
                    <i class="fa fa-arrow-left me-1"></i> Back to Order
                </a>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4">

                    {{-- Receipt Card --}}
                    <div class="card" id="receipt_card">
                        <div class="card-body p-6">

                            {{-- Shop Header --}}
                            <div class="text-center mb-4">
                                <h4 class="fw-bolder mb-0">{{ config('app.name', 'Printing Shop') }}</h4>
                                <div class="text-muted fs-7">
                                    {{ $payment->transaction->branch?->address ?? '' }}
                                    @if($payment->transaction->branch?->contact_number)
                                        <br>{{ $payment->transaction->branch->contact_number }}
                                    @endif
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div class="fs-7 text-muted fw-semibold">OFFICIAL RECEIPT</div>
                            </div>

                            {{-- Receipt Meta --}}
                            <div class="d-flex justify-content-between fs-7 mb-1">
                                <span class="text-muted">Date</span>
                                <span class="fw-semibold">{{ $payment->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 mb-1">
                                <span class="text-muted">Time</span>
                                <span class="fw-semibold">{{ $payment->created_at->format('h:i A') }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 mb-1">
                                <span class="text-muted">Receipt #</span>
                                <span class="fw-semibold">RCP-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 mb-3">
                                <span class="text-muted">Job Order #</span>
                                <span class="fw-semibold">{{ $payment->transaction->transaction_number }}</span>
                            </div>

                            <div class="separator separator-dashed my-3"></div>

                            {{-- Customer --}}
                            <div class="mb-3">
                                <div class="fs-7 text-muted">Customer</div>
                                <div class="fw-bold">
                                    {{ $payment->transaction->customer?->first_name }}
                                    {{ $payment->transaction->customer?->last_name }}
                                </div>
                                <div class="fs-7 text-muted">{{ $payment->transaction->customer?->mobile_num }}</div>
                            </div>

                            <div class="separator separator-dashed my-3"></div>

                            {{-- Order Summary --}}
                            <div class="fs-7 text-muted mb-2 fw-semibold">ORDER SUMMARY</div>
                            @foreach($payment->transaction->items as $item)
                            <div class="d-flex justify-content-between fs-7 mb-1">
                                <span class="text-truncate pe-3" style="max-width:200px">
                                    {{ $item->item_type_label }}
                                    @if($item->size) <span class="text-muted">({{ $item->size }})</span> @endif
                                    @if($item->item_type === 'tarpaulin')
                                        <span class="text-muted">{{ $item->width }}×{{ $item->height }}ft</span>
                                    @else
                                        × {{ $item->quantity }}
                                    @endif
                                </span>
                                <span class="fw-semibold text-nowrap">₱{{ number_format($item->total, 2) }}</span>
                            </div>
                            @endforeach

                            <div class="separator separator-dashed my-3"></div>

                            {{-- Totals --}}
                            <div class="d-flex justify-content-between fs-7 mb-1">
                                <span class="text-muted">Order Total</span>
                                <span class="fw-semibold">₱{{ number_format($payment->transaction->total_amount, 2) }}</span>
                            </div>

                            @php
                                $prior_payments = $payment->transaction->payments
                                    ->where('status', 'accepted')
                                    ->where('id', '<', $payment->id);
                                $prior_total = $prior_payments->sum('amount_paid');
                                $balance_before = $payment->transaction->total_amount - $prior_total;
                            @endphp

                            @if($prior_total > 0)
                            <div class="d-flex justify-content-between fs-7 mb-1">
                                <span class="text-muted">Previously Paid</span>
                                <span class="fw-semibold text-success">₱{{ number_format($prior_total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 mb-1">
                                <span class="text-muted">Balance Before</span>
                                <span class="fw-semibold">₱{{ number_format($balance_before, 2) }}</span>
                            </div>
                            @endif

                            <div class="separator separator-dashed my-2"></div>

                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold">Payment Method</span>
                                <span class="fw-semibold">
                                    {{ \App\Models\TransactionPayment::PAYMENT_METHODS[$payment->payment_method] ?? ucfirst($payment->payment_method ?? 'Cash') }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold">Amount Paid</span>
                                <span class="fw-bolder fs-5 text-primary">₱{{ number_format($payment->amount_paid, 2) }}</span>
                            </div>

                            @if($payment->change_amount > 0)
                            <div class="d-flex justify-content-between fs-7 mb-1">
                                <span class="text-muted">Change</span>
                                <span class="fw-semibold">₱{{ number_format($payment->change_amount, 2) }}</span>
                            </div>
                            @endif

                            @php
                                $new_balance = $payment->transaction->total_amount
                                    - $payment->transaction->payments->where('status', 'accepted')->where('id', '<=', $payment->id)->sum('amount_paid');
                                $new_balance = max(0, $new_balance);
                            @endphp

                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Remaining Balance</span>
                                <span class="fw-bolder fs-5 {{ $new_balance > 0 ? 'text-danger' : 'text-success' }}">
                                    ₱{{ number_format($new_balance, 2) }}
                                </span>
                            </div>

                            @if($new_balance == 0)
                            <div class="text-center mt-2">
                                <span class="badge badge-light-success fs-7 px-4 py-2">FULLY PAID</span>
                            </div>
                            @endif

                            <div class="separator separator-dashed my-4"></div>

                            {{-- Footer --}}
                            <div class="text-center fs-8 text-muted">
                                <div>Received by: <strong>{{ $payment->transaction->cashier?->name }}</strong></div>
                                @if($payment->transaction->deadline)
                                <div class="mt-1 text-danger fw-semibold">
                                    Deadline: {{ $payment->transaction->deadline->format('M d, Y') }}
                                </div>
                                @endif
                                <div class="mt-3">Thank you for your business!</div>
                                <div class="fs-9 mt-1 text-muted">{{ $payment->created_at->format('Y-m-d H:i:s') }}</div>
                            </div>

                        </div>
                    </div>

                    {{-- Action buttons (hidden when printing) --}}
                    <div class="d-flex gap-2 mt-4 justify-content-center d-print-none">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fa fa-print me-1"></i> Print Receipt
                        </button>
                        <a href="{{ route('transactions.show', $payment->transaction_id) }}" class="btn btn-light-primary">
                            Back to Job Order
                        </a>
                        <a href="{{ route('transactions.index') }}" class="btn btn-light">
                            All Transactions
                        </a>
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
    /* Hide everything except the receipt card */
    #kt_aside,
    #kt_toolbar,
    #kt_header,
    .d-print-none,
    .btn { display: none !important; }

    body, html { background: #fff !important; margin: 0; padding: 0; }

    #kt_content_container {
        max-width: 100% !important;
        padding: 0 !important;
    }

    #receipt_card {
        box-shadow: none !important;
        border: none !important;
        max-width: 80mm; /* Thermal-style width */
        margin: 0 auto;
    }

    #receipt_card .card-body {
        padding: 8px !important;
    }

    .separator-dashed {
        border-top: 1px dashed #999 !important;
    }

    /* Compact font sizes for thermal paper */
    body { font-size: 11px !important; }
    .fs-7 { font-size: 10px !important; }
    .fs-8, .fs-9 { font-size: 9px !important; }
    .fs-5 { font-size: 13px !important; }
}

/* Auto-trigger print on page load if ?print=1 */
</style>
<script>
$(document).ready(function () {
    // Auto-print if ?print=1 in URL
    const params = new URLSearchParams(window.location.search);
    if (params.get('print') === '1') {
        setTimeout(() => window.print(), 500);
    }
});
</script>
@endsection
