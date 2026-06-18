@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Branches</h1>
            </div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_create_branch">
                <i class="fa fa-plus me-1"></i> Add Branch
            </button>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- Search / Filter --}}
            <div class="card mb-5">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('branches.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search branch name or code..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                @foreach(\App\Models\Branch::STATUSES as $k => $v)
                                    <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('branches.index') }}" class="btn btn-sm btn-light w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light fs-7 text-uppercase">
                                    <th class="ps-4 min-w-150px">Branch Name</th>
                                    <th class="min-w-80px">Code</th>
                                    <th class="min-w-80px">Type</th>
                                    <th class="min-w-200px">Address</th>
                                    <th class="min-w-120px">Contact</th>
                                    <th class="min-w-60px text-center">Users</th>
                                    <th class="min-w-80px">Status</th>
                                    <th class="text-end pe-4 min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $branch)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('branches.show', $branch->id) }}" class="fw-bold text-gray-800 text-hover-primary">
                                            {{ $branch->branch_name }}
                                        </a>
                                    </td>
                                    <td><span class="badge badge-light fw-bold">{{ $branch->branch_code }}</span></td>
                                    <td class="text-muted">{{ \App\Models\Branch::TYPES[$branch->type] ?? $branch->type }}</td>
                                    <td class="text-muted fs-7">{{ $branch->address }}</td>
                                    <td class="text-muted fs-7">{{ $branch->contact_number ?? '—' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-light-primary">{{ $branch->branch_users_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $branch->status === 'active' ? 'badge-light-success' : 'badge-light-danger' }}">
                                            {{ ucfirst($branch->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('branches.show', $branch->id) }}"
                                            class="btn btn-sm btn-icon btn-light-primary" title="View / Manage Users">
                                            <i class="fa fa-users"></i>
                                        </a>
                                        <button class="btn btn-sm btn-icon btn-light-warning btn-edit-branch"
                                            data-id="{{ $branch->id }}"
                                            data-branch_name="{{ $branch->branch_name }}"
                                            data-branch_code="{{ $branch->branch_code }}"
                                            data-type="{{ $branch->type }}"
                                            data-address="{{ $branch->address }}"
                                            data-contact_number="{{ $branch->contact_number }}"
                                            data-status="{{ $branch->status }}"
                                            title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete-branch"
                                            data-id="{{ $branch->id }}"
                                            data-name="{{ $branch->branch_name }}"
                                            title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-8">
                                        <i class="fa fa-building fs-2 d-block mb-2"></i>
                                        No branches found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $branches->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Create Branch Modal --}}
<div class="modal fade" id="modal_create_branch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="create_branch_errors" class="alert alert-danger d-none"></div>
                <form id="form_create_branch">
                    @csrf
                    @include('branches._form')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_save_branch">
                    <span class="indicator-label"><i class="fa fa-save me-1"></i>Save Branch</span>
                    <span class="indicator-progress d-none">Saving... <span class="spinner-border spinner-border-sm ms-1"></span></span>
                </button>
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
                    <input type="hidden" id="edit_branch_id">
                    @include('branches._form', ['prefix' => 'edit_'])
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="btn_update_branch">
                    <span class="indicator-label"><i class="fa fa-save me-1"></i>Update Branch</span>
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

    const FIELD_MAP = {
        branch_name:    '#branch_name',
        branch_code:    '#branch_code',
        type:           '#type',
        address:        '#address',
        contact_number: '#contact_number',
        status:         '#status',
    };
    const EDIT_FIELD_MAP = {
        branch_name:    '#edit_branch_name',
        branch_code:    '#edit_branch_code',
        type:           '#edit_type',
        address:        '#edit_address',
        contact_number: '#edit_contact_number',
        status:         '#edit_status',
    };

    function show_errors(container, errors, field_map) {
        $(container).addClass('d-none').html('');
        Object.values(field_map).forEach(s => $(s).removeClass('is-invalid'));

        const general = [];
        Object.keys(errors).forEach(function (k) {
            const msgs = Array.isArray(errors[k]) ? errors[k] : [errors[k]];
            if (field_map[k]) {
                $(field_map[k]).addClass('is-invalid');
                $(field_map[k]).closest('.mb-4').find('.invalid-feedback').text(msgs[0]);
            } else {
                general.push(...msgs);
            }
        });
        if (general.length) {
            $(container).html(general.join('<br>')).removeClass('d-none');
        }
    }

    function set_btn_loading($btn, loading) {
        $btn.prop('disabled', loading);
        $btn.find('.indicator-label').toggleClass('d-none', loading);
        $btn.find('.indicator-progress').toggleClass('d-none', !loading);
    }

    // ─── Create ───────────────────────────────────────────────────────────────
    $('#btn_save_branch').on('click', function () {
        const $btn = $(this);
        set_btn_loading($btn, true);
        $('#create_branch_errors').addClass('d-none');
        Object.values(FIELD_MAP).forEach(s => $(s).removeClass('is-invalid'));

        $.ajax({
            url:    '{{ route("branches.store") }}',
            method: 'POST',
            data:   $('#form_create_branch').serialize(),
            success: function (res) {
                if (res.success) {
                    $('#modal_create_branch').modal('hide');
                    location.reload();
                }
            },
            error: function (xhr) {
                show_errors('#create_branch_errors', xhr.responseJSON?.errors ?? {}, FIELD_MAP);
            },
            complete: function () { set_btn_loading($btn, false); }
        });
    });

    // ─── Open Edit ────────────────────────────────────────────────────────────
    $(document).on('click', '.btn-edit-branch', function () {
        const d = $(this).data();
        $('#edit_branch_id').val(d.id);
        $('#edit_branch_name').val(d.branch_name);
        $('#edit_branch_code').val(d.branch_code);
        $('#edit_type').val(d.type);
        $('#edit_address').val(d.address);
        $('#edit_contact_number').val(d.contact_number);
        $('#edit_status').val(d.status);
        $('#edit_branch_errors').addClass('d-none');
        Object.values(EDIT_FIELD_MAP).forEach(s => $(s).removeClass('is-invalid'));
        $('#modal_edit_branch').modal('show');
    });

    // ─── Update ───────────────────────────────────────────────────────────────
    $('#btn_update_branch').on('click', function () {
        const $btn = $(this);
        const id   = $('#edit_branch_id').val();
        set_btn_loading($btn, true);

        $.ajax({
            url:    '/panel/branches/' + id,
            method: 'POST',
            data:   $('#form_edit_branch').serialize() + '&_method=PUT',
            success: function (res) {
                if (res.success) {
                    $('#modal_edit_branch').modal('hide');
                    location.reload();
                }
            },
            error: function (xhr) {
                show_errors('#edit_branch_errors', xhr.responseJSON?.errors ?? {}, EDIT_FIELD_MAP);
            },
            complete: function () { set_btn_loading($btn, false); }
        });
    });

    // ─── Delete ───────────────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete-branch', function () {
        const id   = $(this).data('id');
        const name = $(this).data('name');
        if (!confirm(`Delete branch "${name}"? This cannot be undone.`)) return;

        $.ajax({
            url:    '/panel/branches/' + id,
            method: 'POST',
            data:   { _method: 'DELETE', _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) location.reload();
            },
            error: function (xhr) {
                const msg = Object.values(xhr.responseJSON?.errors ?? {}).flat().join('\n');
                alert(msg || 'Failed to delete branch.');
            }
        });
    });

    // Reset create form on modal close
    $('#modal_create_branch').on('hidden.bs.modal', function () {
        $('#form_create_branch')[0].reset();
        $('#create_branch_errors').addClass('d-none');
        Object.values(FIELD_MAP).forEach(s => $(s).removeClass('is-invalid'));
    });
});
</script>
@endsection
