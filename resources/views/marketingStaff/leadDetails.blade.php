@extends('layouts.dashboard')

@section('title', 'Lead Details')

@section('content')
<div class="container-content">
    <div class="header">Lead Details</div>

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
            <h5>{{ $lead->status }} Lead </h5>
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
                                    <i class="fas fa-times-circle"></i> Not interested
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
                    <p class="card-text">Gender: {{ $lead->gender }}</p>
                    <p class="card-text">Created At: @if ($lead->created_at)
                        {{ $lead->created_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}
                        @else
                        NA
                        @endif
                    </p>
                    <p class="card-text">Updated At: @if ($lead->updated_at)
                        {{ $lead->updated_at->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}
                        @else
                        N/A
                        @endif
                    </p>
                    <div class="activity mb-2">
                        <p>Activity: @if ($lead->activity_date)
                            (Updated At: {{ $lead->activity_date->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }})
                            @else
                            (Updated At: NA)
                            @endif
                        </p>
                        <div class="tag-container">
                            @php
                            $activityTags = $lead->activity ? explode(',', $lead->activity) : ['NA'];
                            @endphp
                            @foreach($activityTags as $tag)
                            <button class="tag">{{ trim($tag) }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="feedback mb-4">
                        <p>Feedback: @if ($lead->feedback_date)
                            (Updated At: {{ $lead->feedback_date->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }})
                            @else
                            (Updated At: NA)
                            @endif
                        </p>
                        <div class="tag-container">
                            @php
                            $feedbackTags = $lead->feedback ? explode(',', $lead->feedback) : ['NA'];
                            @endphp
                            @foreach($feedbackTags as $tag)
                            <button class="tag">{{ trim($tag) }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('marketingStaff.leadManagement') }}" class="btn btn-secondary">Back to Lead Management</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection