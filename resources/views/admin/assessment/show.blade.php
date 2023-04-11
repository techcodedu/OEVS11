<!-- admin/assessment/show.blade.php -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Assessment Application Details</h3>
    </div>
    <div class="card-body">
        <h4>Information</h4>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Application ID</th>
                    <td>{{ $assessment->id }}</td>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <td>{{ $assessment->user->name }}</td>
                </tr>
                <tr>
                    <th>Course Name</th>
                    <td>{{ $assessment->course->name }}</td>
                </tr>
                <tr>
                    <th>Application Type</th>
                    <td>{{ $assessment->application_type }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $assessment->status }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ $assessment->gender }}</td>
                </tr>
                <tr>
                    <th>Civil Status</th>
                    <td>{{ $assessment->civil_status }}</td>
                </tr>
                <tr>
                    <th>Cancellation Status</th>
                    <td>{{$assessment->cancellation_status }}</td>
                </tr>
                <tr>
                    <th>Date of Application</th>
                    <td>{{$assessment->created_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
