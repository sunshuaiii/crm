<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .marketing-staff-insights {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f7f7f7;
            margin-top: 20px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
        }

        .marketing-staff-insights h4 {
            margin-bottom: 10px;
        }

        .marketing-staff-insights p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="marketing-staff-insights">
        <h4 class="chart-title">Lead Insights</h4>
        <h5>Lead Assigned: {{ $leadsAssigned }}</h5>
        <p>New Leads: {{ $newLeads }}</p>
        <p>Contacted Leads: {{ $contactedLeads }}</p>
        <p>Interested Leads: {{ $interestedLeads }}</p>
        <p>Not Interested Leads: {{ $notInterestedLeads }}</p>

        <div class="row justify-content-center">
            <div class='col-md-6 mt-5'>
                <h6 class="section-description">Assigned lead counts for each lead status.</h6>
                <h4 class="chart-title">Marketing Staff Performance Analysis</h4>
                <div style="max-width: 600px; margin: auto;">
                    <canvas id="staffPerformanceChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</body>

<script>
    function renderStaffPerformanceChart(staffIds, statuses, staffData) {
        var statusColors = ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)']; // Add more colors if needed
        
        var ctx = document.getElementById('staffPerformanceChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: staffIds,
                datasets: statuses.map(function (status, index) {
                    return {
                        label: status,
                        data: staffIds.map(function (staffId) {
                            return staffData[staffId][index];
                        }),
                        backgroundColor: statusColors[index], // Use the color for the specific status
                        borderWidth: 1,
                    };
                }),
            },
            options: {
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        stepSize: 1,
                    },
                },
            },
        });
    }

    var staffIds = @json($staffIds);
    var statuses = @json($statuses);
    var staffData = @json($staffData);

    // Call the function to render the chart
    renderStaffPerformanceChart(staffIds, statuses, staffData);
</script>

</html>