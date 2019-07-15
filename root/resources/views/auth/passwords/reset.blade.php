@extends('layouts.authentication')

@section('to-body')
    <div class="row mt-5" style="padding: 10px;">
        <div class="col">
            <div class="card card-login mx-auto mt-5">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.request') }}" aria-label="{{ __('Reset Password') }}" autocomplete="off">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email">{{ __('E-Mail Address') }}</label>

                            <input id="email" type="email" class="form-control" name="email" required autofocus>

                            @if ($errors->has('email'))
                            <div class="error-span" id="m_email_username">
                                {{ ucfirst(strtolower($errors->first('email'))) }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>

                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                            <div class="error-span" id="m_password_username">
                                {{ ucfirst(strtolower($errors->first('password'))) }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password-confirmation">{{ __('Confirm') }}</label>

                            <input id="password-confirmation" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
