@extends('layouts.dashboard')

@section('title', 'Lead Management')

@section('content')
<div class="container-content">
    <div class="header">Lead Management</div>
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

    <div class="text-right">
        <a href="{{ route('marketingStaff.addLead') }}" class="btn btn-primary add-button">
            <i class='bx bx-plus'></i> Add New Lead
        </a>
    </div>

    <div class="mt-4">
        <div id="status-update-message" class="alert alert-success" style="display: none;"></div>

        <div class="mb-3">
            <label for="leadStatusFilter" class="form-label">Filter by Status:</label>
            <select class="form-select" id="leadStatusFilter">
                <option value="all" selected>All Statuses</option>
                <option value="New">New</option>
                <option value="Interested">Interested</option>
                <option value="Not interested">Not Interested</option>
                <option value="Contacted">Contacted</option>
            </select>
        </div>

        <h4>Track Leads:</h4>
        @if(isset($groupedLeads) && count($groupedLeads) > 0)
        @foreach ($groupedLeads as $status => $leads)
        <h5>{{ $status }}</h5>
        <div class="row">
            @forelse ($leads as $lead)
            <div class="col-md-12 mb-4 lead-card" data-lead-status="{{ $lead->status }}">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Lead ID: {{ $lead->id }}</h5>
                            <div class="card-text">
                                <p>Status: <span class="badge bg-primary">
                                        @if ($lead->status === 'New')
                                        <i class="fas fa-hourglass-start"></i> New
                                        @elseif ($lead->status === 'Interested')
                                        <i class="fas fa-handshake"></i> Interested
                                        @elseif ($lead->status === 'Not interested')
                                        <i class="fas fa-times-circle"></i> Not Interested
                                        @elseif ($lead->status === 'Contacted')
                                        <i class="fas fa-envelope-open-text"></i> Contacted
                                        @endif
                                    </span></p>
                            </div>
                        </div>

                        <p class="card-text">First Name: {{ $lead->first_name }}</p>
                        <p class="card-text">Last Name: {{ $lead->last_name }}</p>
                        <p class="card-text">Contact: {{ $lead->contact }}</p>
                        <p class="card-text">Email: {{ $lead->email }}</p>

                        <div class="tag-container activity mb-2">
                            <p class="card-text">Activity:</p>
                            @php
                            $activityTags = $lead->activity ? explode(',', $lead->activity) : ['NA'];
                            @endphp
                            @foreach($activityTags as $tag)
                            <button class="tag">{{ trim($tag) }}</button>
                            @endforeach
                        </div>

                        <div class="tag-container feedback mb-2">
                            <p class="card-text">Feedback:</p>
                            @php
                            $feedbackTags = $lead->feedback ? explode(',', $lead->feedback) : ['NA'];
                            @endphp
                            @foreach($feedbackTags as $tag)
                            <button class="tag">{{ trim($tag) }}</button>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <div class="text-end">
                                <a href="{{ route('marketingStaff.viewLead', ['id' => $lead->id]) }}" class="btn btn-primary">View Lead Details</a>
                                <a href="{{ route('marketingStaff.updateLead', ['id' => $lead->id]) }}" class="btn btn-primary">Update Lead Details</a>
                            </div>

                            <div class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown{{ $lead->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Update Status
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $lead->id }}">
                                        <li><a class="dropdown-item status-update" data-lead-id="{{ $lead->id }}" data-old-status="{{ $lead->status }}" data-new-status="New" href="#">New</a></li>
                                        <li><a class="dropdown-item status-update" data-lead-id="{{ $lead->id }}" data-old-status="{{ $lead->status }}" data-new-status="Interested" href="#">Interested</a></li>
                                        <li><a class="dropdown-item status-update" data-lead-id="{{ $lead->id }}" data-old-status="{{ $lead->status }}" data-new-status="Not interested" href="#">Not Interested</a></li>
                                        <li><a class="dropdown-item status-update" data-lead-id="{{ $lead->id }}" data-old-status="{{ $lead->status }}" data-new-status="Contacted" href="#">Contacted</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p>No lead under this status.</p>
            @endforelse
        </div>
        @endforeach
        @else
        <p>No lead found.</p>
        @endif
    </div>
</div>

<script>
    $('#leadStatusFilter').on('change', function() {
        const selectedStatus = $(this).val();

        $('.lead-card').each(function() {
            const leadStatus = $(this).data('lead-status'); // Use .data() to access the data attribute

            console.log(`Lead status: ${leadStatus}, Selected status: ${selectedStatus}`);

            if (selectedStatus === 'all' || leadStatus === selectedStatus) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('.status-update').on('click', function(e) {
        console.log('Status update clicked');

        e.preventDefault();
        const leadId = $(this).data('lead-id');
        const newStatus = $(this).data('new-status');

        // Get the current status from the PHP variable
        const currentStatus = $(this).data('old-status');

        // Create a confirmation message
        const confirmationMessage = `Confirm to change Lead ID: "${leadId}" from "${currentStatus}" to "${newStatus}"?`;

        // Show the confirmation dialog
        const isConfirmed = confirm(confirmationMessage);

        if (isConfirmed) {
            $.ajax({
                url: "{{ route('marketingStaff.updateLeadStatus') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    lead_id: leadId,
                    new_status: newStatus,
                },
                success: function(response) {
                    // Get the updated ticket details from the response
                    var updatedLead = response.updatedLead;

                    // Show success message with the old and new status
                    var message = "Status for Lead ID: " + updatedLead.id + " is updated from '" + updatedLead.oldStatus + "' to '" + updatedLead.newStatus + "'";

                    $('#status-update-message').text(message).fadeIn();
                },
                error: function() {
                    alert('An error occurred while updating the status.');
                }
            });
        }
    });
</script>
@endsection