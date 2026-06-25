@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    {{ $customer->last_name }}, {{ $customer->first_name }}
                </h1>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-light-primary edit-customer"
                    data-customer_id="{{ $customer->id }}"
                    data-customer_first_name="{{ $customer->first_name }}"
                    data-customer_last_name="{{ $customer->last_name }}"
                    data-customer_mobile_num="{{ $customer->mobile_num }}"
                    data-customer_address="{{ $customer->address }}">
                    <i class="fa fa-edit me-1"></i> Edit
                </button>
               
                @if(!$today_closed)
                <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> New Job Order
                </a>
                @else
                <span class="btn btn-sm btn-secondary disabled">
                    <i class="fa fa-lock me-1"></i> Sales Closed
                </span>
                @endif
                <a href="{{ route('customers.show_customers') }}" class="btn btn-sm btn-light">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="d-flex flex-column flex-xl-row gap-5">

                {{-- Sidebar: Customer Info + Stats --}}
                <div class="flex-column flex-lg-row-auto w-100 w-xl-300px">

                    {{-- Customer Card --}}
                    <div class="card mb-5">
                        <div class="card-body pt-8 pb-5">
                            <div class="text-center mb-5">
                                <div class="symbol symbol-80px symbol-circle mx-auto mb-4 bg-light-primary d-flex align-items-center justify-content-center">
                                    <span class="fs-1 fw-bolder text-primary">
                                        {{ strtoupper(substr($customer->first_name, 0, 1)) }}{{ strtoupper(substr($customer->last_name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="fw-bolder fs-4">{{ $customer->last_name }}, {{ $customer->first_name }}</div>
                                <div class="mt-1">
                                    @if($customer->status === 'active')
                                        <span class="badge badge-light-success">Active</span>
                                    @else
                                        <span class="badge badge-light-danger">{{ ucfirst($customer->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-4"></div>
                            <div class="fs-6">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fw-semibold">Contact</span>
                                    <span class="fw-bold">{{ $customer->mobile_num }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fw-semibold">Address</span>
                                    <span class="fw-bold text-end" style="max-width:160px">
                                        {{ $customer->address }}, {{ $customer->brgy }},
                                        {{ $customer->city_num }}, {{ $customer->province }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted fw-semibold">Since</span>
                                    <span class="fw-bold">{{ $customer->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="card mb-5">
                        <div class="card-header"><h3 class="card-title fw-bold">Summary</h3></div>
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total Orders</span>
                                <span class="badge badge-light-primary fs-7">{{ $total_orders }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total Amount</span>
                                <span class="fw-bold">₱{{ number_format($total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total Paid</span>
                                <span class="fw-bold text-success">₱{{ number_format($total_paid, 2) }}</span>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Outstanding Balance</span>
                                <span class="fw-bolder {{ $total_balance > 0 ? 'text-danger' : 'text-success' }}">
                                    ₱{{ number_format($total_balance, 2) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Unpaid Orders</span>
                                <span class="badge badge-light-danger">{{ $unpaid_count }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Main Content: Tabs --}}
                <div class="flex-lg-row-fluid">

                    {{-- Tabs --}}
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-5 fw-bold mb-6">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-3 active" data-bs-toggle="tab" href="#tab_transactions">
                                Job Orders
                                <span class="badge badge-light-primary ms-2">{{ $total_orders }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-3" data-bs-toggle="tab" href="#tab_payments">
                                Payment History
                                <span class="badge badge-light-success ms-2">{{ $transaction_payments->count() }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- Tab: Transactions --}}
                        <div class="tab-pane fade active show" id="tab_transactions">
                            <div class="card">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title fw-bold">Job Order History</h3>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bolder text-muted bg-light fs-7 text-uppercase">
                                                    <th class="ps-4">Date</th>
                                                    <th>Job Order #</th>
                                                    <th>OR #</th>
                                                    <th>Amount</th>
                                                    <th>Balance</th>
                                                    <th>Payment</th>
                                                    <th>Claim</th>
                                                    <th class="text-end pe-4">Actions</th>
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
                                                        <br><span class="text-muted fs-8">
                                                            <i class="fa fa-clock-o me-1"></i>{{ $txn->deadline->format('M d, Y') }}
                                                        </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted fs-7">{{ $txn->or_number }}</td>
                                                    <td class="fw-bold">₱{{ number_format($txn->total_amount, 2) }}</td>
                                                    <td class="{{ $txn->balance > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                                        ₱{{ number_format($txn->balance, 2) }}
                                                    </td>
                                                    <td>
                                                        @php $pc = match($txn->payment_status) {
                                                            'paid'     => 'badge-light-success',
                                                            'partial'  => 'badge-light-warning',
                                                            'canceled' => 'badge-light-dark',
                                                            default    => 'badge-light-danger',
                                                        }; @endphp
                                                        <span class="badge {{ $pc }}">{{ $txn->payment_status_label }}</span>
                                                    </td>
                                                    <td>
                                                        @php $cc = match($txn->claim_status) {
                                                            'ready'   => 'badge-light-warning',
                                                            'claimed' => 'badge-light-success',
                                                            default   => 'badge-light-info',
                                                        }; @endphp
                                                        <span class="badge {{ $cc }}">{{ $txn->claim_status_label }}</span>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <a href="{{ route('transactions.show', $txn->id) }}"
                                                            class="btn btn-sm btn-icon btn-light-primary" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        @if(!in_array($txn->payment_status, ['paid', 'canceled']))
                                                        <button class="btn btn-sm btn-icon btn-light-success btn-inline-payment"
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
                                                    <td colspan="8" class="text-center text-muted py-6">No job orders found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                            @if($transactions->count())
                                            <tfoot>
                                                <tr class="fw-bold bg-light">
                                                    <td colspan="3" class="ps-4 text-end">Totals:</td>
                                                    <td>₱{{ number_format($total_amount, 2) }}</td>
                                                    <td class="{{ $total_balance > 0 ? 'text-danger' : 'text-success' }}">
                                                        ₱{{ number_format($total_balance, 2) }}
                                                    </td>
                                                    <td colspan="3"></td>
                                                </tr>
                                            </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab: Payment History --}}
                        <div class="tab-pane fade" id="tab_payments">
                            <div class="card">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title fw-bold">Payment History</h3>
                                    <div class="card-toolbar">
                                        <span class="fw-bold text-success">
                                            Total Paid: ₱{{ number_format($total_paid, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bolder text-muted bg-light fs-7 text-uppercase">
                                                    <th class="ps-4">Date</th>
                                                    <th>Job Order #</th>
                                                    <th>Amount Paid</th>
                                                    <th>Change</th>
                                                    <th>Status</th>
                                                    <th class="text-end pe-4">Receipt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($transaction_payments as $pay)
                                                <tr>
                                                    <td class="ps-4">
                                                        <span class="d-block fw-semibold">{{ $pay->created_at->format('M d, Y') }}</span>
                                                        <span class="text-muted fs-7">{{ $pay->created_at->format('h:i A') }}</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('transactions.show', $pay->transaction_id) }}" class="fw-bold text-primary">
                                                            {{ $pay->transaction?->transaction_number ?? '—' }}
                                                        </a>
                                                    </td>
                                                    <td class="fw-bold text-success">
                                                        ₱{{ number_format($pay->amount_paid, 2) }}
                                                    </td>
                                                    <td class="text-muted">
                                                        ₱{{ number_format($pay->change_amount, 2) }}
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-success">{{ ucfirst($pay->status) }}</span>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <a href="{{ route('transactions.payment.receipt', $pay->id) }}"
                                                            class="btn btn-sm btn-icon btn-light-primary" target="_blank" title="Print Receipt">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-6">No payments recorded.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- end tab-content --}}
                </div>{{-- end main --}}
            </div>
        </div>
    </div>
</div>

{{-- Inline Payment Modal --}}
<div class="modal fade" id="modal_inline_payment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Receive Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="inline_payment_errors" class="alert alert-danger d-none"></div>
                <p class="mb-1">Job Order: <strong id="inline_job_order"></strong></p>
                <p class="mb-4">Balance: <strong class="text-danger" id="inline_balance_display"></strong></p>
                <input type="hidden" id="inline_txn_id">
                <div class="mb-3">
                    <label class="form-label required fw-semibold">Payment Method</label>
                    <select id="inline_payment_method" class="form-select">
                        @foreach(\App\Models\TransactionPayment::PAYMENT_METHODS as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label required fw-semibold">Amount Received (₱)</label>
                    <input type="number" step="0.01" id="inline_amount_paid" class="form-control" placeholder="0.00">
                </div>
                <div id="inline_change_row" class="mb-3" style="display:none">
                    <label class="form-label fw-semibold">Change</label>
                    <input type="text" id="inline_change" class="form-control" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btn_inline_confirm_payment">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Customer Modal --}}
<div class="modal fade" id="modal_edit_customer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="edit_customer_errors" class="alert alert-danger d-none"></div>
                <form id="form_edit_customer">
                    @csrf
                    <input type="hidden" id="edit_customer_id" name="customer_id">
                    <div class="row g-4">
                        <div class="col-6">
                            <label class="form-label required fw-semibold">First Name</label>
                            <input type="text" name="first_name" id="edit_first_name" class="form-control form-control-solid" placeholder="First Name">
                            <div class="invalid-feedback" id="err_first_name"></div>
                        </div>
                        <div class="col-6">
                            <label class="form-label required fw-semibold">Last Name</label>
                            <input type="text" name="last_name" id="edit_last_name" class="form-control form-control-solid" placeholder="Last Name">
                            <div class="invalid-feedback" id="err_last_name"></div>
                        </div>
                        <div class="col-8">
                            <label class="form-label required fw-semibold">Address</label>
                            <input type="text" name="address" id="edit_address" class="form-control form-control-solid" placeholder="Lot #, Block #, Street">
                            <div class="invalid-feedback" id="err_address"></div>
                        </div>
                        <div class="col-4">
                            <label class="form-label required fw-semibold">Contact #</label>
                            <input type="text" name="mobile_num" id="edit_mobile_num" class="form-control form-control-solid" placeholder="09XX-XXX-XXXX">
                            <div class="invalid-feedback" id="err_mobile_num"></div>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Province</label>
                            <select name="province" id="edit_province" class="form-select form-select-solid">
                                <option value="">— Keep current —</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->prov_code }}">{{ $prov->prov_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">City / Municipality</label>
                            <select name="city_municipality" id="edit_city_municipality" class="form-select form-select-solid">
                                <option value="">— Select after province —</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Barangay</label>
                            <select name="barangay" id="edit_barangay" class="form-select form-select-solid">
                                <option value="">— Select after city —</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_save_edit_customer">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress d-none">Please wait <span class="spinner-border spinner-border-sm ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jslinks')
<script>
$(document).ready(function () {

    // ─── Inline Payment ────────────────────────────────────────────────────
    let inline_balance = 0;

    $(document).on('click', '.btn-inline-payment', function () {
        const d = $(this).data();
        inline_balance = parseFloat(d.balance);
        $('#inline_txn_id').val(d.id);
        $('#inline_job_order').text(d.joborder);
        $('#inline_balance_display').text('₱' + inline_balance.toFixed(2));
        $('#inline_amount_paid').val('');
        $('#inline_payment_method').val('cash');
        $('#inline_change_row').hide();
        $('#inline_payment_errors').addClass('d-none');
        $('#modal_inline_payment').modal('show');
    });

    $('#inline_amount_paid').on('input', function () {
        const paid   = parseFloat($(this).val()) || 0;
        const change = paid - inline_balance;
        if (paid > 0) {
            $('#inline_change').val(change >= 0 ? '₱' + change.toFixed(2) : '—');
            $('#inline_change_row').show();
        } else {
            $('#inline_change_row').hide();
        }
    });

    $('#btn_inline_confirm_payment').on('click', function () {
        const $btn = $(this).prop('disabled', true).text('Saving...');
        const id   = $('#inline_txn_id').val();
        $('#inline_payment_errors').addClass('d-none');

        $.ajax({
            url:    '/panel/transactions/' + id + '/payment',
            method: 'POST',
            data:   { _token: '{{ csrf_token() }}', amount_paid: $('#inline_amount_paid').val(), payment_method: $('#inline_payment_method').val() },
            success: function (res) {
                if (res.success) {
                    $('#modal_inline_payment').modal('hide');
                    window.location.href = res.receipt_url;
                }
            },
            error: function (xhr) {
                const errs = xhr.responseJSON?.errors ?? {};
                const msg  = Object.values(errs).flat().join('<br>');
                $('#inline_payment_errors').html(msg).removeClass('d-none');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Confirm Payment');
            }
        });
    });

    // ─── Edit Customer ─────────────────────────────────────────────────────
    $(document).on('click', '.edit-customer', function () {
        const d = $(this).data();
        $('#edit_customer_id').val(d.customer_id);
        $('#edit_first_name').val(d.customer_first_name);
        $('#edit_last_name').val(d.customer_last_name);
        $('#edit_address').val(d.customer_address);
        $('#edit_mobile_num').val(d.customer_mobile_num);
        $('#edit_province').val('');
        $('#edit_city_municipality').html('<option value="">— Select after province —</option>');
        $('#edit_barangay').html('<option value="">— Select after city —</option>');
        clear_edit_errors();
        $('#modal_edit_customer').modal('show');
    });

    // Province → City
    $('#edit_province').on('change', function () {
        const prov = $(this).val();
        if (!prov) return;
        $.get('{{ route("search_town") }}', { search: prov }, function (html) {
            $('#edit_city_municipality').html('<option value="">— Select city —</option>' + html);
            $('#edit_barangay').html('<option value="">— Select after city —</option>');
        });
    });

    // City → Barangay
    $('#edit_city_municipality').on('change', function () {
        const city = $(this).val();
        if (!city) return;
        $.get('{{ route("search_barangay") }}', { search: city }, function (html) {
            $('#edit_barangay').html('<option value="">— Select barangay —</option>' + html);
        });
    });

    $('#btn_save_edit_customer').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true);
        $btn.find('.indicator-label').addClass('d-none');
        $btn.find('.indicator-progress').removeClass('d-none');
        clear_edit_errors();

        $.ajax({
            url:    '{{ route("customers.edit_customer") }}',
            method: 'POST',
            data:   $('#form_edit_customer').serialize(),
            success: function (res) {
                if (res.success) {
                    $('#modal_edit_customer').modal('hide');
                    location.reload();
                }
            },
            error: function (xhr) {
                const errs = xhr.responseJSON?.errors ?? {};
                show_edit_errors(errs);
            },
            complete: function () {
                $btn.prop('disabled', false);
                $btn.find('.indicator-label').removeClass('d-none');
                $btn.find('.indicator-progress').addClass('d-none');
            }
        });
    });

    function clear_edit_errors() {
        $('#edit_customer_errors').addClass('d-none').html('');
        $('#form_edit_customer .form-control, #form_edit_customer .form-select').removeClass('is-invalid');
        $('#form_edit_customer .invalid-feedback').text('');
    }

    function show_edit_errors(errors) {
        const field_map = {
            first_name:        '#edit_first_name',
            last_name:         '#edit_last_name',
            address:           '#edit_address',
            mobile_num:        '#edit_mobile_num',
        };
        let general = [];

        Object.keys(errors).forEach(function (key) {
            const msgs = Array.isArray(errors[key]) ? errors[key] : [errors[key]];
            if (field_map[key]) {
                $(field_map[key]).addClass('is-invalid');
                $('#err_' + key).text(msgs[0]);
            } else {
                general.push(...msgs);
            }
        });

        if (general.length) {
            $('#edit_customer_errors').html(general.join('<br>')).removeClass('d-none');
        }
    }

});
</script>
@endsection
