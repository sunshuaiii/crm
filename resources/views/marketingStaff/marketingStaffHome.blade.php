@extends('layouts.dashboard')

@section('title', 'Marketing Staff Dashboard')

@section('content')
<div class="container-content">
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
    <div class="header">Marketing Staff Dashboard</div>

    <!-- Add buttons to trigger update -->
    <div class="row col-md-12">
        <div class="col-md-3">
            <form id="updateRfmForm" action="{{ route('marketingStaff.updateRfmScores') }}" method="post">
                @csrf
                <button type="button" class="btn btn-primary" onclick="updateRfmScores()">
                    <i id="updateIcon1" class="fas fa-sync"></i> Update RFM Scores
                </button>
            </form>
        </div>
        <div class="mb-4">
            <form id="updateCSegmentForm" action="{{ route('marketingStaff.updateCSegment') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="updateCSegment()">
                    <i id="updateIcon2" class="fas fa-sync"></i> Update Customer Segment
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <h4 class="sub-header">Customer Insights</h4>
        <div class="card">
            <div class="card-body">

            </div>
        </div>
    </div>

</div>

<script>
    function updateRfmScores() {
        var button = document.getElementById('updateIcon1');
        var form = document.getElementById('updateRfmForm');

        button.classList.remove('fa-sync');
        button.classList.add('fa-spinner', 'fa-spin');

        form.submit();
    }

    function updateCSegment() {
        var button = document.getElementById('updateIcon2');
        var form = document.getElementById('updateCSegmentForm');

        button.classList.remove('fa-sync');
        button.classList.add('fa-spinner', 'fa-spin');

        form.submit();
    }
</script>
@endsection