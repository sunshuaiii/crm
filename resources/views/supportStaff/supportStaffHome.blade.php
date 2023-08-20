@extends('layouts.dashboard')

@section('title', 'Support Staff Dashboard')

@section('content')
<div class="container-content">
    <div class="header">Support Staff Dashboard</div>
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

    Hi there, awesome support staff for this site!
</div>
@endsection