@extends('layouts.dashboard')

@section('title', 'Search Customer')

@section('content')
<div class="container-content">
    <div class="header">Search Customer</div>
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

    You must be the priviledged administrator of this site!
</div>
@endsection