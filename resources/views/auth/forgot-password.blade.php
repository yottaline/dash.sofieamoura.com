<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="d-flex h-100 w-100 justify-content-center py-5">
        <div id="cards-container">
            <div class="card mt-5">
                <div class="card-body">
                    <div class="mb-4 small text-secondary">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div>
                            <label for="email">Email</label>
                            <input id="email" class="form-control" type="email" name="email"
                                value="{{ old('email') }}" required autofocus>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="d-flex align-items-center mt-4">
                            <button type="submit" class="btn btn-outline-dark rounded-pill btn-sm mb-3 px-5">Reset
                                password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
