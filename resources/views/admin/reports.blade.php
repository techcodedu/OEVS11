@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Reports') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
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
                    </div>
                      {{-- end of table card --}} 
                        {{-- Analytics Section --}}
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
                    {{-- End of Analytics Section --}}

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
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

<!-- /.content -->
@endsection

                                           
