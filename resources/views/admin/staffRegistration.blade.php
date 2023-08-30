@extends('layouts.dashboard')

@section('title', 'Staff Registration')

@section('content')
<div class="container-content">
    <div class="header">Staff Registration</div>
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

    <div class="row justify-content-center mt-4">
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.register.marketingStaff') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bx bxs-user-plus card-icon"></i> Register Marketing Staff</h5>
                        <p class="card-text">Add new marketing staff to the system.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.register.supportStaff') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bx bxs-user-plus card-icon"></i> Register Support Staff</h5>
                        <p class="card-text">Add new support staff to the system.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.register.admin') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bx bxs-user-plus card-icon"></i> Register Admin</h5>
                        <p class="card-text">Add new admin users to the system.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection