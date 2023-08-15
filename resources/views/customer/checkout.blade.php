@extends('layouts.app')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
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
        </div>

        <h2>Checkout Details</h2>

        <!-- Display checkout details -->
        <p>Payment Method: {{ $checkout->payment_method }}</p>
        <p>Checkout Date: {{ $checkout->date->setTimezone('Asia/Kuala_Lumpur')->format('d M Y H:i:s') }}</p>

        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($checkout->checkoutProducts as $checkoutProduct)
                <tr>
                    <td>{{ $checkoutProduct->product->name }}</td>
                    <td>{{ $checkoutProduct->quantity }}</td>
                    <td>RM {{ $checkoutProduct->product->unit_price }}</td>
                    <td>RM {{ $checkoutProduct->product->unit_price * $checkoutProduct->quantity }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>

        <div class="card-text mt-3 text-center">
            <!-- Other details here -->
            <h6>Total Amount: RM {{ $totalAmount }}</h6>
        </div>

        <div class="row justify-content-center align-items-center mt-3">
            <div class="col-md-6 text-center">
                <a href="{{ route('customer.coupons') }}" class="btn btn-secondary">Back to Coupons</a>
            </div>
            <div class="col-md-6 text-center">
                <a href="{{ route('customer.profile') }}" class="btn btn-secondary">Go to Checkout History</a>
            </div>
        </div>

    </div>
</div>
@endsection