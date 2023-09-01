@extends('layouts.app')

@section('title', 'Checkout History')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12">
            <h2><i class="fas fa-history"></i> Checkout History</h2>

            @if(!empty($checkoutSummaries))
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><i class="far fa-calendar-alt"></i> Date</th>
                            <th><i class="far fa-credit-card"></i> Payment Method</th>
                            <th><i class="fas fa-dollar-sign"></i> Total Amount</th>
                            <th><i class="fas fa-money-bill-wave"></i> Final Amount</th>
                            <th><i class="fas fa-star"></i> Points Credited</th>
                            <th><i class="fas fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($checkoutSummaries as $checkoutSummary)
                        <tr>
                            <td>{{ $checkoutSummary['checkout']->date->setTimezone('Asia/Kuala_Lumpur')->format('d M Y H:i:s') }}</td>
                            <td>{{ $checkoutSummary['checkout']->payment_method }}</td>
                            <td>RM {{ number_format($checkoutSummary['totalAmount'], 2) }}</td>
                            <td>RM {{ number_format($checkoutSummary['finalAmount'], 2) }}</td>
                            <td>{{ $checkoutSummary['pointsToCredit'] }} points</td>
                            <td>
                                <a href="{{ route('customer.checkoutDetails', ['id' => $checkoutSummary['checkout']->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-info-circle"></i> View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8 m-5">
                    <div class="text-center">
                        <h5>You haven't made any checkouts or transactions yet. Start enjoying our exclusive offers and rewards by making your first purchase!</h5>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
