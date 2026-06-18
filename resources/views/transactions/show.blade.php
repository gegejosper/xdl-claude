@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    Job Order #{{ $transaction->transaction_number }}
                </h1>
            </div>
            <div class="d-flex gap-2">
                @if(!$transaction->is_finalized)
                <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-sm btn-light-warning">
                    <i class="fa fa-edit me-1"></i> Edit
                </a>
                @endif
                @if($transaction->payment_status !== 'paid')
                <button class="btn btn-sm btn-light-success btn-receive-payment"
                    data-id="{{ $transaction->id }}"
                    data-balance="{{ $transaction->balance }}"
                    data-joborder="{{ $transaction->transaction_number }}">
                    <i class="fas fa-money-bill me-1"></i> Receive Payment
                </button>
                @endif
                <button class="btn btn-sm btn-light-primary" onclick="window.print()">
                    <i class="fa fa-print me-1"></i> Print
                </button>
                @if(Auth::user()->hasRole('admin'))
                    @if(!$transaction->approved_by)
                    <button class="btn btn-sm btn-light-info" id="btn_approve">
                        <i class="fa fa-check me-1"></i> Approve
                    </button>
                    @endif
                    @if($transaction->approved_by && !$transaction->is_finalized)
                    <button class="btn btn-sm btn-dark" id="btn_finalize">
                        <i class="fa fa-lock me-1"></i> Final
                    </button>
                    @endif
                @endif
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-light">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">


            <div class="row g-5">
                {{-- Job Order Card (Printable) --}}
                <div class="col-lg-8">
                    <div class="card" id="printable_job_order">
                        <div class="card-body">
                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-6">
                                <div>
                                    <h2 class="fw-bolder fs-2 mb-1">JOB ORDER</h2>
                                    <span class="text-muted">{{ $transaction->transaction_number }}</span>
                                    <br><span class="text-muted fs-7">OR: {{ $transaction->or_number }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold fs-6">Date: {{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div class="text-muted fs-7">Time: {{ $transaction->created_at->format('h:i A') }}</div>
                                    @if($transaction->deadline)
                                    <div class="text-danger fw-bold fs-7 mt-1">
                                        <i class="fa fa-clock-o me-1"></i> Deadline: {{ $transaction->deadline->format('M d, Y') }}
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            {{-- Customer Details --}}
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">Customer</label>
                                    <div class="fw-bold fs-5">{{ $transaction->customer?->first_name }} {{ $transaction->customer?->last_name }}</div>
                                    <div class="text-muted">{{ $transaction->customer?->mobile_num }}</div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-semibold">Payment Status</label>
                                    @php
                                        $pay_class = match($transaction->payment_status) {
                                            'paid'    => 'badge-light-success',
                                            'partial' => 'badge-light-warning',
                                            default   => 'badge-light-danger',
                                        };
                                    @endphp
                                    <div><span class="badge {{ $pay_class }} fs-7">{{ $transaction->payment_status_label }}</span></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-semibold">Claim Status</label>
                                    @php
                                        $claim_class = match($transaction->claim_status) {
                                            'ready'    => 'badge-light-warning',
                                            'claimed'  => 'badge-light-success',
                                            'canceled' => 'badge-light-danger',
                                            default    => 'badge-light-info',
                                        };
                                    @endphp
                                    <div><span class="badge {{ $claim_class }} fs-7">{{ $transaction->claim_status_label }}</span></div>
                                    <div class="mt-2">
                                        <select id="claim_status_select" class="form-select form-select-sm">
                                            @foreach(\App\Models\Transaction::CLAIM_STATUS as $k => $v)
                                                <option value="{{ $k }}" {{ $transaction->claim_status === $k ? 'selected' : '' }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if($transaction->note)
                            <div class="mb-4 alert alert-secondary py-2">
                                <strong>Note:</strong> {{ $transaction->note }}
                            </div>
                            @endif

                            @if($transaction->material)
                            <div class="mb-4">
                                <label class="text-muted fw-semibold">Material:</label>
                                <span class="fw-bold ms-2">{{ $transaction->material }}</span>
                            </div>
                            @endif

                            {{-- Items Table --}}
                            <div class="table-responsive mb-5">
                                <table class="table table-bordered table-sm align-middle gs-2 gy-2">
                                    <thead class="bg-light">
                                        <tr class="fw-bolder text-muted">
                                            <th>#</th>
                                            <th>Item Type</th>
                                            <th>Size / Dim</th>
                                            <th>Material</th>
                                            <th class="text-center">Qty / Sq Ft</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Discount</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaction->items as $i => $item)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td class="fw-semibold">{{ $item->item_type_label }}</td>
                                            <td>
                                                @if($item->item_type === 'tarpaulin')
                                                    {{ $item->width }} × {{ $item->height }} ft
                                                @else
                                                    {{ $item->size ?? '—' }}
                                                @endif
                                            </td>
                                            <td>{{ $item->material ?? '—' }}</td>
                                            <td class="text-center">
                                                @if($item->item_type === 'tarpaulin')
                                                    {{ number_format($item->sqft, 2) }} sq ft
                                                @else
                                                    {{ $item->quantity }}
                                                @endif
                                            </td>
                                            <td class="text-end">₱{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end text-danger">₱{{ number_format($item->discount, 2) }}</td>
                                            <td class="text-end fw-bold">₱{{ number_format($item->total, 2) }}</td>
                                        </tr>
                                        @if($item->notes)
                                        <tr class="bg-light">
                                            <td></td>
                                            <td colspan="7" class="text-muted fst-italic fs-7">↳ {{ $item->notes }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot class="fw-bolder">
                                        <tr>
                                            <td colspan="6" class="text-end">Discount:</td>
                                            <td class="text-end text-danger">₱{{ number_format($transaction->discount_amount, 2) }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" class="text-end fs-5">Grand Total:</td>
                                            <td class="text-end fs-5 text-primary">₱{{ number_format($transaction->total_amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            {{-- Footer --}}
                            <div class="row mt-6">
                                <div class="col-6">
                                    <div class="border-top pt-2 text-center">
                                        <div class="fw-bold">{{ $transaction->cashier?->name }}</div>
                                        <div class="text-muted fs-7">Created By</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border-top pt-2 text-center">
                                        @if($transaction->approver)
                                            <div class="fw-bold">{{ $transaction->approver->name }}</div>
                                        @else
                                            <div class="text-muted">—</div>
                                        @endif
                                        <div class="text-muted fs-7">Approved By</div>
                                    </div>
                                </div>
                            </div>

                            @if($transaction->remarks)
                            <div class="mt-4 border-top pt-3">
                                <strong>Remarks:</strong> {{ $transaction->remarks }}
                            </div>
                            @endif

                            <div class="mt-3 d-flex gap-3 fs-7 text-muted">
                                <span>File/Layout: <strong>{{ $transaction->has_file_upload ? 'Yes' : 'No' }}</strong></span>
                                @if($transaction->is_finalized)
                                <span class="badge badge-light-dark">FINALIZED {{ $transaction->finalized_at?->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Payments --}}
                <div class="col-lg-4">
                    <div class="card mb-5">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Payment Summary</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Amount</span>
                                <span class="fw-bold">₱{{ number_format($transaction->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Paid</span>
                                <span class="fw-bold text-success">
                                    ₱{{ number_format($transaction->payments->where('status','accepted')->sum('amount_paid'), 2) }}
                                </span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Balance</span>
                                <span class="fw-bolder fs-5 {{ $transaction->balance > 0 ? 'text-danger' : 'text-success' }}">
                                    ₱{{ number_format($transaction->balance, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Payment History</h3>
                        </div>
                        <div class="card-body p-0">
                            @forelse($transaction->payments->where('status','accepted') as $pay)
                            <div class="d-flex justify-content-between align-items-center px-5 py-3 border-bottom">
                                <div>
                                    <div class="fw-semibold">₱{{ number_format($pay->amount_paid, 2) }}</div>
                                    <div class="text-muted fs-7">{{ $pay->created_at->format('M d, Y h:i A') }}</div>
                                    @if($pay->change_amount > 0)
                                    <div class="text-muted fs-8">Change: ₱{{ number_format($pay->change_amount, 2) }}</div>
                                    @endif
                                </div>
                                <div class="d-flex flex-column align-items-end gap-1">
                                    <span class="badge badge-light-success">Accepted</span>
                                    <a href="{{ route('transactions.payment.receipt', $pay->id) }}"
                                        class="btn btn-xs btn-light-primary fs-8 py-1 px-2" target="_blank">
                                        <i class="fa fa-print me-1"></i>Receipt
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-5">No payments yet.</div>
                            @endforelse
                        </div>
                    </div>
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
                <p>Balance: <strong class="text-danger">₱{{ number_format($transaction->balance, 2) }}</strong></p>
                <div class="mb-3">
                    <label class="form-label required fw-semibold">Amount Received (₱)</label>
                    <input type="number" step="0.01" id="payment_amount" class="form-control" placeholder="0.00">
                </div>
                <div id="payment_change_row" style="display:none" class="mb-3">
                    <label class="form-label fw-semibold">Change</label>
                    <input type="text" id="payment_change" class="form-control" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btn_confirm_payment">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jslinks')
<style>
@media print {
    #kt_aside, #kt_toolbar, .btn, select, #printable_job_order + *, .col-lg-4 { display: none !important; }
    #printable_job_order { box-shadow: none !important; border: none !important; }
    body, html { background: white !important; }
}
</style>
<script>
$(document).ready(function () {
    const balance = {{ $transaction->balance }};

    // Receive Payment button
    $('.btn-receive-payment').on('click', function () {
        $('#payment_amount').val('');
        $('#payment_change_row').hide();
        $('#modal_payment').modal('show');
    });

    $('#payment_amount').on('input', function () {
        const paid   = parseFloat($(this).val()) || 0;
        const change = paid - balance;
        if (paid > 0) {
            $('#payment_change').val(change >= 0 ? '₱' + change.toFixed(2) : '—');
            $('#payment_change_row').show();
        } else {
            $('#payment_change_row').hide();
        }
    });

    $('#btn_confirm_payment').on('click', function () {
        const $btn = $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

        $.ajax({
            url:    '/panel/transactions/{{ $transaction->id }}/payment',
            method: 'POST',
            data:   { _token: '{{ csrf_token() }}', amount_paid: $('#payment_amount').val() },
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
                    confirmButtonText: 'OK',
                });
                $btn.prop('disabled', false).text('Confirm');
            },
        });
    });

    // Claim status update
    $('#claim_status_select').on('change', function () {
        const $select = $(this);
        $.ajax({
            url:    '/panel/transactions/{{ $transaction->id }}/claim',
            method: 'POST',
            data:   { _token: '{{ csrf_token() }}', claim_status: $select.val() },
            success: function (res) {
                if (res.success) {
                    Swal.fire({
                        icon:              'success',
                        title:             'Status Updated',
                        text:              res.message,
                        timer:             1500,
                        showConfirmButton: false,
                    });
                }
            },
            error: function (xhr) {
                const errs = xhr.responseJSON?.errors ?? {};
                const msg  = Object.values(errs).flat().join('\n');
                Swal.fire({
                    icon:             'error',
                    title:            'Update Failed',
                    text:             msg || 'An unexpected error occurred.',
                    confirmButtonText: 'OK',
                });
            },
        });
    });

    // Approve
    $('#btn_approve').on('click', function () {
        Swal.fire({
            icon:               'question',
            title:              'Approve this order?',
            text:               'The order will be marked as approved.',
            showCancelButton:   true,
            confirmButtonText:  'Yes, Approve',
            cancelButtonText:   'Cancel',
            confirmButtonColor: '#3085d6',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            const $btn = $('#btn_approve').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Approving...');
            $.ajax({
                url:    '/panel/transactions/{{ $transaction->id }}/approve',
                method: 'POST',
                data:   { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'Approved', timer: 1200, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                },
                error: function (xhr) {
                    const errs = xhr.responseJSON?.errors ?? {};
                    const msg  = Object.values(errs).flat().join('\n');
                    Swal.fire({ icon: 'error', title: 'Failed', text: msg || 'An unexpected error occurred.', confirmButtonText: 'OK' });
                    $btn.prop('disabled', false).text('Approve');
                },
            });
        });
    });

    // Finalize
    $('#btn_finalize').on('click', function () {
        Swal.fire({
            icon:               'warning',
            title:              'Finalize this order?',
            text:               'It cannot be edited afterwards.',
            showCancelButton:   true,
            confirmButtonText:  'Yes, Finalize',
            cancelButtonText:   'Cancel',
            confirmButtonColor: '#f1416c',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            const $btn = $('#btn_finalize').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Finalizing...');
            $.ajax({
                url:    '/panel/transactions/{{ $transaction->id }}/finalize',
                method: 'POST',
                data:   { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'Finalized', timer: 1200, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                },
                error: function (xhr) {
                    const errs = xhr.responseJSON?.errors ?? {};
                    const msg  = Object.values(errs).flat().join('\n');
                    Swal.fire({ icon: 'error', title: 'Failed', text: msg || 'An unexpected error occurred.', confirmButtonText: 'OK' });
                    $btn.prop('disabled', false).text('Finalize');
                },
            });
        });
    });

});
</script>
@endsection
