@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif
            <div class="text-center">
                <h3 class="mt-4">Join Our Customer Loyalty Program and Unlock Exclusive Rewards! </h3>
            </div>
        </div>

        <div class="col-md-5 mt-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="m-4">
                        <h4 class="mb-4">Sign in or create an account to start enjoying the incredible benefits of our Customer Loyalty Program.</h4>
                        <p class="highlight mb-4">Register now to receive <span class="highlight-text">RM10 off New Member Coupon</span> and <span class="highlight-text">300 bonus points</span> for free!</p>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('register.customer') }}" class="btn btn-primary btn-lg btn-block">Register Now</a>
                        <div class="text-center m-4">
                            <a href="{{ route('google.auth') }}" class="btn btn-outline-primary btn-google">
                                <i class="fab fa-google mr-2"></i> {{ __('Continue with Google') }}
                            </a>
                        </div>
                    </div>
                    <p class="mt-4">Already have an account?</p>
                    <div class="mb-2">
                        <a href="{{ route('login.customer') }}" class="btn btn-outline-primary btn-lg btn-block">Login</a>
                    </div>
                </div>
            </div>
            <div class="login-options mt-4">
                <p class="mb-2">Looking for other login options?</p>
                <div class="d-flex justify-content-center md-2">
                    <a href="{{ route('login.admin') }}" class="btn btn-primary login-btn">Admin Login</a>
                    <a href="{{ route('login.marketingStaff') }}" class="btn btn-primary login-btn">Marketing Staff Login</a>
                    <a href="{{ route('login.supportStaff') }}" class="btn btn-primary login-btn">Support Staff Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection