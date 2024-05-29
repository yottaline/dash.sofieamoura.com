<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="d-flex h-100">
        <div id="cards-container" class="bg-muted-8 flex-grow-1 flex-md-grow-0">
            <div class="mt-5 text-center">
                <div class="logo"></div>
                <h6 class="m-0">Sofieamoura Dashboard</h6>
            </div>

            <div id="login-card" class="card border-0 mt-5 bg-muted-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-body">
                    <form id="login-form" id="loginFrom" action="#" method="post" role="form">
                        @csrf
                        <div class="mb-3 position-relative">
                            <label for="login-email">Email<b class="text-danger">&ast;</b></label>
                            <input id="login-email" name="user_email" type="email" value="{{ old('email') }}"
                                class="form-control" required>
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="login-password">Password<b class="text-danger">&ast;</b></label>
                            <input id="login-password" name="password" type="password" class="form-control" required>
                        </div>

                        @if (Route::has('password.request'))
                            <small class="d-block my-3"><i class="bi bi-lock text-muted"></i> <a
                                    href="{{ route('password.request') }}" target="_self">Forgot your
                                    password?</a></small>
                        @endif

                        <input type="hidden" name="token" value="0">
                        <input type="hidden" name="action" value="login_form">
                        <div class="text-center">
                            <button type="submit" class="btn btn-outline-dark rounded-pill mb-3 px-5">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
