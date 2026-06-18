// ─── Helpers ──────────────────────────────────────────────────────────────────

/**
 * Extract a flat array of error messages from a Laravel validation errors object.
 * @param {Object} errors  e.g. { first_name: ["required"], address: ["required"] }
 * @returns {string[]}
 */
function flatten_errors(errors) {
    if (!errors || typeof errors !== 'object') return ['An unexpected error occurred.'];
    return Object.values(errors).flat();
}

/**
 * Show an alert-danger block inside a container element.
 * @param {string} selector  CSS selector of the container
 * @param {string[]} messages
 */
function show_error_alert(selector, messages) {
    const html = messages.map(m => `<div>${m}</div>`).join('');
    $(selector).html('<div class="alert alert-danger mb-0">' + html + '</div>').removeClass('d-none').show();
}

function clear_error_alert(selector) {
    $(selector).addClass('d-none').html('');
}

/**
 * Highlight individual fields and set inline messages.
 * @param {Object} errors   { field: [messages] }
 * @param {Object} field_map  { field_name: '#input_id' }
 */
function highlight_fields(errors, field_map) {
    // Clear previous
    Object.values(field_map).forEach(function (sel) {
        $(sel).removeClass('is-invalid');
        const feedback = sel.replace('#', '#err_');
        $(feedback).text('');
    });

    Object.keys(errors).forEach(function (key) {
        if (field_map[key]) {
            $(field_map[key]).addClass('is-invalid');
            const msgs = Array.isArray(errors[key]) ? errors[key] : [errors[key]];
            const feedback = field_map[key].replace('#', '#err_');
            $(feedback).text(msgs[0]);
        }
    });
}

function clear_field_highlights(field_map) {
    Object.values(field_map).forEach(function (sel) {
        $(sel).removeClass('is-invalid');
        const feedback = sel.replace('#', '#err_');
        $(feedback).text('');
    });
}

// ─── Search / Build Table ─────────────────────────────────────────────────────

const customerSearchKey = document.getElementById('search_customers');
const customersList     = document.querySelector('.customers-list');

function buildCustomer(customers) {
    if (!customers || customers.length === 0) {
        customersList.innerHTML = "<tr><td colspan='7' class='text-center text-muted py-4'><em>No customers found.</em></td></tr>";
        return;
    }

    customersList.innerHTML = customers.map(function (customer) {
        const badge_class = customer.status === 'active' ? 'badge-light-success' : 'badge-light-danger';

        let block_btn = '';
        if (customer.status === 'active') {
            block_btn = `<a href="javascript:;" class="btn btn-icon btn-sm btn-active-light-warning block-customer"
                data-customer_id="${customer.id}" data-customer_status="blocked" title="Block">
                <i class="fa fa-ban"></i></a>`;
        } else if (customer.status === 'blocked') {
            block_btn = `<a href="javascript:;" class="btn btn-icon btn-sm btn-active-light-success block-customer"
                data-customer_id="${customer.id}" data-customer_status="active" title="Unblock">
                <i class="fa fa-check-circle"></i></a>`;
        }

        return `<tr>
            <td><a href="/panel/customers/${customer.id}" class="text-gray-800 text-hover-primary fw-semibold">
                ${customer.last_name}, ${customer.first_name}</a></td>
            <td>${customer.mobile_num}</td>
            <td>${customer.branch_details ? customer.branch_details.branch_name : '—'}</td>
            <td>${customer.address || ''}${customer.brgy ? ', ' + customer.brgy : ''}${customer.city_num ? ', ' + customer.city_num : ''}</td>
            <td>0.00</td>
            <td><span id="customer_status_${customer.id}" class="badge ${badge_class}">${customer.status}</span></td>
            <td class="text-end">
                <a href="javascript:;" class="btn btn-icon btn-sm btn-active-light-info edit-customer"
                    data-customer_id="${customer.id}"
                    data-customer_first_name="${customer.first_name}"
                    data-customer_last_name="${customer.last_name}"
                    data-customer_mobile_num="${customer.mobile_num}"
                    data-customer_address="${customer.address || ''}"
                    title="Edit"><i class="fa fa-edit"></i></a>
                ${block_btn}
                <a href="/panel/customers/${customer.id}" class="btn btn-icon btn-sm btn-active-light-primary" title="View">
                    <i class="fa fa-eye"></i></a>
            </td>
        </tr>`;
    }).join('');
}

function searchCustomers() {
    const term = $('input[name=search_customers]').val().trim();
    if (term.length < 1) return;

    $.ajax({
        type:   'POST',
        url:    '/panel/customers/search',
        data: {
            '_token':       $('input[name=_token]').val(),
            'search_query': term,
        },
        success: function (customers) {
            buildCustomer(customers);
        },
        error: function (xhr) {
            const msgs = flatten_errors(xhr.responseJSON?.errors);
            customersList.innerHTML = `<tr><td colspan="7"><div class="alert alert-danger">${msgs.join('<br>')}</div></td></tr>`;
        },
    });
}

if (customerSearchKey) {
    let search_timer;
    customerSearchKey.addEventListener('keyup', function () {
        clearTimeout(search_timer);
        search_timer = setTimeout(searchCustomers, 350); // debounce
    });
}

// ─── DOM Ready ────────────────────────────────────────────────────────────────

