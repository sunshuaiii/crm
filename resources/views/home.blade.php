@extends('layouts.app')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif
            <h3 class="mt-4">Welcome to our Customer Loyalty Program! </h3>
        </div>

        <div class="col-md-5 mt-5">
            <div class="card">
                <div class="card-header text-center"> Customer Loyalty Program for Retail Store  </div>
                <div class="card-body justify-content-center align-items-center">
                    <div class="text-center m-4">
                        <h5>We are excited to have you join us. Sign in or create an account to start enjoying the benefits.</h5>
                        <h5 class="highlight">Register now to get a RM10 off New Member Coupon and 300 points for free!</h5>
                    </div>
                    <div class="text-center mb-4">
                        <a href="{{ route('login.customer') }}" class="btn btn-primary">
                            {{ __('Login') }}
                        </a>
                    </div>
                    <div class="text-center mb-4">
                        <a href="{{ route('register.customer') }}" class="btn btn-primary">
                            {{ __('Register') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


