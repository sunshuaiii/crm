@extends('layouts.dashboard')

@section('title', 'Marketing Staff Dashboard')

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
    <div class="header">Marketing Staff Dashboard</div>

    <div class="col-md-12 mb-4">
        <h4 class="sub-header">Customer Insights</h4>
        <div class="card">
            <div class="card-body">

            </div>
        </div>
    </div>
</div>
@endsection