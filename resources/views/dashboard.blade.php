@extends('layouts.panel')
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
	<div class="toolbar" id="kt_toolbar">
		<!--begin::Container-->
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
		</div>
		<!--end::Container-->
	</div>
	<!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
           
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
@endsection
@section('jslinks')
<script src="{{asset('')}}"></script>
@endsection