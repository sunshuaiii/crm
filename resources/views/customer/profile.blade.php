@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('My Profile Details') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('customer.profile.update') }}">
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

                        @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                        @endif

                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Userame') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('first_name') is-invalid @enderror" name="username" value="{{ Auth::user()->username }}">

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" disabled>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ Auth::user()->first_name }}">

                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ Auth::user()->last_name }}">

                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="contact" class="col-md-4 col-form-label text-md-end">{{ __('Mobile Number') }}</label>

                            <div class="col-md-6">
                                <input id="contact" type="text" class="form-control" name="contact" value="{{ Auth::user()->contact }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            @if(Auth::user()->dob)<label for="dob" class="col-md-4 col-form-label text-md-end">{{ __('Date of Birth') }}</label>
                            <div class="col-md-6">
                                <input id="dob" type="text" class="form-control" name="dob" value="{{ date('d/m/Y', strtotime(Auth::user()->dob)) }}" disabled>
                            </div>
                            @else
                            <label for="dob" class="col-md-4 col-form-label text-md-end">{{ __('Date of Birth') }} *</label>
                            <div class="col-md-6">
                                <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required autocomplete="bday">
                                @error('dob')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            @endif
                        </div>

                        <div class="row mb-3">
                            <label for="gender" class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>
                            <div class="col-md-6">
                                <select id="gender" class="form-select" name="gender">
                                    <option value="" {{ Auth::user()->gender === null ? 'selected' : '' }}></option>
                                    <option value="Male" {{ Auth::user()->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ Auth::user()->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-4">
                                @if(Auth::user()->dob)
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save Changes') }}
                                </button>
                                @else
                                <button type="submit" class="btn btn-primary" onclick="showConfirmation()">
                                    {{ __('Save Changes') }}
                                </button>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showConfirmation() {
        var dobField = document.getElementById('dob');

        if (dobField && !dobField.value) {
            alert("Please fill in the Date of Birth field.");
        } else {
            var confirmationMessage = "Please review your details before proceeding. The Date of Birth (DOB) field cannot be changed afterwards. Are you sure you want to continue?";

            if (confirm(confirmationMessage)) {
                // User confirmed, proceed with the form submission
                document.querySelector('form').submit();
            } else {
                // User canceled, do nothing
            }
        }
    }
</script>
@endsection