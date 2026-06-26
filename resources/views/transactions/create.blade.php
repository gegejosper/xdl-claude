@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">New Job Order</h1>
            </div>
            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-light">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- errors shown via Swal --}}

            <form id="form_job_order">
                @csrf
                <input type="hidden" name="submission_token" value="{{ $submission_token }}">
                <div class="row g-5">

                    {{-- Left: Order Info + Summary --}}
                    <div class="col-lg-4">
                        <div class="card mb-5">
                            <div class="card-header">
                                <h3 class="card-title fw-bold">Order Info</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label required fw-semibold">Customer</label>
                                    <div class="d-flex gap-2">
                                        <select name="customer_id" id="customer_id" class="form-select" required style="flex:1">
                                            <option value="">Search customer...</option>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}">{{ $c->last_name }}, {{ $c->first_name }} — {{ $c->mobile_num }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-light-primary btn-icon flex-shrink-0"
                                            data-bs-toggle="modal" data-bs-target="#modal_add_customer_jo"
                                            title="Add new customer">
                                            <i class="fa fa-user-plus"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-muted">
                                        Customer not listed?
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal_add_customer_jo">Add new customer</a>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Note / Instructions</label>
                                    <textarea name="note" class="form-control" rows="2" placeholder="Order notes"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Material</label>
                                    <input type="text" name="material" class="form-control" placeholder="e.g. Tela type">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Deadline</label>
                                    <input type="date" name="deadline" class="form-control">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">File / Layout Uploaded?</label>
                                    <div class="d-flex gap-4 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_file_upload" value="1" id="file_yes">
                                            <label class="form-check-label" for="file_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_file_upload" value="0" id="file_no" checked>
                                            <label class="form-check-label" for="file_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="2" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Summary --}}
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title fw-bold">Summary</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Sub-total</span>
                                    <span id="summary_subtotal" class="fw-bold">₱0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Discount</span>
                                    <span id="summary_discount" class="fw-bold text-danger">- ₱0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold fs-5">Total</span>
                                    <span id="summary_total" class="fw-bolder fs-5 text-primary">₱0.00</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btn_submit_order" class="btn btn-primary w-100">
                                    <i class="fa fa-save me-1"></i> Save Job Order
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Items --}}
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title fw-bold">Order Items</h3>
                                <button type="button" class="btn btn-sm btn-light-primary" id="btn_add_item">
                                    <i class="fa fa-plus me-1"></i> Add Item
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="items_container"></div>
                                <p id="no_items_msg" class="text-center text-muted py-4">No items added yet. Click "Add Item" to begin.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

