@extends('layouts.dashboard')

@section('title', 'Lead Management')

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
    <div class="header">Lead Management</div>

    Hi there, awesome marketing staff for this site!
</div>
@endsection