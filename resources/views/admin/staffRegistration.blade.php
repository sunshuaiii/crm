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

    <div class="register-options">
        <a href="{{ route('admin.register.marketingStaff') }}" class="btn btn-primary">
            <i class="bx bxs-user-plus"></i> Register Marketing Staff
        </a>
        <a href="{{ route('admin.register.supportStaff') }}" class="btn btn-primary">
            <i class="bx bxs-user-plus"></i> Register Support Staff
        </a>
        <a href="{{ route('admin.register.admin') }}" class="btn btn-primary">
            <i class="bx bxs-user-plus"></i> Register Admin
        </a>
    </div>

</div>
@endsection