<x-guest-layout>
    <h2>Welcome Back</h2>
    <p>Sign in to your ProcureFlow account</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@company.com">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password" placeholder="••••••••">
            @error('password')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin:0.75rem 0">
            <div class="form-check">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Sign In to ProcureFlow
        </button>
    </form>
</x-guest-layout>
