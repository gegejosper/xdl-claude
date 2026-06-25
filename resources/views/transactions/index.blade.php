@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Transactions</h1>
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> New Job Order
                </a> -->
                @if(!$today_closed)
                <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> New Job Order
                </a>
                @else
                <span class="btn btn-sm btn-secondary disabled">
                    <i class="fa fa-lock me-1"></i> Sales Closed
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- Filters --}}
            <div class="card mb-5">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('transactions.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Job Order # or Customer Name" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Payment</label>
                            <select name="payment_status" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach(\App\Models\Transaction::PAYMENT_STATUS as $k => $v)
                                    <option value="{{ $k }}" {{ request('payment_status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Claim</label>
                            <select name="claim_status" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach(\App\Models\Transaction::CLAIM_STATUS as $k => $v)
                                    <option value="{{ $k }}" {{ request('claim_status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Date From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Date To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 min-w-120px">Date / Time</th>
                                    <th class="min-w-130px">Job Order #</th>
                                    <th class="min-w-180px">Customer</th>
                                    <th class="min-w-100px">Amount</th>
                                    <th class="min-w-100px">Balance</th>
                                    <th class="min-w-100px">Payment</th>
                                    <th class="min-w-100px">Claim</th>
                                    <th class="min-w-80px text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $txn)
                                <tr>
                                    <td class="ps-4">
                                        <span class="d-block fw-semibold">{{ $txn->created_at->format('M d, Y') }}</span>
                                        <span class="text-muted fs-7">{{ $txn->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('transactions.show', $txn->id) }}" class="fw-bold text-primary">
                                            {{ $txn->transaction_number }}
                                        </a>
                                        @if($txn->deadline)
                                            <br><span class="text-muted fs-8"><i class="fa fa-clock-o me-1"></i>{{ $txn->deadline->format('M d, Y') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $txn->customer?->first_name }} {{ $txn->customer?->last_name }}
                                        <br><span class="text-muted fs-7">{{ $txn->customer?->mobile_num }}</span>
                                    </td>
                                    <td class="fw-bold">₱{{ number_format($txn->total_amount, 2) }}</td>
                                    <td class="{{ $txn->balance > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                        ₱{{ number_format($txn->balance, 2) }}
                                    </td>
                                    <td>
                                        @php
                                            $pay_class = match($txn->payment_status) {
                                                'paid'     => 'badge-light-success',
                                                'partial'  => 'badge-light-warning',
                                                'canceled' => 'badge-light-dark',
                                                default    => 'badge-light-danger',
                                            };
                                        @endphp
                                        <span class="badge {{ $pay_class }}">{{ $txn->payment_status_label }}</span>
                                    </td>
                                    <td>
                                        <select class="claim-status-select claim-{{ $txn->claim_status }}"
                                            data-id="{{ $txn->id }}"
                                            data-current="{{ $txn->claim_status }}">
                                            @foreach(\App\Models\Transaction::CLAIM_STATUS as $k => $v)
                                                <option value="{{ $k }}" {{ $txn->claim_status === $k ? 'selected' : '' }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('transactions.show', $txn->id) }}"
                                            class="btn btn-sm btn-icon btn-light-primary" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if(!$txn->is_finalized)
                                        <a href="{{ route('transactions.edit', $txn->id) }}"
                                            class="btn btn-sm btn-icon btn-light-warning" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endif
                                        @if(!in_array($txn->payment_status, ['paid', 'canceled']))
                                        <button class="btn btn-sm btn-icon btn-light-success btn-receive-payment"
                                            data-id="{{ $txn->id }}"
                                            data-balance="{{ $txn->balance }}"
                                            data-joborder="{{ $txn->transaction_number }}"
                                            title="Receive Payment">
                                            <i class="fas fa-money-bill"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">No transactions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Receive Payment Modal --}}
<div class="modal fade" id="modal_payment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Receive Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Job Order: <strong id="payment_job_order"></strong></p>
                <p class="mb-4">Balance: <strong class="text-danger" id="payment_balance"></strong></p>
                <input type="hidden" id="payment_txn_id">
                <div class="mb-3">
                    <label class="form-label required fw-semibold">Payment Method</label>
                    <select id="payment_method" class="form-select">
                        @foreach(\App\Models\TransactionPayment::PAYMENT_METHODS as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label required fw-semibold">Amount Received (₱)</label>
                    <input type="number" step="0.01" id="payment_amount" class="form-control" placeholder="0.00">
                </div>
                <div class="mb-3" id="payment_change_row" style="display:none">
                    <label class="form-label fw-semibold">Change</label>
                    <input type="text" id="payment_change" class="form-control" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btn_confirm_payment">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jslinks')
<style>
.claim-status-select {
    border: none;
    outline: none;
    border-radius: 6px;
    padding: 3px 6px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    appearance: auto;
}
.claim-status-select.claim-in-queue  { background: #e8f4fd; color: #0d6efd; }
.claim-status-select.claim-ready     { background: #fff3cd; color: #856404; }
.claim-status-select.claim-claimed   { background: #d1e7dd; color: #0f5132; }
.claim-status-select.claim-canceled  { background: #f8d7da; color: #842029; }
.claim-status-select:disabled        { opacity: 0.6; cursor: not-allowed; }
</style>
<script>
$(document).ready(function () {

    // Inline claim status update
    $(document).on('change', '.claim-status-select', function () {
        const $sel    = $(this);
        const id      = $sel.data('id');
        const prev    = $sel.data('current');
        const status  = $sel.val();

        $sel.prop('disabled', true);

        $.ajax({
            url:    '/panel/transactions/' + id + '/claim',
            method: 'POST',
            data:   { _token: '{{ csrf_token() }}', claim_status: status },
            success: function (res) {
                if (res.success) {
                    $sel.data('current', status)
                        .removeClass('claim-in-queue claim-ready claim-claimed claim-canceled')
                        .addClass('claim-' + status);
                    Swal.fire({
                        icon: 'success', title: 'Status Updated',
                        timer: 1200, showConfirmButton: false,
                        toast: true, position: 'top-end',
                    });
                }
            },
            error: function (xhr) {
                $sel.val(prev); // revert
                const errs = xhr.responseJSON?.errors ?? {};
                const msg  = Object.values(errs).flat().join('\n');
                Swal.fire({ icon: 'error', title: 'Update Failed',
                    text: msg || 'An unexpected error occurred.', confirmButtonText: 'OK' });
            },
            complete: function () {
                $sel.prop('disabled', false);
            },
        });
    });

    let current_balance = 0;

    $(document).on('click', '.btn-receive-payment', function () {
        const d = $(this).data();
        current_balance = parseFloat(d.balance);
        $('#payment_txn_id').val(d.id);
        $('#payment_job_order').text(d.joborder);
        $('#payment_balance').text('₱' + parseFloat(d.balance).toFixed(2));
        $('#payment_amount').val('');
        $('#payment_method').val('cash');
        $('#payment_change_row').hide();
        $('#modal_payment').modal('show');
    });

    $('#payment_amount').on('input', function () {
        const paid   = parseFloat($(this).val()) || 0;
        const change = paid - current_balance;
        if (paid > 0) {
            $('#payment_change').val(change >= 0 ? '₱' + change.toFixed(2) : '—');
            $('#payment_change_row').show();
        } else {
            $('#payment_change_row').hide();
        }
    });

    $('#btn_confirm_payment').on('click', function () {
        const $btn = $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
        const id   = $('#payment_txn_id').val();

        $.ajax({
            url:    '/panel/transactions/' + id + '/payment',
            method: 'POST',
            data: {
                _token:         '{{ csrf_token() }}',
                amount_paid:    $('#payment_amount').val(),
                payment_method: $('#payment_method').val(),
            },
            success: function (res) {
                if (res.success) {
                    $('#modal_payment').modal('hide');
                    Swal.fire({
                        icon:              'success',
                        title:             'Payment Recorded',
                        text:              'Redirecting to receipt...',
                        timer:             1200,
                        showConfirmButton: false,
                    }).then(() => { window.location.href = res.receipt_url; });
                }
            },
            error: function (xhr) {
                const errs = xhr.responseJSON?.errors ?? {};
                const msg  = Object.values(errs).flat().join('\n');
                Swal.fire({
                    icon:             'error',
                    title:            'Payment Failed',
                    text:             msg || 'An unexpected error occurred.',
                    confirmButtonText:'OK',
                });
                $btn.prop('disabled', false).text('Confirm Payment');
            },
        });
    });

});
</script>
@endsection
