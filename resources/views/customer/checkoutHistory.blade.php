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

        <div class="col-md-12">
            <h2>Checkout History</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Payment Method</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($checkoutHistory as $checkout)
                    <tr>
                        <td>{{ $checkout->date->setTimezone('Asia/Kuala_Lumpur')->format('d M Y H:i:s') }}</td>
                        <td>{{ $checkout->payment_method }}</td>
                        <td>RM {{ $checkout->checkoutProducts->sum(function ($item) {
                                return $item->quantity * $item->product->unit_price;
                            }) }}</td>
                        <td><a href="{{ route('customer.checkoutDetails', ['id' => $checkout->id]) }}" class="btn btn-primary">View Details</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection