@extends('layouts.panel')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
	<div class="toolbar" id="kt_toolbar">
		<!--begin::Container-->
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
		</div>
		<!--end::Container-->
	</div>
	<!--end::Toolbar-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <!--begin::Card header-->
				<div class="card-header border-0 pt-6">
					<!--begin::Card title-->
					<div class="card-title">
						 <h2>Edit Permission</h2>
					</div>
					<!--begin::Card title-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body py-4">
                   @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('permissions.update', $permission) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Permission Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $permission->name) }}">
                    </div>
                    <button class="btn btn-success">Update</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection