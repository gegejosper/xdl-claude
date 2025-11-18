<section class="mb-5">
    <div class="card">
        <div class="card-body">
            <h2 class="h5 mb-2">{{ __('Update Password') }}</h2>
            <p class="text-muted mb-4">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                        autocomplete="current-password"
                        required
                    >
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                        autocomplete="new-password"
                        required
                    >
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                        autocomplete="new-password"
                        required
                    >
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save') }}
                    </button>

                    @if (session('status') === 'password-updated')
                        <span id="password_updated_message" class="text-success small">{{ __('Saved.') }}</span>
                        <script>
                            setTimeout(() => {
                                const msg = document.getElementById('password_updated_message');
                                if (msg) msg.style.display = 'none';
                            }, 2000);
                        </script>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>