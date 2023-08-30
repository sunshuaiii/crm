@extends('layouts.dashboard')

@section('title', 'Update Lead Details')

@section('content')
<div class="container-content">
    <div class="header">Update Lead Details</div>
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

    <form id="updateLeadForm" method="POST" action="{{ route('marketingStaff.storeUpdatedLead', ['id' => $lead->id]) }}">
        @csrf
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $lead->first_name) }}" autocomplete="first_name" autofocus>
            @error('first_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $lead->last_name) }}" autocomplete="last_name" autofocus>
            @error('last_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact', $lead->contact) }}" autocomplete="contact" autofocus>
            @error('contact')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $lead->email) }}" autocomplete="email" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
            </select>
            @error('gender')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="activity">Activity</label>
            <select name="activity[]" id="activity" class="form-control @error('activity') is-invalid @enderror" multiple>
                @foreach(explode(',', $lead->activity) as $item)
                <option value="{{ $item }}" {{ in_array($item, old('activity', explode(',', $lead->activity))) ? 'selected' : '' }}>
                    {{ $item }}
                </option>
                @endforeach
            </select>
            <input type="hidden" name="activity_string" id="activity_string" value="{{ old('activity', $lead->activity) }}">

            @error('activity')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="feedback">Feedback</label>
            <select name="feedback[]" id="feedback" class="form-control @error('feedback') is-invalid @enderror" multiple>
                @foreach(explode(',', $lead->feedback) as $item)
                <option value="{{ $item }}" {{ in_array($item, old('feedback', explode(',', $lead->feedback))) ? 'selected' : '' }}>
                    {{ $item }}
                </option>
                @endforeach
            </select>
            <input type="hidden" name="feedback_string" id="feedback_string" value="{{ old('feedback', $lead->feedback) }}">
            @error('feedback')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary" onclick="showConfirmation()">Update This Lead</button>
            <a href="{{ route('marketingStaff.leadManagement') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Go Back
            </a>
        </div>
    </form>
</div>

<script>
    function showConfirmation() {
        if (confirm("Please confirm the updated lead details.")) {
            // If user confirms, submit the form
            document.getElementById('updateLeadForm').submit();
        }
    }

    $(document).ready(function() {
        $('#activity, #feedback').select2({
            tags: true,
            tokenSeparators: [',']
        });

        $('#activity').on('change', function() {
            $('#activity_string').val($(this).val().join(','));
        });

        $('#feedback').on('change', function() {
            $('#feedback_string').val($(this).val().join(','));
        });
    });
</script>
@endsection