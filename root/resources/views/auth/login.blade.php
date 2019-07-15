@extends('layouts.authentication')

@section('to-head')
    <style>
        .email {
        display: block;
        padding: 15px 10px;
        margin-bottom: 10px;
        width: 100%;
        border: 1px solid #ddd;
        transition: border-width 0.2s ease;
        border-radius: 2px;
        color: #ccc;
        }
        .email:focus {
        outline: none;
        color: #444;
        border-color: #2196F3;
        border-left-width: 35px;
        }
        .password {
        display: block;
        padding: 15px 10px;
        margin-bottom: 10px;
        width: 100%;
        border: 1px solid #ddd;
        transition: border-width 0.2s ease;
        border-radius: 2px;
        color: #ccc;
        }
        .password:focus {
        outline: none;
        color: #444;
        border-color: #2196F3;
        border-left-width: 35px;
        }
    </style>
@endsection

@section('to-body')
    <div style="padding: 10px;">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header" align="center">
                @include('extra.dev_msg3')
                <h2>HRIS</h2>
                {{-- @include('extra.dev_msg') --}}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" autocomplete="off">
                    @csrf
                    @if($errors->any())
                    <div class="alert alert-danger text-center" id="alert">
                        <label><b>{{$errors->first()}}</b></label>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="username"><strong>Username</strong></label>

                        <input class="form-control email" name="username" id="username" type="text" aria-describedby="emailHelp" placeholder="Enter Username" autofocus required>
                        @if ($errors->has('username'))
                        <div class="error-span" id="m_login_username">
                            {{ ucfirst(strtolower($errors->first('username'))) }}
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password"><strong>Password</strong></label>
                        <input class="form-control password"  name="password" id="password"type="password" placeholder="Password" required>
                        @if ($errors->has('password'))
                        <div class="error-span" id="m_password_username">
                            {{ ucfirst(strtolower($errors->first('password'))) }}
                        </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
                    {{-- <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a> --}}
                    {{-- <a href="{{route('home.index')}}"><button type="button" class="btn btn-primary btn-block">Login</button></a> --}}
                    {{-- <a class="btn btn-link" href="#">
                        {{ __('Forgot Your Password?') }}
                    </a> --}}
                </form>
            </div>
            <div class="card-footer" align="center">
                <a class="d-block small" href="{{url('/online-application/')}}" style="text-decoration:none;"><strong>Click Here to Apply Online</strong></a>
            </div>
        </div>
    </div>

    
@endsection
