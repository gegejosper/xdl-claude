<section class="mb-5">
    <div class="card">
        <div class="card-body">
            <h2 class="h5 mb-2">{{ __('Profile Information') }}</h2>
            <p class="text-muted mb-4">
                {{ __("Update your account's profile information and email address.") }}
            </p>

            <!-- Email Verification Resend Form -->
            <form id="send_verification" method="POST" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <!-- Profile Update Form -->
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}"
                        required
                        autofocus
                        autocomplete="name"
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="username"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-3">
                            <p class="small text-warning mb-1">
                                {{ __('Your email address is unverified.') }}
                            </p>
                            <button
                                form="send_verification"
                                class="btn btn-link p-0 align-baseline"
                            >
                                {{ __('Click here to re-send the verification email.') }}
                            </button>

                            @if (session('status') === 'verification-link-sent')
                                <p class="small text-success mt-2">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save') }}
                    </button>

                    @if (session('status') === 'profile-updated')
                        <span id="profile_updated_message" class="text-success small">{{ __('Saved.') }}</span>
                        <script>
                            setTimeout(() => {
                                const msg = document.getElementById('profile_updated_message');
                                if (msg) msg.style.display = 'none';
                            }, 2000);
                        </script>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>