@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@push('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <link href="{{ asset('js/login.js') }}" rel="stylesheet">
@endpush

@section('content')
<div class="centrado">
    <div class="auth-container">
        <div class="auth-tabs">
            <a href="{{ route('register') }}">Sign up</a>
            <a href="{{ route('login') }}" class="active">Log in</a>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
            </div>

            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" required autocomplete="current-password" placeholder="Password">
            </div>

            <button type="submit" class="btn-primary">Log in</button>
        </form>
    </div>
</div>
@endsection