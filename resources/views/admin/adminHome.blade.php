@extends('layouts.adminApp')

@section('title', 'Admin Home')

@section('content')
<div class="container-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
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
            </div>
        </div>
    </div>
</div>
@endsection