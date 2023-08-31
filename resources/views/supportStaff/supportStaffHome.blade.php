@extends('layouts.dashboard')

@section('title', 'Support Staff Dashboard')

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
    <div class="header">Support Staff Dashboard</div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-4 mb-4">
            <a href="{{ route('supportStaff.ticketInsights') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-bar card-icon"></i> Ticket Insights</h5>
                        <p class="card-text">Explore insights about customer support ticket trends and analysis.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-4 mb-4">
            <a href="{{ route('supportStaff.searchCustomer') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-search card-icon"></i> Search Customer</h5>
                        <p class="card-text">Find and analyze customer information for better support.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection