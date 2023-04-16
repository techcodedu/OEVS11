@extends('layouts.frontapp')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">My Applications</h2>
    <div class="row">
        <div class="col-md-6">
            <h4>Course Enrollments</h4>
            <table class="table table-striped table-bordered table-hover">
    <thead class="thead-dark">
        <tr>
            <th>Course Name</th>
            <th>Enrollment Type</th>
            <th>Status</th>
            <th>Training Schedule</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($enrollments as $enrollment)
            <tr>
                <td>{{ $enrollment->course->name }}</td>
                <td>{{ $enrollment->enrollment_type }}</td>
                <td>
                    <span class="badge badge-{{ $enrollment->status === 'enrolled' ? 'success' : 'warning' }}">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </td>
                <td>
                    @if($enrollment->status === 'enrolled' && !empty($enrollment->trainingSchedule))
                        <i class="fas fa-calendar-alt" style="cursor:pointer" onclick="viewTrainingSchedule({{ $enrollment->trainingSchedule->id }}, '{{ $enrollment->scholarship_grant }}')"></i>
                    @else
                        <span>-</span>
                    @endif
                    @if ($enrollment->feedback)
                        <i class="fas fa-comments" style="cursor:pointer" onclick="openFeedbackViewModal({{ $enrollment->id }})" title="Read Feedback"></i>
                    @else
                        <span>No feedback yet</span>
                    @endif
                </td>
                <!-- Update the Course Enrollments Actions -->
                <td>
                    @if ($enrollment->created_at->diffInDays(\Carbon\Carbon::now()) <= 3 && $enrollment->status !== 'enrolled')
                        <a href="{{ route('enrollments.cancel', $enrollment->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this enrollment?')">Cancel Enrollment</a>
                    @elseif ($enrollment->status === 'enrolled')
                        <button class="btn btn-success btn-sm" disabled>Vetted for Scholarship</button>
                    @else
                        <button class="btn btn-danger btn-sm" disabled>Cancel Enrollment</button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No enrollments found.</td>
            </tr>
        @endforelse
    </tbody>
</table>


             <p class="text-muted mt-2">
                <small>* Cancellation of enrollments is only allowed within 3 days of enrollment.</small>
            </p>
        </div>
        <div class="col-md-6">
            <h4>Assessment Applications</h4>
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Application Number</th>
                        <th>Course Name</th>
                        <th>Assessment Title</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $displayed_applications = 0;
                    @endphp

                    @foreach($assessment_applications as $application)
                        @if($application->cancellation_status != 'Cancelled')
                            <tr>
                                <td>{{ $application->application_number }}</td>
                                <td>{{ $application->course->name }}</td>
                                <td>{{ $application->assessment_title }}</td>
                                <td>
                                    <span class="badge badge-{{ $application->status === 'Approved' ? 'success' : ($application->status === 'scheduled' ? 'success' : 'warning') }}">
                                        {{ $application->status }}
                                    </span>
                                    @if($application->status === 'scheduled' && $application->schedule)
                                        <br>
                                        <small>Scheduled on: {{ \Carbon\Carbon::parse($application->schedule->scheduled_date)->format('F j, Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($application->created_at->diffInDays(\Carbon\Carbon::now()) <= 3 && $application->status !== 'scheduled')
                                        <a href="{{ route('assessment_applications.cancel', $application->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this application?')">Cancel Application</a>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled>Cancel Application</button>
                                    @endif
                                </td>
                            </tr>
                            @php
                            $displayed_applications++;
                            @endphp
                        @endif
                    @endforeach

                    @if($displayed_applications === 0)
                        <tr>
                            <td colspan="5" class="text-center">No assessment applications found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <p class="text-muted mt-2">
                <small>* Cancellation of assessment applications is only allowed within 3 days of submission.</small>
            </p>
        </div>
    </div>
</div>
{{-- training schedule --}}
<div class="modal fade" id="trainingScheduleModal" tabindex="-1" role="dialog" aria-labelledby="trainingScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trainingScheduleModalLabel">Training Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Training schedule and scholarship grant details will be populated here via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- view feeback --}}
<!-- Feedback View Modal -->
<div class="modal fade" id="feedbackViewModal" tabindex="-1" role="dialog" aria-labelledby="feedbackViewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackViewModalLabel">Feedback Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="feedbackMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    function viewTrainingSchedule(scheduleId, scholarshipGrant) {
    // Fetch the training schedule details via an AJAX request
    $.get('/get_schedule/' + scheduleId, function(schedule) {
        // Populate the modal with the fetched data
        $('#trainingScheduleModal .modal-body').html(`
            <p><strong>Start Date:</strong> ${schedule.start_date_formatted}</p>
            <p><strong>End Date:</strong> ${schedule.end_date_formatted}</p>
            <p><strong>Scholarship Grant:</strong> ${scholarshipGrant || 'N/A'}</p>
        `);

        // Show the modal
        $('#trainingScheduleModal').modal('show');
    });
}

</script>
<script>
    function openFeedbackViewModal(enrollmentId) {
    $.ajax({
        url: '/student/applications/view-feedback/' + enrollmentId,
        method: 'GET',
        success: function(response) {
            $('#feedbackMessage').text(response);
            $('#feedbackViewModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Error:', textStatus, errorThrown);
        }
    });
}

</script>


@endsection
