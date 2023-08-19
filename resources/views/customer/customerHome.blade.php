@extends('layouts.app')

@section('title', 'Customer Home')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
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

        <div class="col-md-5 mt-4">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/icon/points.png') }}" alt="Points Image" class="mr-3" style="width: 40px; height: 40px;">
                    <h4 class="m-2 text-center">You have <span class="highlight">{{ Auth::user()->points }}</span> points now!</h4>
                </div>
            </div>
        </div>

        <div class="col-md-8 mt-4">
            <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/banner/1.jpg') }}" class="d-block w-100" alt="Banner 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/banner/2.jpg') }}" class="d-block w-100" alt="Banner 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/banner/3.png') }}" class="d-block w-100" alt="Banner 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

    </div>
</div>
@endsection