@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-content">
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
    <div class="header">Admin Dashboard</div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.couponInsights') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-ticket-alt card-icon"></i> Coupon Insights</h5>
                        <p class="card-text">Explore insights about coupon usage and trends.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.staffInsights') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users-cog card-icon"></i> Staff Insights</h5>
                        <p class="card-text">View insights about staff performance and engagement.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection