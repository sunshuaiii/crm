@extends('layouts.dashboard')

@section('title', 'Report Generation')

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
    <div class="header">Report Generation</div>

    Hi there, awesome marketing staff for this site!
</div>
@endsection