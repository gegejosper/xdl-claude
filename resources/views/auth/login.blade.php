
@extends('layouts.auth')
@section('content')
    <div class="d-flex flex-column flex-root">
        <!--begin::Page bg image-->
        <style>body { background-image: url({{asset('assets/media/auth/bg4.jpg')}}); } [data-bs-theme="dark"] body { background-image: url('assets/media/auth/bg4-dark.jpg'); }</style>
        <!--end::Page bg image-->
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row">
            <!--begin::Aside-->
            <div class="d-flex flex-center w-lg-50 pt-5 pt-lg-0 px-10">
                <!--begin::Aside-->
                <div class="d-flex flex-center flex-lg-start flex-column">
                    <!--begin::Logo-->
                    <a href="/" class="mb-7 fs-1 text-white text-decoration-none">
                        [ {{config('app.name')}} ]
                    </a>
                    <!--end::Logo-->
                    <!--begin::Title-->
                    <h2 class="text-white fw-normal m-0">Providing Business Solutions from A-Z.</h2>
                    <!--end::Title-->
                </div>
                <!--begin::Aside-->
            </div>
            <!--begin::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
                <!--begin::Card-->
                <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-5 pb-lg-10">
                        <!--begin::Form-->
                        @auth
                        <div class="text-center">
                            <a href="{{auth()->user()->dashboard_route()}}" class="btn btn-lg btn-primary fw-bolder">Go to Dashboard</a>
                        </div>
                        @else
                        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="{{ url('/panel/dashboard') }}" method="POST" action="{{ route('login') }}">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-11">
                                <!--begin::Title-->
                                <h1 class="text-gray-900 fw-bolder mb-3">Back Panel Login</h1>
                                <!--end::Title-->
                               
                            </div>
                            <!--begin::Heading-->
                         
                            <!--begin::Input group=-->
                            <div class="fv-row mb-8">
                                <!--begin::Email-->
                                <label for="email" class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    placeholder="Email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="username" 
                                    class="form-control bg-transparent @error('email') is-invalid @enderror" 
                                />

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <!--end::Email-->
                            </div>
                            <div class="fv-row mb-3">
                                <!--begin::Password-->
                                <label for="password" class="form-label">Password</label>
                                <input 
                                    type="password" 
                                    placeholder="Password" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password" 
                                    class="form-control bg-transparent @error('password') is-invalid @enderror" 
                                />

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <!--end::Password-->
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                <!--begin::Remember Me-->
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="remember" 
                                        id="remember_me"
                                        {{ old('remember') ? 'checked' : '' }}
                                    />
                                    <label class="form-check-label" for="remember_me">
                                        Remember Me
                                    </label>
                                </div>
                                <!--end::Remember Me-->

                                <!--begin::Forgot Password-->
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="link-primary">
                                        Forgot Password?
                                    </a>
                                @endif
                                <!--end::Forgot Password-->
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Submit button-->
                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Sign In</span>
                                    <!--end::Indicator label-->
                                    <!--begin::Indicator progress-->
                                    <span class="indicator-progress">Please wait... 
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    <!--end::Indicator progress-->
                                </button>
                            </div>
                            <!--end::Submit button-->
                                
                        </form>
                        @endauth
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                    
                </div>
                <!--end::Card-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
@endsection
