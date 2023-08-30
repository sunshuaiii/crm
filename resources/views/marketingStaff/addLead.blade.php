@extends('layouts.dashboard')

@section('title', 'Add New Lead')

@section('content')
<div class="container-content">
    <div class="header">Add New Lead</div>
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

    <form id="addLeadForm" method="POST" action="{{ route('marketingStaff.storeLead') }}">
        @csrf
        <div class="form-group">
            <label for="first_name">First Name *</label>
            <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" required value="{{ old('first_name') }}" autocomplete="first_name" autofocus>
            @error('first_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="last_name">Last Name *</label>
            <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" required value="{{ old('last_name') }}" autocomplete="last_name" autofocus>
            @error('last_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="contact">Contact *</label>
            <input type="text" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" required value="{{ old('contact') }}" autocomplete="contact" autofocus>
            @error('contact')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}" autocomplete="email" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="gender">Gender *</label>
            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
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
            <label for="activity">Activity *</label>
            <select name="activity[]" id="activity" class="form-control @error('activity') is-invalid @enderror" multiple required>
                @if(old('activity'))
                @foreach(explode(',', old('activity')) as $item)
                <option value="{{ $item }}" selected>{{ $item }}</option>
                @endforeach
                @endif
            </select>
            <input type="hidden" name="activity_string" id="activity_string" value="{{ old('activity', '') }}">

            @error('activity')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="feedback">Feedback</label>
            <select name="feedback[]" id="feedback" class="form-control @error('feedback') is-invalid @enderror" multiple>
                @if(old('feedback'))
                @foreach(explode(',', old('feedback')) as $item)
                <option value="{{ $item }}" selected>{{ $item }}</option>
                @endforeach
                @endif
            </select>
            <input type="hidden" name="feedback_string" id="feedback_string" value="{{ old('feedback', '') }}">
            @error('feedback')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary" onclick="showConfirmation()">Add This Lead</button>
            <a href="{{ route('marketingStaff.leadManagement') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Go Back
            </a>
        </div>
    </form>
</div>

<script>
    function showConfirmation() {
        if (confirm("Are you sure you want to add this lead?")) {
            // If user confirms, submit the form
            document.getElementById('addLeadForm').submit();
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