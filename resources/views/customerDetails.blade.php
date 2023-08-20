@extends('layouts.dashboard')

@section('title', 'View Customer Details ')

@section('content')
<div class="container-content">
    <div class="header">View Customer Details</div>

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

    <div class="mt-4">
        <h4>Customer Details:</h4>
        <table class="table">
            <tr>
                <th>ID</th>
                <td>{{ $customer->id }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td>{{ $customer->username ?? 'NA' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $customer->email }}</td>
            </tr>
            <tr>
                <th>Google ID</th>
                <td>{{ $customer->google_id ?? 'NA' }}</td>
            </tr>
            <tr>
                <th>First Name</th>
                <td>{{ $customer->first_name ?? 'NA' }}</td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td>{{ $customer->last_name ?? 'NA' }}</td>
            </tr>
            <tr>
                <th>Contact</th>
                <td>{{ $customer->contact ?? 'NA' }}</td>
            </tr>
            <tr>
                <th>Gender</th>
                <td>{{ $customer->gender ?? 'NA' }}</td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td>{{ $customer->dob ?? 'NA' }}</td>
            </tr>
            <tr>
                <th>Points</th>
                <td>{{ $customer->points }}</td>
            </tr>
            <tr>
                <th>Recency Score</th>
                <td>{{ $customer->r_score }}</td>
            </tr>
            <tr>
                <th>Frequency Score</th>
                <td>{{ $customer->f_score }}</td>
            </tr>
            <tr>
                <th>Monetary Score</th>
                <td>{{ $customer->m_score }}</td>
            </tr>
            <tr>
                <th>Customer Segment</th>
                <td>{{ $customer->c_segment }}</td>
            </tr>
            <tr>
                <th>Created At</th>
                <td>@if ($customer->created_at)
                    {{ $customer->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}
                    @else
                    NA
                    @endif
                </td>
            </tr>
            <tr>
                <th>Updated At</th>
                <td>@if ($customer->updated_at)
                    {{ $customer->updated_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}
                    @else
                    NA
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Previous Page
        </a>
    </div>
</div>
@endsection