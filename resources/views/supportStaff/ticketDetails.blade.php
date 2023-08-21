@extends('layouts.dashboard')

@section('title', 'Ticket Details')

@section('content')
<div class="container-content">
    <div class="header">Ticket Details</div>

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

    <div class="row mt-4">
        <div class="col-md-8 offset-md-2">
            <h5>{{ $ticket->query_type }}</h5>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">Ticket ID: {{ $ticket->id }}</h5>
                        <div class="card-text">
                            <p>Status: <span class="badge bg-primary">
                                    @if ($ticket->status === 'New')
                                    <i class="fas fa-circle"></i> New
                                    @elseif ($ticket->status === 'Open')
                                    <i class="fas fa-circle-notch"></i> Open
                                    @elseif ($ticket->status === 'Pending')
                                    <i class="fas fa-clock"></i> Pending
                                    @elseif ($ticket->status === 'Solved')
                                    <i class="fas fa-check-circle"></i> Solved
                                    @elseif ($ticket->status === 'Closed')
                                    <i class="fas fa-times-circle"></i> Closed
                                    @endif
                                </span></p>
                        </div>
                    </div>
                    <h5 class="card-title">Customer ID: {{ $ticket->customer_id }}</h5>
                    <p class="card-text">Response Time Used: @if ($ticket->response_time === null)
                        NA
                        @else
                        {{ round($ticket->response_time / 60) }} minutes
                        @endif
                    </p>
                    <p class="card-text">Resolution Time Used: @if ($ticket->resolution_time === null)
                        NA
                        @else
                        {{ round($ticket->resolution_time / 60) }} minutes
                        @endif
                    </p>
                    <p class="card-text">Created At: @if ($ticket->created_at)
                        {{ $ticket->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}
                        @else
                        NA
                        @endif
                    </p>
                    <p class="card-text">Updated At: @if ($ticket->updated_at)
                        {{ $ticket->updated_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}
                        @else
                        N/A
                        @endif
                    </p>
                    <p class="card-text">{{ $ticket->message }}</p>

                    <div class="text-end">
                        <a href="{{ route('supportStaff.customerService') }}" class="btn btn-secondary">Back to Tickets Assigned</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection