@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ isset($url) && $url === 'customer' ? 'Customer' : '' }} {{ __('Register') }}</div>
                <div class="card-body">
                    <form method="POST" action='{{ url("register/$url") }}' aria-label="{{ __('Register') }}">
                        @csrf

                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Step 1: User Information -->
                        <div id="step-1">
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

                            <div class="text-center">
                                <button type="button" class="btn btn-primary" onclick="validateStep(1, 2)">Next</button>
                            </div>
                        </div>

                        <!-- Step 2: Additional Information -->
                        <div id="step-2" style="display: none;">
                            <div class="row mb-3">
                                <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }} *</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" autofocus>

                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }} *</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">

                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="contact" class="col-md-4 col-form-label text-md-end">{{ __('Contact') }} *</label>

                                <div class="col-md-6">
                                    <input id="contact" type="text" class="form-control @error('contact') is-invalid @enderror" name="contact" value="{{ old('contact') }}" required autocomplete="tel">

                                    @error('contact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="dob" class="col-md-4 col-form-label text-md-end">{{ __('Date of Birth') }} *</label>

                                <div class="col-md-6">
                                    <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required autocomplete="bday">

                                    @error('dob')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('Gender') }} *</label>

                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="gender-male" value="Male">
                                        <label class="form-check-label" for="gender-male">
                                            {{ __('Male') }}
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="gender-female" value="Female">
                                        <label class="form-check-label" for="gender-female">
                                            {{ __('Female') }}
                                        </label>
                                    </div>

                                    @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Previous</button>
                                <button type="button" class="btn btn-primary" onclick="validateStep(2, 3)">Next</button>
                            </div>
                        </div>

                        <!-- Step 3: Account Information -->
                        <div id="step-3" style="display: none;">
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
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }} *</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Previous</button>
                                <button type="submit" class="btn btn-primary" onclick="showConfirmation()">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>


                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="{{ route('register.customer') }}">Sign in now!</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Confirmation Pop-up -->
<script>
    var currentStep = 1;

    function nextStep(step) {
        document.getElementById('step-' + currentStep).style.display = 'none';
        document.getElementById('step-' + step).style.display = 'block';
        currentStep = step;
    }

    function prevStep(step) {
        document.getElementById('step-' + currentStep).style.display = 'none';
        document.getElementById('step-' + step).style.display = 'block';
        currentStep = step;
    }

    function validateStep(current, next) {
        if (areRequiredFieldsFilled(current)) {
            nextStep(next);
        } else {
            alert('Please fill in all required fields before proceeding.');
        }
    }

    function showConfirmation() {
        // Check if all required fields are entered in the final step
        if (areRequiredFieldsFilled(3)) {
            if (confirm('Are you sure you want to submit the registration?')) {
                document.querySelector('form').submit();
            }
        } else {
            alert('Please fill in all required fields before submitting.');
        }
    }

    function areRequiredFieldsFilled(step) {
        // List of required field IDs for each step
        var requiredFields = {
            1: ['username', 'email'],
            2: ['first_name', 'last_name', 'contact', 'dob', 'gender'],
            3: ['password', 'password-confirm']
        };

        // Check if each required field is filled for the specified step
        var fields = requiredFields[step];
        for (var i = 0; i < fields.length; i++) {
            var field = document.getElementById(fields[i]);
            if (field && field.value.trim() === '') {
                return false;
            }
        }

        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-password');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const passwordField = this.parentNode.querySelector('input[id="password"]');
                const icon = this.querySelector('i');
                console.log(passwordField.type);

                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>
@endsection
