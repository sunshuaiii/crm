@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Forgot Your Password?') }}</div>
                <div class="card-body">
                    <p>
                        {{ __('If you have forgotten your password, please contact our customer service for assistance.') }}
                    </p>

                    @if(Auth::user())
                    <p>
                        {{ __('You can reach us via our') }}
                        <a href="{{ route('customer.support.contactUs') }}">{{ __('Contact Us') }}</a>
                        {{ __('page.') }}
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-5 mt-5">
            <div class="card">
                <div class="card-header text-center">
                    <i class="far fa-address-book"></i> Contacts
                </div>
                <div class="card-body justify-content-center align-items-center">
                    <div class="text-center">
                        <h5><i class="far fa-envelope"></i> support@email.com</h5>
                        <h5><i class="fas fa-phone"></i> 012-3456789</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection