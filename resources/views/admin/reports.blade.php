@extends('layouts.app')

<style>
      .chart-card-body {
    height: 300px;
  }
</style>
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Reports') }}</h1>
                    <!-- Add this code somewhere in your blade view to print the content of the $enrollmentCourseData variable -->

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Chart and analytics cards -->
            <div class="row">
                <!-- Chart card -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body chart-card-body">
                            <canvas id="enrollmentStatusChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Line chart card -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body chart-card-body">
                            <canvas id="enrollmentCoursesChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Assessment Remarks Bar Chart Card -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body chart-card-body">
                            <canvas id="assessmentRemarksChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalEnrollments }}</h3>
                            <p>Total Enrollments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalCompleted }}</h3>
                            <p>Completed Enrollments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalInProgress }}</h3>
                            <p>Enrollments In Progress</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-spinner"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Filters -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filters</h3>
                        </div>
                        <form method="GET" action="{{ route('admin.reports') }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status">Enrollment Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">All</option>
                                        <option value="inReview">In Review</option>
                                        <option value="inProgress">In Progress</option>
                                        <option value="enrolled">Enrolled</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="date_from">Scheduled Assessment Date Range</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" name="date_from" id="date_from">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" name="date_to" id="date_to">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="training_date_from">Training Schedule Date Range</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" name="training_date_from" id="training_date_from">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" name="training_date_to" id="training_date_to">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                            </div>
                        </form>
                    </div>

                    {{-- data in the table --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Enrollments Report</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="enrollmentsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Course</th>
                                        <th>Status</th>
                                        <th>Enrollment Type</th>
                                        <th>Scholarship Grant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->id }}</td>
                                            <td>{{ $enrollment->user->name }}</td>
                                            <td>{{ $enrollment->course->title }}</td>
                                            <td>{{ $enrollment->status }}</td>
                                            <td>{{ $enrollment->enrollment_type }}</td>
                                            <td>{{ $enrollment->scholarship_grant }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Enrollments Report</h3>
                            <button class="btn btn-primary" onclick="printTable('enrollmentsTable')">Print</button>
                        </div>
                        <div class="card-footer clearfix">
                    </div>
                      {{-- end of table card --}}
                </div>
                 
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function printTable(tableId) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print</title></head><body>');
            printWindow.document.write('<h1>' + document.title + '</h1>');
            printWindow.document.write(document.getElementById(tableId).outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
    <script>
    // Enrollment Courses Line Chart
    const enrollmentCourseData = @json($enrollmentCourseData);
    const courseLabels = enrollmentCourseData.map(e => e.course);
    const enrollmentCounts = enrollmentCourseData.map(e => e.enrollments);

    const lineCtx = document.getElementById('enrollmentCoursesChart').getContext('2d');
    const enrollmentCoursesChart = new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: courseLabels,
        datasets: [{
            label: 'Enrollments per Course',
            data: enrollmentCounts,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        maintainAspectRatio: false,
        responsive: true
    }
});

</script>
<script>
    // Enrollment Types Pie Chart
    const enrollmentTypeData = @json($enrollmentTypeData);
    const enrollmentTypeLabels = enrollmentTypeData.map(e => e.enrollment_type);
    const enrollmentTypeCounts = enrollmentTypeData.map(e => e.count);

    const pieCtx = document.getElementById('enrollmentStatusChart').getContext('2d');
    const enrollmentStatusChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
           labels: enrollmentTypeLabels,
            datasets: [{
                data: enrollmentTypeCounts,
                backgroundColor: ['#FF6384', '#36A2EB'],
                hoverBackgroundColor: ['#FF6384', '#36A2EB']
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true
        }
    });

    // Student Assessment Remarks Bar Chart
    const assessmentRemarksData = @json($assessmentRemarksData);
    const assessmentRemarksLabels = assessmentRemarksData.map(e => e.remarks);
    const assessmentRemarksCounts = assessmentRemarksData.map(e => e.count);

    const barCtx = document.getElementById('assessmentRemarksChart').getContext('2d');
    const assessmentRemarksChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: assessmentRemarksLabels,
            datasets: [{
                label: 'Student Assessments by Remarks',
                data: assessmentRemarksCounts,
                backgroundColor: ['#42A5F5', '#66BB6A'],
                borderColor: ['#42A5F5', '#66BB6A'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    barPercentage: 0.1, // Controls the width of individual bars (0 to 1)
                    categoryPercentage: 0.5 // Controls the width of the category containing the bars (0 to 1)
                }
            },
            maintainAspectRatio: false,
            responsive: true
        }
    });
</script>



<!-- /.content -->
@endsection


