{{-- Add Customer Modal — included in transactions/create and edit --}}
<div class="modal fade" id="modal_add_customer_jo" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-user-plus me-2 text-primary"></i>Add New Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="jo_add_customer_errors" class="alert alert-danger d-none"></div>
                <form id="form_jo_add_customer">
                    @csrf
                    <div class="row g-4">
                        <div class="col-6">
                            <label class="form-label required fw-semibold">First Name</label>
                            <input type="text" name="first_name" id="jo_first_name"
                                class="form-control form-control-solid" placeholder="First Name">
                            <div class="invalid-feedback" id="jo_err_first_name"></div>
                        </div>
                        <div class="col-6">
                            <label class="form-label required fw-semibold">Last Name</label>
                            <input type="text" name="last_name" id="jo_last_name"
                                class="form-control form-control-solid" placeholder="Last Name">
                            <div class="invalid-feedback" id="jo_err_last_name"></div>
                        </div>
                        <div class="col-8">
                            <label class="form-label required fw-semibold">Address</label>
                            <input type="text" name="address" id="jo_address"
                                class="form-control form-control-solid" placeholder="Lot #, Block #, Street">
                            <div class="invalid-feedback" id="jo_err_address"></div>
                        </div>
                        <div class="col-4">
                            <label class="form-label required fw-semibold">Contact #</label>
                            <input type="text" name="mobile_num" id="jo_mobile_num"
                                class="form-control form-control-solid" placeholder="09XX-XXX-XXXX">
                            <div class="invalid-feedback" id="jo_err_mobile_num"></div>
                        </div>
                        <div class="col-4">
                            <label class="form-label required fw-semibold">Province</label>
                            <select name="province" id="jo_province" class="form-select form-select-solid">
                                <option value="">— Select Province —</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->prov_code }}"
                                        {{ $prov->prov_code === $default_province ? 'selected' : '' }}>
                                        {{ $prov->prov_desc }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="jo_err_province"></div>
                        </div>
                        <div class="col-4">
                            <label class="form-label required fw-semibold">City / Municipality</label>
                            <div id="jo_loading_city" class="spinner-border spinner-border-sm text-success d-none" role="status"></div>
                            <select name="city_municipality" id="jo_city_municipality" class="form-select form-select-solid">
                                <option value="">— Select City —</option>
                                @foreach($municipalities as $mun)
                                    <option value="{{ $mun->citymun_code }}"
                                        {{ $mun->citymun_code === $default_citymun ? 'selected' : '' }}>
                                        {{ $mun->citymun_desc }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="jo_err_city_municipality"></div>
                        </div>
                        <div class="col-4">
                            <label class="form-label required fw-semibold">Barangay</label>
                            <div id="jo_loading_brgy" class="spinner-border spinner-border-sm text-success d-none" role="status"></div>
                            <select name="barangay" id="jo_barangay" class="form-select form-select-solid">
                                <option value="">— Select Barangay —</option>
                                @foreach($barangays as $brgy)
                                    <option value="{{ $brgy->brgy_code }}">{{ $brgy->brgy_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="jo_err_barangay"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_jo_save_customer">
                    <span class="indicator-label"><i class="fa fa-save me-1"></i>Save Customer</span>
                    <span class="indicator-progress d-none">
                        Saving... <span class="spinner-border spinner-border-sm ms-1"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const JO_FIELD_MAP = {
        first_name:        '#jo_first_name',
        last_name:         '#jo_last_name',
        address:           '#jo_address',
        mobile_num:        '#jo_mobile_num',
        province:          '#jo_province',
        city_municipality: '#jo_city_municipality',
        barangay:          '#jo_barangay',
    };

    function jo_clear_errors() {
        $('#jo_add_customer_errors').addClass('d-none').html('');
        Object.values(JO_FIELD_MAP).forEach(function (sel) {
            $(sel).removeClass('is-invalid');
        });
        Object.keys(JO_FIELD_MAP).forEach(function (k) {
            $('#jo_err_' + k).text('');
        });
    }

    function jo_show_errors(errors) {
        const general = [];
        Object.keys(errors).forEach(function (key) {
            const msgs = Array.isArray(errors[key]) ? errors[key] : [errors[key]];
            if (JO_FIELD_MAP[key]) {
                $(JO_FIELD_MAP[key]).addClass('is-invalid');
                $('#jo_err_' + key).text(msgs[0]);
            } else {
                general.push(...msgs);
            }
        });
        if (general.length) {
            $('#jo_add_customer_errors').html(general.join('<br>')).removeClass('d-none');
        }
    }

    // Province → City
    $('#jo_province').on('change', function () {
        const prov = $(this).val();
        if (!prov) return;
        $('#jo_loading_city').removeClass('d-none');
        $.get('{{ route("search_town") }}', { search: prov }, function (html) {
            $('#jo_city_municipality').html('<option value="">— Select City —</option>' + html);
            $('#jo_barangay').html('<option value="">— Select Barangay —</option>');
            $('#jo_loading_city').addClass('d-none');
        });
    });

    // City → Barangay
    $('#jo_city_municipality').on('change', function () {
        const city = $(this).val();
        if (!city) return;
        $('#jo_loading_brgy').removeClass('d-none');
        $.get('{{ route("search_barangay") }}', { search: city }, function (html) {
            $('#jo_barangay').html('<option value="">— Select Barangay —</option>' + html);
            $('#jo_loading_brgy').addClass('d-none');
        });
    });

    // Reset form when modal closes
    $('#modal_add_customer_jo').on('hidden.bs.modal', function () {
        $('#form_jo_add_customer')[0].reset();
        jo_clear_errors();
    });

    // Save customer
    $('#btn_jo_save_customer').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true);
        $btn.find('.indicator-label').addClass('d-none');
        $btn.find('.indicator-progress').removeClass('d-none');
        jo_clear_errors();

        $.ajax({
            type:   'POST',
            url:    '{{ route("customers.add_customer") }}',
            data:   $('#form_jo_add_customer').serialize(),
            success: function (res) {
                if (res.success) {
                    const c = res.customer;
                    const label = c.last_name + ', ' + c.first_name + ' — ' + c.mobile_num;

                    // Inject the new customer into the Select2 and select it
                    const new_option = new Option(label, c.id, true, true);
                    $('#customer_id').append(new_option).trigger('change');

                    $('#modal_add_customer_jo').modal('hide');
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors ?? {};
                jo_show_errors(errors);
            },
            complete: function () {
                $btn.prop('disabled', false);
                $btn.find('.indicator-label').removeClass('d-none');
                $btn.find('.indicator-progress').addClass('d-none');
            },
        });
    });
})();
</script>
