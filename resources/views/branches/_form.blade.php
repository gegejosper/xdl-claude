@php $prefix = $prefix ?? ''; @endphp

<div class="row g-4">
    <div class="col-8">
        <div class="mb-4">
            <label class="form-label required fw-semibold">Branch Name</label>
            <input type="text" name="branch_name" id="{{ $prefix }}branch_name"
                class="form-control form-control-solid" placeholder="e.g. Main Branch">
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="col-4">
        <div class="mb-4">
            <label class="form-label required fw-semibold">Branch Code</label>
            <input type="text" name="branch_code" id="{{ $prefix }}branch_code"
                class="form-control form-control-solid text-uppercase" placeholder="e.g. MB01">
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="col-6">
        <div class="mb-4">
            <label class="form-label required fw-semibold">Type</label>
            <select name="type" id="{{ $prefix }}type" class="form-select form-select-solid">
                @foreach(\App\Models\Branch::TYPES as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="col-6">
        <div class="mb-4">
            <label class="form-label required fw-semibold">Status</label>
            <select name="status" id="{{ $prefix }}status" class="form-select form-select-solid">
                @foreach(\App\Models\Branch::STATUSES as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="col-12">
        <div class="mb-4">
            <label class="form-label required fw-semibold">Address</label>
            <input type="text" name="address" id="{{ $prefix }}address"
                class="form-control form-control-solid" placeholder="Full address">
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="col-6">
        <div class="mb-4">
            <label class="form-label fw-semibold">Contact Number</label>
            <input type="text" name="contact_number" id="{{ $prefix }}contact_number"
                class="form-control form-control-solid" placeholder="09XX-XXX-XXXX">
            <div class="invalid-feedback"></div>
        </div>
    </div>
</div>
