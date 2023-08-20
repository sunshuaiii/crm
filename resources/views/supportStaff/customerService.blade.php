@extends('layouts.dashboard')

@section('title', 'Customer Service')

@section('content')
<div class="container-content">
    <div class="header">Customer Service</div>
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
        <div id="status-update-message" class="alert alert-success" style="display: none;"></div>

        <h4>Tickets Assigned:</h4>
        @if(isset($groupedTickets) && count($groupedTickets) > 0)
        @foreach ($groupedTickets as $queryType => $tickets)
        <h5>{{ $queryType }}</h5>
        <div class="row">
            @forelse ($tickets as $ticket)
            <div class="col-md-12 mb-4">
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

                        <p class="card-text">Customer ID: {{ $ticket->customer_id }}</p>
                        <p class="card-text">Message: {{ strlen($ticket->message) > 200 ? substr($ticket->message, 0, 200) . '...' : $ticket->message }}</p>

                        <div class="d-flex justify-content-between">
                            <div class="text-end">
                                <a href="{{ route('supportStaff.viewTicket', ['id' => $ticket->id]) }}" class="btn btn-primary">View Ticket Details</a>
                                <a href="{{ route('supportStaff.viewCustomer', ['id' => $ticket->customer_id]) }}" class="btn btn-secondary">View Customer Details</a>
                            </div>

                            <div class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown{{ $ticket->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Update Status
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $ticket->id }}">
                                        <li><a class="dropdown-item status-update" data-ticket-id="{{ $ticket->id }}" data-new-status="New" href="#">New</a></li>
                                        <li><a class="dropdown-item status-update" data-ticket-id="{{ $ticket->id }}" data-new-status="Open" href="#">Open</a></li>
                                        <li><a class="dropdown-item status-update" data-ticket-id="{{ $ticket->id }}" data-new-status="Pending" href="#">Pending</a></li>
                                        <li><a class="dropdown-item status-update" data-ticket-id="{{ $ticket->id }}" data-new-status="Solved" href="#">Solved</a></li>
                                        <li><a class="dropdown-item status-update" data-ticket-id="{{ $ticket->id }}" data-new-status="Closed" href="#">Closed</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p>No tickets assigned under this query type.</p>
            @endforelse
        </div>
        @endforeach
        @else
        <p>No tickets found.</p>
        @endif
    </div>
</div>

<script>
    $('.status-update').on('click', function(e) {
        e.preventDefault();
        const ticketId = $(this).data('ticket-id');
        const newStatus = $(this).data('new-status');

        $.ajax({
            url: "{{ route('supportStaff.updateTicketStatus') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ticket_id: ticketId,
                new_status: newStatus
            },
            success: function(response) {
                // Get the updated ticket details from the response
                var updatedTicket = response.updatedTicket;

                // Show success message with the old and new status
                var message = "Status for Ticket ID: " + updatedTicket.id + " is updated from '" + updatedTicket.oldStatus + "' to '" + updatedTicket.newStatus + "'";

                $('#status-update-message').text(message).fadeIn();
            },
            error: function() {
                alert('An error occurred while updating the status.');
            }
        });
    });
</script>
@endsection