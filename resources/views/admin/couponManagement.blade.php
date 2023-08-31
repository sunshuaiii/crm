@extends('layouts.dashboard')

@section('title', 'Coupon Management')

@section('content')
<div class="container-content">
    <div class="header">Coupon Management</div>
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

    <div class="text-right mb-3">
        <a href="{{ route('admin.addCoupon') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add New Coupon
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Discount</th>
                    <th>Redemption Points</th>
                    <th>Conditions</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->id }}</td>
                    <td>{{ $coupon->name }}</td>
                    <td>{{ $coupon->discount }}</td>
                    <td>{{ $coupon->redemption_points }}</td>
                    <td>{{ $coupon->conditions }}</td>
                    <td>
                        @if ($coupon->created_at)
                        {{ $coupon->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}
                        @else
                        NA
                        @endif
                    </td>
                    <td>
                        @if ($coupon->updated_at)
                        {{ $coupon->updated_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}
                        @else
                        NA
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.editCoupon', ['id' => $coupon->id]) }}" class="btn btn-link">
                            <i class="bx bx-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
