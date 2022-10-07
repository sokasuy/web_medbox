@extends('layouts.forgotpassword')

@section('title')
    <title>{{ config('app.name', 'Laravel') }} | Forgot Password (v2)</title>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="{{ route('dashboard.home') }}" class="h1">{{ __('Admin') }}&nbsp;<b>{{ config('app.name', 'Laravel') }}</b></a>
    </div>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="input-group mb-3">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-1">
        <a href="{{ route('login') }}">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
@endsection
