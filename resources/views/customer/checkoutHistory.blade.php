@extends('layouts.app')

@section('title', 'Checkout History')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12">
            <h2>Checkout History</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Payment Method</th>
                        <th>Total Amount</th>
                        <th>Final Amount</th>
                        <th>Points Credited</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($checkoutSummaries as $checkoutSummary)
                    <tr>
                        <td>{{ $checkoutSummary['checkout']->date->setTimezone('Asia/Kuala_Lumpur')->format('d M Y H:i:s') }}</td>
                        <td>{{ $checkoutSummary['checkout']->payment_method }}</td>
                        <td>RM {{ $checkoutSummary['totalAmount'] }}</td>
                        <td>RM {{ $checkoutSummary['finalAmount'] }}</td>
                        <td>{{ $checkoutSummary['pointsToCredit'] }} points</td>
                        <td>
                            <a href="{{ route('customer.checkoutDetails', ['id' => $checkoutSummary['checkout']->id]) }}" class="btn btn-primary">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection