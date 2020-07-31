@extends('home')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group @error('name') has-error @enderror">
        <label for="name" class="form-label">{{ __('Name') }}</label>
        <input id="name" type="text" class="form-input" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="{{ __('Name') }}" autofocus>

        @error('name')
            <p class="form-input-hint" role="alert">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="form-group @error('email') has-error @enderror">
        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" class="form-input" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}">

        @error('email')
            <p class="form-input-hint" role="alert">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="form-group @error('password') has-error @enderror">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input id="password" type="password" class="form-input" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">

        @error('password')
            <p class="form-input-hint" role="alert">
                {{ $message }}
            </p>
        @enderror
    </div>
    
    <div class="form-group @error('password-confirm') has-error @enderror">
        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
        <input id="password-confirm" type="password" class="form-input" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </div>
    </div>
</form>
@endsection
