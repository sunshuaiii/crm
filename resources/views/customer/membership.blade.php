@extends('layouts.app')

@section('title', 'Membership')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            @if(Auth::user()->created_at->isToday())
            <h3 class="mt-4">Hi, {{ Auth::user()->username }}!</h3>
            <h4 class="mt-3">We're excited to have you join us today. Feel free to explore and get started!</h4>
            @else
            <h3 class="mt-4">Hi, {{ Auth::user()->username }}! Welcome back! </h3>
            @endif

            @if(!Auth::user()->username || !Auth::user()->dob || !Auth::user()->first_name || !Auth::user()->last_name || !Auth::user()->contact || !Auth::user()->gender)
            <h5 class="mt-3">Your profile is incomplete. Please complete your profile details to make the most of our services.</h5>
            @endif
        </div>

        <div class="col-md-5 mt-3">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/icon/points.png') }}" alt="Points Image" class="mr-3" style="width: 40px; height: 40px;">
                    <h4 class="m-2 text-center">You have <span class="highlight">{{ Auth::user()->points }}</span> points now!</h4>
                </div>
            </div>
        </div>

        <div class="col-md-8 mt-5">
            <div class="card">
                <div class="card-header">
                    Scan for Membership
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5>Your Customer ID: {{ Auth::user()->id }}</h5>
                    </div>
                    <div class="d-flex justify-content-around">
                        <div>
                            <h6>QR Code</h6>
                            {!! $qrCode !!}
                        </div>
                        <div>
                            <h6>Barcode</h6>
                            {!! $barCode !!}
                        </div>
                    </div>

                    @if(Auth::user()->created_at)
                    <div class="text-center mb-4">
                        <h6><i class="far fa-calendar-alt"></i> Member Since {{ Auth::user()->created_at->format('F Y') }}</h6>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="text-center m-4">
            <form id="redeemForm" action="{{ route('customer.checkout.membership') }}" method="POST">
                @csrf
                <button type="button" class="btn btn-primary" onclick="submit()">
                    <i class="fas fa-shopping-cart"></i> Checkout With Membership
                </button>
            </form>
        </div>

    </div>
</div>
@endsection