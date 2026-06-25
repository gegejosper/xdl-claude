@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Daily Sales</h1>
            </div>
            @if(!Auth::user()->hasRole(['admin','superadmin']))
            <button type="button" class="btn btn-sm btn-primary" id="btn_close_today">
                <i class="fa fa-lock me-1"></i> Close Today's Sales
            </button>
            @endif
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- Today's stats card (staff only) --}}
            @if(!Auth::user()->hasRole(['admin','superadmin']))
            <div class="card mb-5" id="today_stats_card">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Today's Summary — {{ now()->format('F d, Y') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="border rounded p-4 text-center">
                                <div class="text-muted fs-7 mb-1">Total Orders</div>
                                <div class="fw-bolder fs-2 text-primary" id="stat_count">—</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-4 text-center">
                                <div class="text-muted fs-7 mb-1">Total Sales</div>
                                <div class="fw-bolder fs-2 text-success" id="stat_sales">—</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-4 text-center">
                                <div class="text-muted fs-7 mb-1">Payments Collected</div>
                                <div class="fw-bolder fs-2 text-info" id="stat_payments">—</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-4 text-center">
                                <div class="text-muted fs-7 mb-1">Status Breakdown</div>
                                <div class="fs-7 mt-1" id="stat_status_breakdown">—</div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment method breakdown --}}
                    <div id="stat_method_section" style="display:none">
                        <div class="fw-semibold fs-7 text-muted text-uppercase mb-2">Payment Method Breakdown</div>
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle mb-0" style="max-width:400px">
                                <tbody id="stat_method_rows"></tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td class="text-end text-primary" id="stat_method_total">—</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div id="already_closed_notice" class="alert alert-warning mt-4 d-none">
                        <i class="fa fa-info-circle me-1"></i> Today's sales have already been closed.
                    </div>
                </div>
            </div>
            @endif
            {{-- Payment History --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Payment History</h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-info fs-7">{{ $payments->total() }} record(s)</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle mb-0">
                            <thead>
                                <tr class="fw-bold text-muted fs-7 text-uppercase">
                                    <th class="ps-5">Date & Time</th>
                                    <th>Job Order</th>
                                    <th>Customer</th>
                                    <th>Method</th>
                                    <th class="text-end">Amount Paid</th>
                                    <th class="text-end pe-5">Change</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $method_badge = [
                                        'cash'          => 'badge-light-success',
                                        'gcash'         => 'badge-light-primary',
                                        'bank_transfer' => 'badge-light-info',
                                        'maya'          => 'badge-light-warning',
                                        'check'         => 'badge-light-secondary',
                                        'others'        => 'badge-light-dark',
                                    ];
                                    $payment_methods = \App\Models\TransactionPayment::PAYMENT_METHODS;
                                @endphp
                                @forelse($payments as $pay)
                                <tr>
                                    <td class="ps-5 text-muted fs-7">{{ $pay->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        @if($pay->transaction)
                                        <a href="{{ route('transactions.show', $pay->transaction_id) }}" class="fw-semibold text-primary">
                                            {{ $pay->transaction->transaction_number }}
                                        </a>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
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
                                    <td class="text-end pe-5 text-muted">₱{{ number_format($pay->change_amount ?? 0, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-6">No payments recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($payments->hasPages())
                <div class="card-footer">{{ $payments->links() }}</div>
                @endif
            </div>
            {{-- History table --}}
            <div class="card">
                <div class="card-header"><h3 class="card-title fw-bold">Sales History</h3></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle mb-0">
                            <thead>
                                <tr class="fw-bold text-muted fs-7 text-uppercase">
                                    <th class="ps-5">Date</th>
                                    @if($is_admin)<th>Branch</th>@endif
                                    <th>Orders</th>
                                    <th>Total Sales</th>
                                    <th>Payments</th>
                                    <th>Closed By</th>
                                    <th>Type</th>
                                    <th class="text-end pe-5">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $rec)
                                <tr>
                                    <td class="ps-5 fw-semibold">{{ $rec->sales_date->format('M d, Y') }}</td>
                                    @if($is_admin)<td>{{ $rec->branch->name ?? '—' }}</td>@endif
                                    <td>{{ $rec->transaction_count }}</td>
                                    <td class="text-success fw-bold">₱{{ number_format($rec->total_sales, 2) }}</td>
                                    <td class="text-info fw-bold">₱{{ number_format($rec->total_payments, 2) }}</td>
                                    <td>{{ $rec->closer?->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $rec->close_type === 'auto' ? 'badge-light-warning' : 'badge-light-primary' }}">
                                            {{ ucfirst($rec->close_type) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-5">
                                        <a href="{{ route('daily-sales.show', $rec->id) }}" class="btn btn-xs btn-light-primary">
                                            <i class="fa fa-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center text-muted py-6">No records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($records->hasPages())
                <div class="card-footer">{{ $records->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Close modal (staff) --}}
@if(!Auth::user()->hasRole(['admin','superadmin']))
<div class="modal fade" id="modal_close_sales" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Close Today's Sales — {{ now()->format('F d, Y') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Summary snapshot --}}
                <div class="bg-light rounded p-4 mb-4">
                    <div class="fw-bold fs-6 mb-3">Closing Summary</div>
                    <div class="row g-3 mb-3">
                        <div class="col-4 text-center">
                            <div class="text-muted fs-8">Total Orders</div>
                            <div class="fw-bolder fs-4 text-primary" id="modal_stat_count">—</div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="text-muted fs-8">Total Sales</div>
                            <div class="fw-bolder fs-4 text-success" id="modal_stat_sales">—</div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="text-muted fs-8">Total Collected</div>
                            <div class="fw-bolder fs-4 text-info" id="modal_stat_payments">—</div>
                        </div>
                    </div>
                    {{-- Status breakdown row --}}
                    <div class="d-flex gap-3 mb-3 fs-7" id="modal_status_row"></div>
                    {{-- Payment method breakdown --}}
                    <div class="separator separator-dashed my-3"></div>
                    <div class="fw-semibold fs-8 text-muted text-uppercase mb-2">Payment Breakdown</div>
                    <table class="table table-sm mb-0" id="modal_method_table">
                        <tbody id="modal_method_rows"></tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td>Total Collected</td>
                                <td class="text-end text-primary" id="modal_method_total">—</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-muted fs-7 mb-3">
                    <i class="fa fa-info-circle me-1"></i>
                    This will lock the day's summary. Transactions can still be created after closing.
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Notes (optional)</label>
                    <textarea id="close_notes" class="form-control" rows="2" placeholder="Any closing notes..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_confirm_close">
                    <i class="fa fa-lock me-1"></i> Confirm Close
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('jslinks')
<script>
$(document).ready(function () {

    @if(!Auth::user()->hasRole(['admin','superadmin']))
    let today_stats_cache = null;

    function render_method_rows(breakdown, tbody_id, total_id) {
        const $tbody = $('#' + tbody_id).empty();
        let total = 0;
        if (!breakdown || breakdown.length === 0) {
            $tbody.append('<tr><td colspan="2" class="text-muted fst-italic">No payments recorded yet.</td></tr>');
        } else {
            breakdown.forEach(function (item) {
                const amount = parseFloat(item.amount.replace(/,/g, ''));
                total += amount;
                $tbody.append(
                    '<tr><td>' + item.label + '</td><td class="text-end fw-semibold">₱' + item.amount + '</td></tr>'
                );
            });
        }
        $('#' + total_id).text('₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 }));
    }

    // Load today's stats
    $.get('{{ route("daily-sales.today-stats") }}', function (res) {
        today_stats_cache = res;

        // Stats card
        $('#stat_count').text(res.transaction_count);
        $('#stat_sales').text('₱' + res.total_sales);
        $('#stat_payments').text('₱' + res.total_payments);

        // Status breakdown chip
        const status_html =
            '<span class="badge badge-light-danger me-1">Unpaid: ' + res.unpaid_count + '</span>' +
            '<span class="badge badge-light-warning me-1">Partial: ' + res.partial_count + '</span>' +
            '<span class="badge badge-light-success">Paid: ' + res.paid_count + '</span>';
        $('#stat_status_breakdown').html(status_html);

        // Method breakdown table on stats card
        if (res.method_breakdown && res.method_breakdown.length > 0) {
            $('#stat_method_section').show();
            render_method_rows(res.method_breakdown, 'stat_method_rows', 'stat_method_total');
        }

        if (res.already_closed) {
            $('#already_closed_notice').removeClass('d-none');
            $('#btn_close_today').prop('disabled', true).text('Already Closed');
        }
    });

    $('#btn_close_today').on('click', function () {
        $('#close_notes').val('');

        // Populate modal summary from cached stats
        if (today_stats_cache) {
            const r = today_stats_cache;
            $('#modal_stat_count').text(r.transaction_count);
            $('#modal_stat_sales').text('₱' + r.total_sales);
            $('#modal_stat_payments').text('₱' + r.total_payments);

            const status_html =
                '<span class="badge badge-light-danger me-1">Unpaid: ' + r.unpaid_count + '</span>' +
                '<span class="badge badge-light-warning me-1">Partial: ' + r.partial_count + '</span>' +
                '<span class="badge badge-light-success">Paid: ' + r.paid_count + '</span>';
            $('#modal_status_row').html(status_html);

            render_method_rows(r.method_breakdown, 'modal_method_rows', 'modal_method_total');
        }

        $('#modal_close_sales').modal('show');
    });

    $('#btn_confirm_close').on('click', function () {
        const $btn = $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Closing...');

        $.ajax({
            url:    '{{ route("daily-sales.close") }}',
            method: 'POST',
            data:   { _token: '{{ csrf_token() }}', notes: $('#close_notes').val() },
            success: function (res) {
                if (res.success) {
                    $('#modal_close_sales').modal('hide');
                    Swal.fire({
                        icon:              'success',
                        title:             'Sales Closed',
                        text:              'Daily sales for today have been closed.',
                        timer:             1800,
                        showConfirmButton: false,
                    }).then(() => location.reload());
                }
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.errors?.general ?? 'Failed to close sales.';
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
                $btn.prop('disabled', false).html('Confirm Close');
            },
        });
    });
    @endif

});
</script>
@endsection