$(document).ready(function () {

    const ADD_FIELD_MAP = {
        first_name:         '#first_name',
        last_name:          '#last_name',
        address:            '#address',
        mobile_num:         '#mobile_num',
        province:           '#province',
        city_municipality:  '#city_municipality',
        barangay:           '#barangay',
    };

    const EDIT_FIELD_MAP = {
        first_name:  '#edit_first_name',
        last_name:   '#edit_last_name',
        address:     '#edit_address',
        mobile_num:  '#edit_mobile_num',
    };

    // ─── Open Edit Modal ───────────────────────────────────────────────────

    $(document).on('click', '.edit-customer', function () {
        const d = $(this).data();
        $('#edit_first_name').val(d.customer_first_name);
        $('#edit_last_name').val(d.customer_last_name);
        $('#edit_address').val(d.customer_address);
        $('#edit_mobile_num').val(d.customer_mobile_num);
        $('#edit_customer_id').val(d.customer_id);
        // Reset address dropdowns — handled inside customers.blade.php inline script
        $('#edit_province').val('');
        if ($('#edit_city_municipality').length) {
            $('#edit_city_municipality').html('<option value="">— Select after province —</option>');
            $('#edit_barangay').html('<option value="">— Select after city —</option>');
        }
        clear_error_alert('#add_customer_errors');
        clear_error_alert('#edit_customer_errors');
        clear_field_highlights(EDIT_FIELD_MAP);
        $('#modal_edit_customer').modal('show');
    });

    // ─── Add Customer ──────────────────────────────────────────────────────

    $('#addcustomer').on('click', function () {
        const $btn = $(this).prop('disabled', true);
        clear_error_alert('#add_customer_errors');
        clear_field_highlights(ADD_FIELD_MAP);

        $.ajax({
            type:   'POST',
            url:    '/panel/customers/add',
            data: {
                '_token':           $('input[name=_token]').val(),
                'first_name':       $('input[name=first_name]').val(),
                'last_name':        $('input[name=last_name]').val(),
                'address':          $('input[name=address]').val(),
                'barangay':         $('select[name=barangay]').val(),
                'province':         $('select[name=province]').val(),
                'mobile_num':       $('input[name=mobile_num]').val(),
                'city_municipality':$('select[name=city_municipality]').val(),
                'branch':           $('select[name=branch_id]').val() || 1,
            },
            success: function (res) {
                if (res.success) {
                    window.location.href = '/panel/customers/' + res.customer.id;
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors ?? {};
                highlight_fields(errors, ADD_FIELD_MAP);
                const general = flatten_errors(errors).filter(function (m) {
                    // Show messages not covered by field highlights
                    return true;
                });
                if (general.length) {
                    show_error_alert('#add_customer_errors', general);
                }
            },
            complete: function () {
                $btn.prop('disabled', false);
            },
        });
    });

    // ─── Update Customer (list page form) ─────────────────────────────────

    $(document).on('submit', '#update_customer_form', function (e) {
        e.preventDefault();
        const $btn = $('#updatecustomer').prop('disabled', true);
        clear_error_alert('#edit_customer_errors');
        clear_field_highlights(EDIT_FIELD_MAP);

        $.ajax({
            type: 'POST',
            url:  '/panel/customers/edit',
            data: {
                '_token':           $('input[name=_token]').val(),
                'customer_id':      $('input[name=edit_customer_id]').val(),
                'first_name':       $('input[name=edit_first_name]').val(),
                'last_name':        $('input[name=edit_last_name]').val(),
                'address':          $('input[name=edit_address]').val(),
                'mobile_num':       $('input[name=edit_mobile_num]').val(),
                'province':         $('select[name=edit_province]').val(),
                'city_municipality':$('select[name=edit_city_municipality]').val(),
                'barangay':         $('select[name=edit_barangay]').val(),
            },
            success: function (res) {
                if (res.success) {
                    $('#modal_edit_customer').modal('hide');
                    location.reload();
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors ?? {};
                highlight_fields(errors, EDIT_FIELD_MAP);
                show_error_alert('#edit_customer_errors', flatten_errors(errors));
            },
            complete: function () {
                $btn.prop('disabled', false);
            },
        });
    });

    // ─── Block / Unblock Customer ──────────────────────────────────────────

    $(document).on('click', '.block-customer', function () {
        const customer_id     = $(this).data('customer_id');
        const new_status      = $(this).data('customer_status');
        const action_label    = new_status === 'blocked' ? 'block' : 'unblock';

        if (!confirm('Are you sure you want to ' + action_label + ' this customer?')) return;

        $.ajax({
            type: 'POST',
            url:  '/panel/customers/modify',
            data: {
                '_token':          $('input[name=_token]').val(),
                'customer_id':     customer_id,
                'customer_status': new_status,
            },
            success: function (res) {
                if (res.success) {
                    // Update badge in-place
                    const $badge   = $('#customer_status_' + customer_id);
                    const is_active = res.status === 'active';
                    $badge.text(res.status)
                          .removeClass('badge-light-success badge-light-danger badge-light-warning')
                          .addClass(is_active ? 'badge-light-success' : 'badge-light-danger');

                    // Swap button data so next click toggles correctly
                    const $btn = $('[data-customer_id="' + customer_id + '"].block-customer');
                    $btn.data('customer_status', is_active ? 'blocked' : 'active');
                }
            },
            error: function (xhr) {
                const msgs = flatten_errors(xhr.responseJSON?.errors);
                alert('Error: ' + msgs.join('\n'));
            },
        });
    });

});
