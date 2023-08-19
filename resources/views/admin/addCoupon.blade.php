@extends('layouts.dashboard')

@section('title', 'Add New Coupon')

@section('content')
<div class="container-content">
    <div class="header">Add New Coupon</div>
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

    <form id='addCouponForm' method="POST" action="{{ route('admin.storeCoupon') }}">
        @csrf
        <div class="form-group">
            <label for="name">Coupon Name *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}" autocomplete="name" autofocus>
            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="discount">Discount (RM) *</label>
            <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" required min="0" required value="{{ old('discount') }}" autocomplete="discount" autofocus>
            @error('discount')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="redemption_points">Redemption Points *</label>
            <input type="number" name="redemption_points" class="form-control @error('redemption_points') is-invalid @enderror" required min="0" required value="{{ old('redemption_points') }}" autocomplete="redemption_points" autofocus>
            @error('redemption_points')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="conditions">Terms and Conditions *</label>
            <textarea name="conditions" class="form-control @error('conditions') is-invalid @enderror" rows="4" required required value="{{ old('conditions') }}" autocomplete="conditions" autofocus></textarea>
            @error('conditions')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="text-center">

        <div class="text-center">
            <button type="submit" class="btn btn-primary" onclick="showConfirmation()">Add This Coupon</button>
            <a href="{{ route('admin.couponManagement') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Go Back
            </a>
        </div>
    </form>
</div>

<script>
    function showConfirmation() {
        if (confirm("Are you sure you want to add this coupon?")) {
            // If user confirms, submit the form
            document.getElementById('addCouponForm').submit();
        }
    }
</script>
@endsection