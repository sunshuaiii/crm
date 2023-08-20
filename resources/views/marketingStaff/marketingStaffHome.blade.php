@extends('layouts.dashboard')

@section('title', 'Marketing Staff Dashboard')

@section('content')
<div class="container-content">
    <div class="header">Marketing Staff Dashboard</div>
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

    Hi there, awesome marketing staff for this site!
</div>
@endsection