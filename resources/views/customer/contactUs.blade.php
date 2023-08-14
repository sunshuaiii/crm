@extends('layouts.app')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif
            <h2 class="heading">We'd love to hear from you.</h2>
            <div class="text-center">
                <h5>Our team is ready to answer all your questions!</h5>
            </div>
        </div>
    </div>

    <div class="container-row">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header text-center"> Contacts </div>
                    <div class="card-body justify-content-center align-items-center">
                        <div class="text-center">
                            <h5>support@email.com</h5>
                            <h5>012-3456789</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center"> Get in touch with us </div>
                    <div class="card-body">
                        <form action="{{ route('customer.support.contactUs.submit') }}" method="POST">
                            @csrf

                            <input type="text" class="form-control" id="customer_id" name="customer_id" value="{{ Auth::user()->id }}" hidden>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ Auth::user()->first_name }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ Auth::user()->last_name }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email"> Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="contact">Phone</label>
                                <input type="tel" class="form-control" id="contact" name="contact" value="{{ Auth::user()->contact }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="query_type">Type of Query</label>
                                <select class="form-select" id="query_type" name="query_type" required>
                                    <option value="" selected disabled>Select a query type</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Complaint">Complaint</option>
                                    <option value="Query">Query</option>
                                    <option value="Issue">Issue</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Your Message</label>
                                <textarea class="form-control" id="message" name="message" placeholder="Type your message here..." rows="4" required></textarea>
                            </div>



                            <div class="row m-3">
                                <button type="submit" class="btn btn-primary" onclick="showConfirmation()">
                                    Send
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- JavaScript for Confirmation Pop-up -->
<script>
    function showConfirmation() {
        // Check if all required fields are entered
        if (areRequiredFieldsFilled()) {
            if (confirm('Are you sure you want to send the message?')) {
                document.querySelector('form').submit();
            }
        } else {
            alert('Please fill in all required fields before sending.');
        }
    }

    function areRequiredFieldsFilled() {
        // List of required field IDs
        var requiredFields = ['query_type', 'message'];

        // Check if each required field is filled
        for (var i = 0; i < requiredFields.length; i++) {
            var field = document.getElementById(requiredFields[i]);
            if (field && field.value.trim() === '') {
                return false;
            }
        }

        return true;
    }
</script>
@endsection