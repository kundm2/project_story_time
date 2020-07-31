@extends('home')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group @error('email') has-error @enderror">
        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" class="form-input " name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}" autofocus>

        @error('email')
            <p class="form-input-hint" role="alert">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="form-group @error('password') has-error @enderror"">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input id="password" type="password" class="form-input" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">

        @error('password')
            <p class="form-input-hint" role="alert">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-checkbox" for="remember">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <i class="form-icon"></i> {{ __('Remember Me') }}
        </label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            {{ __('Login') }}
        </button>

        @if (Route::has('password.request'))
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </div>
</form>
@endsection
