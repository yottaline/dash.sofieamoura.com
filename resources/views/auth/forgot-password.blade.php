<x-guest-layout>
    <div class="container">
        <div class="card m-auto mt-5" style="max-width: 350px">
            <div class="card-body">
                <div class="mb-4 text-sm text-gray-600">
                    <b>Forgot your password? No problem</b><br>
                    <small>Enter your email address and we will email you a password reset link</small>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div>
                        <label for="email">Email</label>
                        <input id="email" class="form-control" type="email" name="email"
                            value="{{ old('email') }}" required autofocus>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="d-flex align-items-center mt-4">
                        <button type="submit" class="btn btn-outline-dark rounded-pill btn-sm mb-3 px-5 m-auto">Reset
                            password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-guest-layout>
