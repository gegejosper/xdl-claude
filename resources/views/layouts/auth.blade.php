<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	@include('layouts.meta')
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="auth-bg bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<script src="https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js"></script>
		@yield('content')
		<!--end::Root-->
		<!--end::Main-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		// <script src="{{asset('assets/js/custom/authentication/sign-in/general.js')}}"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>