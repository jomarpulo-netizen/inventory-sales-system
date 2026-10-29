@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white text-center">
                <h4 class="mb-0">OTP Verification</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-info">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <p class="text-muted text-center">Ilagay ang 6-digit OTP code na ipinakita sa itaas para makapasok.</p>

                <form action="{{ route('otp.verify') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">6-Digit OTP Code</label>
                        <input type="text" name="otp" class="form-control text-center fs-4 tracking-widest" maxlength="6" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Verify & Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection