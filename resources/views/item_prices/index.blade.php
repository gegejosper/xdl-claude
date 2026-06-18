@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Item Prices</h1>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <div id="price_alert" class="d-none mb-4"></div>

            <div class="card">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold">Default Unit Prices per Item Type</h3>
                    <div class="card-toolbar">
                        <button type="button" id="btn_save_prices" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save Prices
                        </button>
                    </div>
                </div>
                <div class="card-body py-4">
                    <p class="text-muted fs-7 mb-5">
                        These prices auto-fill when creating a job order. You can still override them per order.
                    </p>
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 min-w-200px">Item Type</th>
                                    <th class="min-w-180px">Default Unit Price (₱)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($types as $key => $label)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $label }}</td>
                                    <td>
                                        <input type="number"
                                            step="0.01"
                                            min="0"
                                            name="prices[{{ $key }}]"
                                            class="form-control form-control-sm w-180px price-input"
                                            data-type="{{ $key }}"
                                            value="{{ number_format((float)($prices[$key] ?? 0), 2, '.', '') }}"
                                            placeholder="0.00">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('jslinks')
<script>
$(document).ready(function () {

    $('#btn_save_prices').on('click', function () {
        const $btn = $(this).prop('disabled', true).text('Saving...');
        $('#price_alert').addClass('d-none').removeClass('alert alert-success alert-danger');

        const prices = {};
        $('.price-input').each(function () {
            prices[$(this).data('type')] = $(this).val();
        });

        $.ajax({
            url:    '{{ route("item_prices.store") }}',
            method: 'POST',
            data:   { _token: '{{ csrf_token() }}', prices: prices },
            success: function (res) {
                if (res.success) {
                    $('#price_alert')
                        .addClass('alert alert-success')
                        .text(res.message)
                        .removeClass('d-none');
                }
            },
            error: function (xhr) {
                const errs = xhr.responseJSON?.errors ?? {};
                const msg  = Object.values(errs).flat().join(' ');
                $('#price_alert')
                    .addClass('alert alert-danger')
                    .text(msg || 'Failed to save prices.')
                    .removeClass('d-none');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Prices');
                setTimeout(() => $('#price_alert').addClass('d-none'), 4000);
            }
        });
    });

});
</script>
@endsection
