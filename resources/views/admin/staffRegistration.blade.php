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

    You must be the priviledged administrator of this site!
</div>
@endsection