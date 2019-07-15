@extends('layouts.authentication')

@section('to-body')
    <div class="row mt-5" style="padding: 10px;">
        <div class="col">
            <div class="card card-login mx-auto mt-5">
                <div class="card-header" align="center">
                    <h2>HRIS</h2>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <div class="text-center mb-4">
                                <h4>Forgot your password?</h4>
                                <p>Enter your email address and we will send you instructions on how to reset your password.</p>
                            </div>
                            <input id="email" type="email" class="form-control email" name="email" required>
                            @if ($errors->has('email'))
                            <div class="error-span" id="m_email_username">
                                {{ ucfirst(strtolower($errors->first('email'))) }}
                            </div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </form>
                </div>
                <div class="card-footer" align="center">
                    <a class="d-block small" href="{{route('login')}}" style="text-decoration:none;">Click Here to Login</a>
                </div>
            </div>
        </div>
    </div>
@endsection
