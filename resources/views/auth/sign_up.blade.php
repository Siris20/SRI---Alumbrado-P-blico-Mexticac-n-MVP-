@extends('layouts.app')

@section('title', 'Registro')

@push('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
     <link href="{{ asset('js/login.js') }}" rel="stylesheet">
@endpush

@section('content')
<div class="centrado">
    <div class="auth-container">
        <div class="auth-tabs">
            <a href="{{ route('register') }}" class="active">Sign up</a>
            <a href="{{ route('login') }}">Log in</a>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Full Name">
            </div>

            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email">
            </div>

            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" required placeholder="Password">
            </div>

            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password_confirmation" required placeholder="Confirm Password">
            </div>

            <button type="submit" class="btn-primary">Sign up</button>
        </form>
    </div>
</div>
@endsection