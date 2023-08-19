@extends('layouts.dashboard')

@section('title', 'Search Customer')

@section('content')
<div class="container-content">
    <div class="header">Search Customer</div>
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

    <form method="post" action="{{ route('admin.searchCustomer.submit') }}">
        @csrf
        <div class="mb-3">
            <label for="keyword" class="form-label">Search Keywords</label>
            <input type="text" name="keyword" class="form-control" value="{{ $keyword ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="filterBy" class="form-label">Search By</label>
            <select name="filterBy" class="form-select" required>
                <option value="first_name" {{ isset($filterBy) && $filterBy == 'first_name' ? 'selected' : '' }}>First Name</option>
                <option value="last_name" {{ isset($filterBy) && $filterBy == 'last_name' ? 'selected' : '' }}>Last Name</option>
                <option value="email" {{ isset($filterBy) && $filterBy == 'email' ? 'selected' : '' }}>Email</option>
                <option value="contact" {{ isset($filterBy) && $filterBy == 'contact' ? 'selected' : '' }}>Contact</option>
                <!-- Add more options for filtering by other columns -->
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div class="mt-4">
        <h4>Search Results:</h4>
        @if(isset($customers) && count($customers) > 0)
        <p>{{ count($customers) }} match result(s) found:</p>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID</th>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Contact</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->first_name }}</td>
                    <td>{{ $customer->last_name }}</td>
                    <td>{{ $customer->contact }}</td>
                    <td>{{ $customer->created_at }}</td>
                    <td><a href="{{ route('admin.viewCustomer', ['id' => $customer->id]) }}" class="btn btn-primary">View Customer</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No customers found.</p>
        @endif
    </div>
</div>
@endsection