{{-- ─── Item Row Template ──────────────────────────────────────────────────── --}}
<template id="item_row_template">
<div class="item-row card card-bordered mb-4" data-index="__IDX__">
    <div class="card-header min-h-50px">
        <h6 class="card-title fw-bold mb-0">
            Item #<span class="item-num">__NUM__</span>
        </h6>
        <div class="card-toolbar">
            <button type="button" class="btn btn-sm btn-light-danger btn-remove-item">
                <i class="fa fa-times me-1"></i> Remove
            </button>
        </div>
    </div>
    <div class="card-body py-4">

        {{-- Row 1: Type + Material + Price + Discount --}}
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label required fw-semibold fs-7">Item Type</label>
                <select name="items[__IDX__][item_type]" class="form-select form-select-sm item-type-select" required>
                    <option value="">— Select —</option>
                    @foreach(\App\Models\TransactionItem::ITEM_TYPES as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold fs-7">Material</label>
                <input type="text" name="items[__IDX__][material]" class="form-control form-control-sm" placeholder="e.g. Dry-fit">
            </div>
            <div class="col-md-3">
                <label class="form-label required fw-semibold fs-7">Unit Price (₱)</label>
                <input type="number" step="0.01" min="0" name="items[__IDX__][unit_price]"
                    class="form-control form-control-sm item-unit-price" value="0" required>
                <div class="form-text text-muted fs-8 item-price-hint d-none">Auto-filled from defaults</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold fs-7">Discount (₱)</label>
                <input type="number" step="0.01" min="0" name="items[__IDX__][discount]"
                    class="form-control form-control-sm item-discount" value="0">
            </div>
        </div>

        {{-- Tarpaulin Section --}}
        <div class="tarp-section d-none">
            <div class="row g-3 mb-3">
                <div class="col-md-2">
                    <label class="form-label fw-semibold fs-7">Width (ft)</label>
                    <input type="number" step="0.01" min="0" name="items[__IDX__][width]"
                        class="form-control form-control-sm item-width" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold fs-7">Height (ft)</label>
                    <input type="number" step="0.01" min="0" name="items[__IDX__][height]"
                        class="form-control form-control-sm item-height" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold fs-7">Sq Ft (auto)</label>
                    <input type="text" class="form-control form-control-sm item-sqft bg-light" readonly placeholder="0.00">
                </div>
                <div class="col-md-2">
                    <label class="form-label required fw-semibold fs-7">Qty (pcs)</label>
                    <input type="number" min="1" name="items[__IDX__][quantity]"
                        class="form-control form-control-sm item-qty" value="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold fs-7">Total Sq Ft</label>
                    <input type="text" class="form-control form-control-sm item-total-sqft bg-light" readonly placeholder="0.00">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold fs-7">Item Total</label>
                    <input type="text" class="form-control form-control-sm item-total-display bg-light fw-bold" readonly>
                </div>
            </div>
        </div>

        {{-- Sized Items Section (clothing with per-size qty) --}}
        <div class="sized-section d-none">
            <div class="border rounded p-3 bg-light-subtle mb-3">
                <div class="fw-semibold fs-7 mb-2 text-muted">Quantity per Size</div>
                <div class="row g-2">
                    @foreach(\App\Models\TransactionItem::SIZES as $size)
                    <div class="col-auto">
                        <label class="form-label fw-semibold fs-8 mb-1">{{ $size }}</label>
                        <input type="number" min="0"
                            name="items[__IDX__][sizes][{{ $size }}]"
                            class="form-control form-control-sm text-center size-qty-input"
                            data-size="{{ $size }}"
                            value="0"
                            style="width:64px">
                    </div>
                    @endforeach
                </div>
                <div class="d-flex gap-4 mt-3 fs-7">
                    <span class="text-muted">Total Qty: <strong class="sized-total-qty">0</strong></span>
                    <span class="text-muted">Item Total: <strong class="item-total-display text-primary">₱0.00</strong></span>
                </div>
            </div>
        </div>

        {{-- Non-sized Section (DTF, bags, others) --}}
        <div class="non-sized-section d-none">
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label required fw-semibold fs-7">Quantity</label>
                    <input type="number" min="1" name="items[__IDX__][quantity]"
                        class="form-control form-control-sm item-qty" value="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold fs-7">Item Total</label>
                    <input type="text" class="form-control form-control-sm item-total-display bg-light fw-bold" readonly>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold fs-7">Notes</label>
                <input type="text" name="items[__IDX__][notes]" class="form-control form-control-sm"
                    placeholder="Item notes (optional)">
            </div>
        </div>

    </div>
</div>
</template>

@endsection

@section('jslinks')
<script>
(function () {
    let item_index = 0;

    const SIZED_TYPES = @json(\App\Models\TransactionItem::SIZED_TYPES);
    const SQFT_TYPES  = @json(\App\Models\TransactionItem::SQFT_TYPES);
    const PRICE_URL   = '{{ url("/panel/item-prices") }}';

    // ─── Recalculate one item row ──────────────────────────────────────────────
    function recalculate_item($row) {
        const type  = $row.find('.item-type-select').val();
        const price = parseFloat($row.find('.item-unit-price').val()) || 0;
        const disc  = parseFloat($row.find('.item-discount').val())   || 0;
        let total   = 0;
        let qty     = 0;

        if (SQFT_TYPES.includes(type)) {
            const w         = parseFloat($row.find('.item-width').val())  || 0;
            const h         = parseFloat($row.find('.item-height').val()) || 0;
            const piece_qty = parseInt($row.find('.tarp-section .item-qty').val()) || 1;
            const sqft      = w * h;
            const total_sqft = sqft * piece_qty;
            $row.find('.item-sqft').val(sqft > 0 ? sqft.toFixed(2) + ' sq ft' : '');
            $row.find('.item-total-sqft').val(total_sqft > 0 ? total_sqft.toFixed(2) + ' sq ft' : '');
            qty   = piece_qty;
            total = Math.max(0, (total_sqft * price) - disc);
        } else if (SIZED_TYPES.includes(type)) {
            let sized_qty = 0;
            $row.find('.size-qty-input').each(function () {
                sized_qty += parseInt($(this).val()) || 0;
            });
            qty   = sized_qty;
            total = Math.max(0, (sized_qty * price) - disc);
            $row.find('.sized-total-qty').text(sized_qty);
        } else {
            qty   = parseInt($row.find('.non-sized-section .item-qty').val()) || 0;
            total = Math.max(0, (qty * price) - disc);
        }

        $row.find('.item-total-display').val('₱' + total.toFixed(2)).text('₱' + total.toFixed(2));
        $row.data('item_total',    total);
        $row.data('item_discount', disc);
        recalculate_summary();
    }

    // ─── Recalculate order summary ────────────────────────────────────────────
    function recalculate_summary() {
        let subtotal = 0, discount = 0;
        $('.item-row').each(function () {
            subtotal += $(this).data('item_total')    || 0;
            discount += $(this).data('item_discount') || 0;
        });
        const gross = subtotal + discount;
        $('#summary_subtotal').text('₱' + gross.toFixed(2));
        $('#summary_discount').text('- ₱' + discount.toFixed(2));
        $('#summary_total').text('₱' + subtotal.toFixed(2));
    }

    // ─── Show/hide sections based on item type ────────────────────────────────
    function apply_type_sections($row, type) {
        $row.find('.tarp-section').addClass('d-none');
        $row.find('.sized-section').addClass('d-none');
        $row.find('.non-sized-section').addClass('d-none');

        if (SQFT_TYPES.includes(type)) {
            $row.find('.tarp-section').removeClass('d-none');
        } else if (SIZED_TYPES.includes(type)) {
            $row.find('.sized-section').removeClass('d-none');
        } else if (type !== '') {
            $row.find('.non-sized-section').removeClass('d-none');
        }
    }

    // ─── Fetch default price for item type ────────────────────────────────────
    function fetch_default_price($row, type) {
        if (!type) return;
        $.get(PRICE_URL + '/' + type, function (res) {
            if (res.unit_price > 0) {
                $row.find('.item-unit-price').val(res.unit_price);
                $row.find('.item-price-hint').removeClass('d-none');
                recalculate_item($row);
            }
        });
    }

    // ─── Add item row ──────────────────────────────────────────────────────────
    function add_item(data) {
        const idx      = item_index++;
        const template = document.getElementById('item_row_template').innerHTML;
        const html     = template.replace(/__IDX__/g, idx).replace(/__NUM__/g, item_index);
        const $row     = $(html);
        $row.data({ item_total: 0, item_discount: 0 });

        $('#items_container').append($row);
        $('#no_items_msg').hide();

        if (data) {
            $row.find('.item-type-select').val(data.item_type);
            $row.find('[name$="[material]"]').val(data.material || '');
            $row.find('.item-unit-price').val(data.unit_price);
            $row.find('.item-discount').val(data.discount || 0);
            $row.find('[name$="[notes]"]').val(data.notes || '');

            apply_type_sections($row, data.item_type);

            if (SQFT_TYPES.includes(data.item_type)) {
                $row.find('.item-width').val(data.width || '');
                $row.find('.item-height').val(data.height || '');
            }
        }

        recalculate_item($row);
    }

    // ─── Init ──────────────────────────────────────────────────────────────────
    $(document).ready(function () {

        add_item();

        $('#btn_add_item').on('click', () => add_item());

        $(document).on('click', '.btn-remove-item', function () {
            if ($('.item-row').length <= 1) {
                alert('At least one item is required.');
                return;
            }
            $(this).closest('.item-row').remove();
            if ($('.item-row').length === 0) $('#no_items_msg').show();
            recalculate_summary();
        });

        // Type change → show sections + fetch default price
        $(document).on('change', '.item-type-select', function () {
            const $row = $(this).closest('.item-row');
            const type = $(this).val();
            apply_type_sections($row, type);
            $row.find('.item-price-hint').addClass('d-none');
            fetch_default_price($row, type);
            recalculate_item($row);
        });

        // Recalculate on any numeric input
        $(document).on('input',
            '.item-unit-price, .item-qty, .item-discount, .item-width, .item-height, .size-qty-input',
            function () {
                recalculate_item($(this).closest('.item-row'));
            }
        );

        // Submit
        $('#btn_submit_order').on('click', function () {
            const $btn = $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

            $.ajax({
                url:    '{{ route("transactions.store") }}',
                method: 'POST',
                data:   $('#form_job_order').serializeArray(),
                success: function (res) {
                    if (res.success && res.redirect) {
                        Swal.fire({
                            icon:              'success',
                            title:             'Job Order Saved',
                            text:              res.message,
                            timer:             1500,
                            showConfirmButton: false,
                        }).then(() => { window.location.href = res.redirect; });
                    }
                },
                error: function (xhr) {
                    const errs = xhr.responseJSON?.errors ?? {};
                    const msg  = Object.values(errs).flat().join('\n');
                    Swal.fire({
                        icon:             'error',
                        title:            'Failed to Save',
                        text:             msg || 'An unexpected error occurred.',
                        confirmButtonText:'OK',
                    });
                    $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Job Order');
                },
            });
        });

    });
})();
</script>

{{-- Select2 --}}
<script>
$(document).ready(function () {
    $('#customer_id').select2({
        placeholder:    'Search customer name or number...',
        allowClear:     true,
        width:          '100%',
        dropdownParent: $('body'),
    });
});
</script>

@include('transactions._add_customer_modal')
@endsection
