@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div>
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    {{ $branch->branch_name }}
                    <span class="badge badge-light ms-3 fs-7">{{ $branch->branch_code }}</span>
                </h1>
                <span class="text-muted fs-7">{{ \App\Models\Branch::TYPES[$branch->type] ?? $branch->type }}</span>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-light-warning" data-bs-toggle="modal" data-bs-target="#modal_edit_branch">
                    <i class="fa fa-edit me-1"></i> Edit Branch
                </button>
                <a href="{{ route('branches.index') }}" class="btn btn-sm btn-light">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="row g-5">

                {{-- Branch Info Card --}}
                <div class="col-lg-4">
                    <div class="card mb-5">
                        <div class="card-header"><h3 class="card-title fw-bold">Branch Info</h3></div>
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted fw-semibold">Status</span>
                                <span class="badge {{ $branch->status === 'active' ? 'badge-light-success' : 'badge-light-danger' }}">
                                    {{ ucfirst($branch->status) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted fw-semibold">Type</span>
                                <span class="fw-bold">{{ \App\Models\Branch::TYPES[$branch->type] ?? $branch->type }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted fw-semibold">Contact</span>
                                <span class="fw-bold">{{ $branch->contact_number ?? '—' }}</span>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted fw-semibold mb-1">Address</div>
                                <div class="fw-bold">{{ $branch->address }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted fw-semibold">Assigned Users</span>
                                <span class="badge badge-light-primary fs-7">{{ $branch->branch_users->count() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Add User Card --}}
                    <div class="card">
                        <div class="card-header"><h3 class="card-title fw-bold">Assign User</h3></div>
                        <div class="card-body">
                            <div id="add_user_errors" class="alert alert-danger d-none mb-3"></div>
                            <div class="mb-3">
                                <label class="form-label required fw-semibold">Select User</label>
                                <select id="assign_user_id" class="form-select form-select-solid">
                                    <option value="">— Select a user —</option>
                                    @foreach($available_users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($available_users->isEmpty())
                                <div class="text-muted fs-7">All users are already assigned to a branch.</div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary w-100" id="btn_assign_user"
                                {{ $available_users->isEmpty() ? 'disabled' : '' }}>
                                <span class="indicator-label"><i class="fa fa-user-plus me-1"></i>Assign User</span>
                                <span class="indicator-progress d-none">Assigning... <span class="spinner-border spinner-border-sm ms-1"></span></span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Assigned Users --}}
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title fw-bold">Assigned Users</h3>
                        </div>
                        <div class="card-body pt-0" id="users_table_container">
                            @if($branch->branch_users->isEmpty())
                                <div class="text-center text-muted py-8" id="no_users_msg">
                                    <i class="fa fa-users fs-2 d-block mb-2"></i>
                                    No users assigned to this branch yet.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-row-dashed align-middle gs-0 gy-3" id="users_table">
                                        <thead>
                                            <tr class="fw-bolder text-muted bg-light fs-7 text-uppercase">
                                                <th class="ps-4">Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Assigned</th>
                                                <th class="text-end pe-4">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="users_tbody">
                                            @foreach($branch->branch_users as $bu)
                                            <tr id="user_row_{{ $bu->user_id }}">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="symbol symbol-35px symbol-circle bg-light-primary d-flex align-items-center justify-content-center">
                                                            <span class="fw-bold text-primary fs-7">
                                                                {{ strtoupper(substr($bu->user?->name ?? '?', 0, 2)) }}
                                                            </span>
                                                        </div>
                                                        <span class="fw-semibold">{{ $bu->user?->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-muted fs-7">{{ $bu->user?->email }}</td>
                                                <td>
                                                    <span class="badge badge-light-info">
                                                        {{ $bu->user?->primary_role_name() ?? '—' }}
                                                    </span>
                                                </td>
                                                <td class="text-muted fs-7">{{ $bu->created_at->format('M d, Y') }}</td>
                                                <td class="text-end pe-4">
                                                    <button class="btn btn-sm btn-icon btn-light-danger btn-remove-user"
                                                        data-user_id="{{ $bu->user_id }}"
                                                        data-name="{{ $bu->user?->name }}"
                                                        title="Remove from branch">
                                                        <i class="fa fa-user-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Edit Branch Modal --}}
<div class="modal fade" id="modal_edit_branch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="edit_branch_errors" class="alert alert-danger d-none"></div>
                <form id="form_edit_branch">
                    @csrf
                    @include('branches._form', ['prefix' => 'edit_'])
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="btn_update_branch">
                    <span class="indicator-label"><i class="fa fa-save me-1"></i>Update</span>
                    <span class="indicator-progress d-none">Saving... <span class="spinner-border spinner-border-sm ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jslinks')
<script>
$(document).ready(function () {

    const BRANCH_ID = {{ $branch->id }};

    // Pre-fill edit modal with current values
    $('#edit_branch_name').val('{{ addslashes($branch->branch_name) }}');
    $('#edit_branch_code').val('{{ $branch->branch_code }}');
    $('#edit_type').val('{{ $branch->type }}');
    $('#edit_address').val('{{ addslashes($branch->address) }}');
    $('#edit_contact_number').val('{{ $branch->contact_number }}');
    $('#edit_status').val('{{ $branch->status }}');

    const EDIT_FIELD_MAP = {
        branch_name:    '#edit_branch_name',
        branch_code:    '#edit_branch_code',
        type:           '#edit_type',
        address:        '#edit_address',
        contact_number: '#edit_contact_number',
        status:         '#edit_status',
    };

    function set_btn_loading($btn, loading) {
        $btn.prop('disabled', loading);
        $btn.find('.indicator-label').toggleClass('d-none', loading);
        $btn.find('.indicator-progress').toggleClass('d-none', !loading);
    }

    // ─── Update Branch ────────────────────────────────────────────────────────
    $('#btn_update_branch').on('click', function () {
        const $btn = $(this);
        set_btn_loading($btn, true);
        $('#edit_branch_errors').addClass('d-none');
        Object.values(EDIT_FIELD_MAP).forEach(s => $(s).removeClass('is-invalid'));

        $.ajax({
            url:    '/panel/branches/' + BRANCH_ID,
            method: 'POST',
            data:   $('#form_edit_branch').serialize() + '&_method=PUT',
            success: function (res) {
                if (res.success) {
                    $('#modal_edit_branch').modal('hide');
                    location.reload();
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors ?? {};
                $('#edit_branch_errors').addClass('d-none').html('');
                const general = [];
                Object.keys(errors).forEach(function (k) {
                    const msgs = Array.isArray(errors[k]) ? errors[k] : [errors[k]];
                    if (EDIT_FIELD_MAP[k]) {
                        $(EDIT_FIELD_MAP[k]).addClass('is-invalid');
                        $(EDIT_FIELD_MAP[k]).closest('.mb-4').find('.invalid-feedback').text(msgs[0]);
                    } else {
                        general.push(...msgs);
                    }
                });
                if (general.length) $('#edit_branch_errors').html(general.join('<br>')).removeClass('d-none');
            },
            complete: function () { set_btn_loading($btn, false); }
        });
    });

    // ─── Assign User ──────────────────────────────────────────────────────────
    $('#btn_assign_user').on('click', function () {
        const $btn    = $(this);
        const user_id = $('#assign_user_id').val();
        if (!user_id) {
            alert('Please select a user.');
            return;
        }
        set_btn_loading($btn, true);
        $('#add_user_errors').addClass('d-none');

        $.ajax({
            url:    '/panel/branches/' + BRANCH_ID + '/users',
            method: 'POST',
            data:   { _token: '{{ csrf_token() }}', user_id: user_id },
            success: function (res) {
                if (res.success) {
                    const u = res.user;

                    // Append row to table
                    if ($('#users_table').length === 0) {
                        // Create table if not yet there
                        $('#no_users_msg').replaceWith(`
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-3" id="users_table">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light fs-7 text-uppercase">
                                            <th class="ps-4">Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Assigned</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users_tbody"></tbody>
                                </table>
                            </div>`);
                    }

                    const today = new Date().toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                    const initials = u.name.substring(0, 2).toUpperCase();
                    $('#users_tbody').append(`
                        <tr id="user_row_${u.id}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-35px symbol-circle bg-light-primary d-flex align-items-center justify-content-center">
                                        <span class="fw-bold text-primary fs-7">${initials}</span>
                                    </div>
                                    <span class="fw-semibold">${u.name}</span>
                                </div>
                            </td>
                            <td class="text-muted fs-7">${u.email}</td>
                            <td><span class="badge badge-light-info">${u.role ?? '—'}</span></td>
                            <td class="text-muted fs-7">${today}</td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-icon btn-light-danger btn-remove-user"
                                    data-user_id="${u.id}" data-name="${u.name}">
                                    <i class="fa fa-user-times"></i>
                                </button>
                            </td>
                        </tr>`);

                    // Remove the user from the dropdown
                    $('#assign_user_id option[value="' + u.id + '"]').remove();
                    $('#assign_user_id').val('');

                    // Show toast-style alert
                    show_success(res.message);
                }
            },
            error: function (xhr) {
                const msgs = Object.values(xhr.responseJSON?.errors ?? {}).flat().join('<br>');
                $('#add_user_errors').html(msgs).removeClass('d-none');
            },
            complete: function () { set_btn_loading($btn, false); }
        });
    });

    // ─── Remove User ──────────────────────────────────────────────────────────
    $(document).on('click', '.btn-remove-user', function () {
        const user_id = $(this).data('user_id');
        const name    = $(this).data('name');
        if (!confirm(`Remove "${name}" from this branch?`)) return;

        const $row = $(`#user_row_${user_id}`);
        const $btn = $(this).prop('disabled', true);

        $.ajax({
            url:    '/panel/branches/' + BRANCH_ID + '/users/remove',
            method: 'POST',
            data:   { _token: '{{ csrf_token() }}', user_id: user_id },
            success: function (res) {
                if (res.success) {
                    $row.fadeOut(300, function () { $(this).remove(); });

                    // Re-add to dropdown
                    const email = $row.find('td:nth-child(2)').text().trim();
                    $('#assign_user_id').append(
                        new Option(`${name} (${email})`, user_id)
                    );
                    $('#btn_assign_user').prop('disabled', false);

                    show_success(res.message);
                }
            },
            error: function (xhr) {
                const msgs = Object.values(xhr.responseJSON?.errors ?? {}).flat().join('\n');
                alert(msgs || 'Failed to remove user.');
            },
            complete: function () { $btn.prop('disabled', false); }
        });
    });

    function show_success(msg) {
        const $alert = $(`<div class="alert alert-success alert-dismissible fade show py-2 px-3 fs-7 mb-0 mt-3">${msg}<button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button></div>`);
        $('#users_table_container').prepend($alert);
        setTimeout(() => $alert.alert('close'), 3000);
    }

});
</script>
@endsection
