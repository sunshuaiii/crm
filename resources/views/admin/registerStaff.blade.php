@extends('layouts.dashboard')

@section('title', 'Register New '. $url)

@section('content')
<div class="container-content">
    <div class="header">Register New {{ ucfirst($url) }}</div>
    <form method="POST" action='{{ url("admin/register/$url") }}' aria-label="{{ __('Register') }}">
        @csrf

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row mb-3">
            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }} *</label>

            <div class="col-md-6">
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="name" autofocus>

                @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }} *</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }} *</label>

            <div class="col-md-6">
                <div class='input-group'>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="fas fa-eye"></i>
                    </button>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <small class="text-muted">Password must be at least 8 characters long.</small>

            </div>
        </div>

        <div class="row mb-3">
            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }} *</label>

            <div class="col-md-6">
                <div class='input-group'>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="fas fa-eye"></i>
                    </button>
                    @error('password-confirm')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-0 row">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary" onclick="showConfirmation()">
                    {{ __('Register') }}
                </button>
                <a href="{{ route('admin.staffRegistration') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Go Back
                </a>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript for Confirmation Pop-up -->
<script>
    function showConfirmation() {
        // Check if all required fields are entered
        if (areRequiredFieldsFilled()) {
            if (confirm('Are you sure you want to submit the registration?')) {
                document.querySelector('form').submit();
            }
        } else {
            alert('Please fill in all required fields before submitting.');
        }
    }

    function areRequiredFieldsFilled() {
        // List of required field IDs
        var requiredFields = ['username', 'email', 'password', 'password-confirm'];

        // Check if each required field is filled
        for (var i = 0; i < requiredFields.length; i++) {
            var field = document.getElementById(requiredFields[i]);
            if (field && field.value.trim() === '') {
                return false;
            }
        }

        return true;
    }

    $(document).ready(function() {
        $(".toggle-password").click(function() {
            // Find the associated input field
            var inputField = $(this).siblings("input");
            const icon = this.querySelector('i');

            // Toggle the input field's type attribute between "password" and "text"
            if (inputField.attr("type") === "password") {
                inputField.attr("type", "text");
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                inputField.attr("type", "password");
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>
@endsection