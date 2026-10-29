@extends('layouts.app')

@section('content')
<!-- Google reCAPTCHA Script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white text-center">
                <h4 class="mb-0">Login</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <!-- Google reCAPTCHA Box -->
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Login</button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}">Don't have an account? Register</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection