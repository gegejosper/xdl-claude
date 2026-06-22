@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ $page_name }}</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_expense">
                    <i class="fa fa-plus"></i> Add {{ $category === 'purchase' ? 'Purchase' : 'Expense' }}
                </button>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- Filters --}}
            <div class="card mb-5">
                <div class="card-body py-3">
                    <form method="GET" action="{{ request()->url() }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Date From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Date To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-sm btn-primary me-2">Filter</button>
                            <a href="{{ request()->url() }}" class="btn btn-sm btn-light">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 min-w-60px">#</th>
                                    <th class="min-w-120px">Date</th>
                                    <th class="min-w-120px">Type</th>
                                    <th class="min-w-200px">Description</th>
                                    <th class="min-w-120px">Amount</th>
                                    <th class="min-w-120px">Added By</th>
                                    <th class="min-w-100px text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $exp)
                                <tr>
                                    <td class="ps-4">{{ $exp->id }}</td>
                                    <td>{{ $exp->expense_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-light-primary">{{ $exp->type_label }}</span>
                                    </td>
                                    <td>{{ $exp->description ?? '—' }}</td>
                                    <td class="fw-bold">₱{{ number_format($exp->amount, 2) }}</td>
                                    <td>{{ $exp->added_by_user?->name ?? '—' }}</td>
                                    <td class="text-end pe-4">
                                        @php
                                            $can_edit = Auth::user()->hasRole(['admin', 'superadmin'])
                                                || ($exp->added_by === Auth::id() && $exp->expense_date->isToday());
                                        @endphp
                                        @if($can_edit)
                                        <button class="btn btn-sm btn-icon btn-light-primary btn-edit-expense"
                                            data-id="{{ $exp->id }}"
                                            data-type="{{ $exp->type }}"
                                            data-description="{{ $exp->description }}"
                                            data-amount="{{ $exp->amount }}"
                                            data-date="{{ $exp->expense_date->format('Y-m-d') }}"
                                            data-remarks="{{ $exp->remarks }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        @endif
                                        @can('manage-settings')
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete-expense"
                                            data-id="{{ $exp->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($expenses->count())
                            <tfoot>
                                <tr class="fw-bolder bg-light">
                                    <td colspan="4" class="ps-4 text-end">Total (this page):</td>
                                    <td class="text-danger">₱{{ number_format($expenses->sum('amount'), 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $expenses->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="modal_add_expense" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add {{ $category === 'purchase' ? 'Purchase' : 'Expense' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="add_expense_errors" class="alert alert-danger d-none"></div>
                <form id="form_add_expense">
                    @csrf
                    <input type="hidden" name="category" value="{{ $category }}">
                    <div class="mb-4">
                        <label class="form-label required fw-semibold">Type</label>
                        <select name="type" id="add_type" class="form-select" required>
                            <option value="">Select type</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Optional description">
                    </div>
                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="form-label required fw-semibold">Amount (₱)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label required fw-semibold">Date</label>
                            <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Optional remarks"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_save_expense">Save</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="modal_edit_expense" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="edit_expense_errors" class="alert alert-danger d-none"></div>
                <form id="form_edit_expense">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="edit_expense_id" id="edit_expense_id">
                    <div class="mb-4">
                        <label class="form-label required fw-semibold">Type</label>
                        <select name="type" id="edit_type" class="form-select" required>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description</label>
                        <input type="text" name="description" id="edit_description" class="form-control">
                    </div>
                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="form-label required fw-semibold">Amount (₱)</label>
                            <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label required fw-semibold">Date</label>
                            <input type="date" name="expense_date" id="edit_expense_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Remarks</label>
                        <textarea name="remarks" id="edit_remarks" class="form-control" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_update_expense">Update</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jslinks')
<script>
$(document).ready(function () {

    // Save
    $('#btn_save_expense').on('click', function () {
        const $btn = $(this).prop('disabled', true).text('Saving...');
        const data = $('#form_add_expense').serialize();
        $('#add_expense_errors').addClass('d-none');

        $.ajax({
            url: '{{ route("expenses.store") }}',
            method: 'POST',
            data: data,
            success: function (res) {
                if (res.success) {
                    $('#modal_add_expense').modal('hide');
                    location.reload();
                }
            },
            error: function (xhr) {
                const errs = xhr.responseJSON?.errors ?? {};
                const msg  = Object.values(errs).flat().join('<br>');
                $('#add_expense_errors').html(msg).removeClass('d-none');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Save');
            }
        });
    });

    // Open Edit
    $(document).on('click', '.btn-edit-expense', function () {
        const d = $(this).data();
        $('#edit_expense_id').val(d.id);
        $('#edit_type').val(d.type);
        $('#edit_description').val(d.description);
        $('#edit_amount').val(d.amount);
        $('#edit_expense_date').val(d.date);
        $('#edit_remarks').val(d.remarks);
        $('#edit_expense_errors').addClass('d-none');
        $('#modal_edit_expense').modal('show');
    });

    // Update
    $('#btn_update_expense').on('click', function () {
        const $btn  = $(this).prop('disabled', true).text('Saving...');
        const id    = $('#edit_expense_id').val();
        const data  = $('#form_edit_expense').serialize();
        $('#edit_expense_errors').addClass('d-none');

        $.ajax({
            url: '/panel/expenses/' + id,
            method: 'POST',
            data: data,
            success: function (res) {
                if (res.success) {
                    $('#modal_edit_expense').modal('hide');
                    location.reload();
                }
            },
            error: function (xhr) {
                const errs = xhr.responseJSON?.errors ?? {};
                const msg  = Object.values(errs).flat().join('<br>');
                $('#edit_expense_errors').html(msg).removeClass('d-none');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Update');
            }
        });
    });

    // Delete
    $(document).on('click', '.btn-delete-expense', function () {
        if (!confirm('Delete this record?')) return;
        const id   = $(this).data('id');
        const $row = $(this).closest('tr');

        $.ajax({
            url: '/panel/expenses/' + id,
            method: 'POST',
            data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) $row.remove();
            },
            error: function () {
                alert('Failed to delete.');
            }
        });
    });

});
</script>
@endsection
