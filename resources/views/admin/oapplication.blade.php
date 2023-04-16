@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
.modal-body {
  max-height: 400px;
  overflow-y: scroll;
}
.green-option {
    background-color: #28a745;
    color: white;
}
.custom-filter-form {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 0.5rem;
}

.custom-filter-form .form-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0; /* Reset the default margin-bottom of form-group */
}
.shared-filter-form {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 0.5rem;
}

.shared-filter-form .form-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0; /* Reset the default margin-bottom of form-group */
}

.shared-filter-form .form-group label {
    margin-bottom: 0;
    margin-right: 5px; /* Add margin-right to the label */
}

.shared-filter-form .form-group select {
    width: auto;
    margin-right: 15px; /* Add margin-right to the select */
}
.badge {
    font-size: 0.8rem;
}

td i {
    font-size: 1.2rem;
    color: #007bff;
    cursor: pointer;
}
.custom-card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 2px solid #3182ce;
    padding-bottom: 0.5rem;
    display: inline-block;
    margin-bottom: 1rem;
}

</style>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Application') }}</h1>
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
                            <div class="row">
                                <div class="col-md-4">
                                    <h3 class="card-title custom-card-title">Enrollment</h3>
                                </div>

                                <div class="col-md-8">
                                    <!-- filterform -->
                                    <form method="GET" action="" class="shared-filter-form enrollment-filter-form">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <div class="form-group">
                                                <label for="enrollment_course_name" class="mr-1 d-inline-block">Course Name</label>
                                                <select name="enrollment_course_name" id="enrollment_course_name" class="form-control form-control-sm d-inline-block">
                                                    <option value="">All Courses</option>
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->name }}">{{ $course->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="enrollment_status" class="mr-1 d-inline-block">Status</label>
                                                <select name="enrollment_status" id="enrollment_status" class="form-control form-control-sm d-inline-block">
                                                    <option value="">All Statuses</option>
                                                    <option value="inReview">In Review</option>
                                                    <option value="inProgress">In Progress</option>
                                                    <option value="enrolled">Enrolled</option>
                                                </select>
                                            </div>
                                           <div class="form-group">
                                                <label for="enrollment_type" class="mr-1 d-inline-block">Enrollment Type</label>
                                                <select name="enrollment_type" id="enrollment_type" class="form-control form-control-sm d-inline-block">
                                                    <option value="">All Types</option>
                                                    <option value="regular_training">Regular Training</option>
                                                    <option value="scholarship">Scholarship</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="enrollment-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Application ID</th>
                                        <th>Full Name</th>
                                        <th>Course Name</th>
                                        <th>Enrollment Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                    <tr>
                                        <td><span class="badge badge-primary">{{ $enrollment->id }}</span></td>
                                        <td>{{ $enrollment->personalInformation ? $enrollment->personalInformation->fullname : 'N/A' }}</td>
                                        <td>{{ $enrollment->course->name }}</td>
                                        <td><span class="badge badge-info">{{ $enrollment->enrollment_type }}</span></td>
                                        <td>
                                            <select name="status" class="form-control status-select" data-enrollment-id="{{ $enrollment->id }}" data-enrollment-type="{{ $enrollment->enrollment_type }}">
                                                <option class="status-option" value="inReview" {{ $enrollment->status === 'inReview' ? 'selected' : '' }}>In Review</option>
                                                <option class="status-option" value="inProgress" {{ $enrollment->status === 'inProgress' ? 'selected' : '' }}>In Progress</option>
                                                <option class="status-option" value="enrolled" {{ $enrollment->status === 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                            </select>
                                        </td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#enrollmentModal" data-id="{{ $enrollment->id }}"><i class="fas fa-info-circle" title="View Student Inforamtion and Credentials"></i></a>
                                            {{-- feedback --}}
                                            <a href="#" data-toggle="modal" data-target="#feedbackModal" data-id="{{ $enrollment->id }}"><i class="fas fa-comment"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr id="enrollment-no-records-row" style="display: none;">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer clearfix">
                            {{ $enrollments->links() }}
                        </div>
                    </div>
                    {{-- assessment --}}
                   <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title custom-card-title">Assessment Application</h3>
                                </div>
                                <div class="col-md-6">
                                    <!-- filterform -->
                                    <form method="GET" action="" class="shared-filter-form custom-filter-form" id="application-filter-form">
                                        <div class="form-group">
                                            <label for="course_name" class="mr-1">Course Name</label>
                                            <select name="course_name" id="course_name" class="form-control form-control-sm">
                                                <option value="">All Courses</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->name }}" {{ request('course_name') === $course->name ? 'selected' : '' }}>{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="mr-1">Status</label>
                                            <select name="status" id="status" class="form-control form-control-sm">
                                                <option value="">All Statuses</option>
                                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                               
                                            </select>
                                        </div>
                                        <div class="form-group"> <!-- Add this wrapping div -->
                                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- other card content -->
                        <div class="card-body">
                            <table id="application-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Application Number</th>
                                        <th>Full Name</th>
                                        <th>Course Name</th>
                                        <th>Application Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessments as $assessment)
                                    <tr>
                                        <td><span class="badge badge-primary">{{ $assessment->application_number }}</span></td>
                                        <td>{{ $assessment->user->name }}</td>
                                        <td>{{ $assessment->course->name }}</td>
                                        <td><span class="badge badge-info">{{ $assessment->application_type }}</span></td>
                                        <td>
                                            <select name="status" class="form-control assessment-status-select" data-assessment-id="{{ $assessment->id }}">
                                                <option value="pending" {{ $assessment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="scheduled" {{ $assessment->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            </select>
                                        </td>
                                            <div class="form-group scheduled-date-input" style="display: none;">
                                                <input type="date" name="scheduled_date" class="form-control" data-assessment-id="{{ $assessment->id }}">
                                            </div>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#assessmentModal" data-id="{{ $assessment->id }}"><i class="fas fa-info-circle"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr id="no-records-row" style="display: none;">
                                        <td colspan="7" class="text-center">No records founds</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            {{ $assessments->links() }}
                        </div>

                    </div>

                    {{-- end of assessment --}}

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    {{-- modal for feedback --}}
    <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Submit Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <form action="{{ route('enrollment.storeFeedback') }}" method="POST">

                    @csrf
                    <input type="hidden" name="enrollment_id" id="enrollment_id" value="0">
                    <div class="modal-body">
                        <textarea name="feedback" id="feedback" class="form-control" rows="5" placeholder="Write your feedback here..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal for enrollment-->
    <div class="modal fade" id="enrollmentModal" tabindex="-1" role="dialog" aria-labelledby="enrollmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollmentModalLabel">Enrollment Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" style="max-height: 500px; overflow-y: auto;">
                    <div id="enrollmentDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

   {{-- modal for  --}}
   <!-- Assessment Modal -->
    <div class="modal fade" id="assessmentModal" tabindex="-1" role="dialog" aria-labelledby="assessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assessmentModalLabel">Assessment Application Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 500px;">
                    <div id="assessmentDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal for confirmation -->
    <div class="modal" tabindex="-1" role="dialog" id="confirmationModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to update the status of this assessment application?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdateStatus">Yes, Update Status</button>
                </div>
            </div>
        </div>
    </div>
   <!-- custom modal for confirmation dialog -->
   <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="statusModalBody">
                    <!-- Dynamic content will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdate">Confirm Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Enrollment Status Modal -->
    <div class="modal fade" id="enrollmentStatusModal" tabindex="-1" role="dialog" aria-labelledby="enrollmentStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollmentStatusModalLabel">Confirm Status Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="enrollmentStatusModalBody">
                    Are you sure you want to update the enrollment status?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmEnrollmentUpdate">Confirm Update</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
          $(function () {
            // Event listener for opening the enrollment modal window
            $(document).on('click', 'a[data-target="#enrollmentModal"]', function (e) {
                e.preventDefault();

                // Get the enrollment ID from the data-id attribute of the clicked element
                var enrollmentId = $(this).data('id');
                console.log("Making AJAX request for enrollment " + enrollmentId);
                $.ajax({
                    url: "{{ route('admin.enrollment.show', ':id') }}".replace(':id', enrollmentId),
                    type: 'GET',
                    dataType: 'html',
                    success: function (response) {
                        console.log("Received response: ", response);
                        // Update the modal content with the enrollment details
                        $('#enrollmentModal .modal-body').html(response);
                        // Show the modal
                        $('#enrollmentModal').modal('show');
                    },
                    error: function (response) {
                        console.log("Error:", response);
                    }
                });
            });

            // Event listener for updating the enrollment status
            $('.status-select').on('change', function () {
                var enrollmentId = $(this).data('enrollment-id');
                var enrollmentType = $(this).data('enrollment-type');
                var newStatus = $(this).val();
                var enrollmentSelect = $(this); // Save the select element for later use

                if (enrollmentType === 'regular_training' && newStatus === 'enrolled') {
                    $('#enrollmentStatusModalLabel').text('Confirm Registration Payment');
                    $('#enrollmentStatusModalBody').html('Has the student paid the registration? <br>' +
                        '<input type="radio" name="registration_is_paid" value="1"> Yes<br>' +
                        '<input type="radio" name="registration_is_paid" value="0"> No');
                }
                else if (newStatus === 'enrolled') {
                    var scholarshipSelect = '<select name="scholarship" class="form-control">' +
                        '<option value="PESFA">PESFA</option>' +
                        '<option value="STEP">STEP</option>' +
                        '<option value="TWSP">TWSP</option>' +
                        '<option value="UAQTEA">UAQTEA</option>' +
                        '</select>';
                    $('#enrollmentStatusModalLabel').text('Select Scholarship Type');
                    $('#enrollmentStatusModalBody').html('Please select the type of scholarship: <br>' + scholarshipSelect);
                } else {
                    $('#enrollmentStatusModalLabel').text('Confirm Status Update');
                    $('#enrollmentStatusModalBody').html('Are you sure you want to update the status to <strong>' + newStatus + '</strong>?');
                }

                $('#enrollmentStatusModal').modal('show');
                // Set the event listener for the "Confirm Update" button
                $('#confirmEnrollmentUpdate').off('click').on('click', function () {
                var selectedScholarship = newStatus === 'enrolled' ? $('select[name="scholarship"]').val() : null;
                var registrationIsPaid = $('input[name="registration_is_paid"]:checked').val();
                var forceUpdateScholarship = false;
                if (enrollmentType === 'regular_training' && newStatus === 'enrolled') {
                        registrationIsPaid = $('input[name="registration_is_paid"]:checked').val();
                        if (registrationIsPaid === '0') {
                            newStatus = 'inReview';
                        }
                    } else {
                        registrationIsPaid = null;
                    }
                if (newStatus === 'enrolled') {
                    // Check for an existing scholarship before calling updateEnrollment
                    $.ajax({
                        url: "{{ route('enrollments.checkScholarship', 'ENROLLMENT_ID') }}".replace('ENROLLMENT_ID', enrollmentId),
                        type: 'GET',
                        success: function(response) {
                            if (response.has_scholarship) {
                                if (confirm('The user has already been assigned a scholarship grant. Do you want to update it?')) {
                                    forceUpdateScholarship = true;
                                }
                            }
                            updateEnrollment(enrollmentId, newStatus, selectedScholarship, enrollmentSelect, forceUpdateScholarship, registrationIsPaid);
                        },
                        error: function(response) {
                            console.log("Error checking scholarship:", response);
                        }
                    });
                } else {
                    // Call updateEnrollment directly if the new status is not "enrolled"
                    updateEnrollment(enrollmentId, newStatus, selectedScholarship, enrollmentSelect, forceUpdateScholarship, registrationIsPaid);
                }
            });


            });
            function updateEnrollment(enrollmentId, newStatus, selectedScholarship, enrollmentSelect, forceUpdateScholarship,registrationIsPaid) {
                $.ajax({
                    url: '/admin/enrollments/updateStatus',
                    type: 'PUT',
                    data: {
                        enrollment: enrollmentId,
                        registration_is_paid: registrationIsPaid,
                        status: newStatus,
                        scholarship_grant: selectedScholarship,
                        force_update_scholarship: forceUpdateScholarship === true,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.already_exists) {
                            console.log("Already exists condition triggered");
                            // Check if scholarship_grant field has a value before prompting for update
                            if (scholarship_grant !== '' && confirm('The user has already been assigned a scholarship grant. Do you want to update it?')) {
                            // Make another AJAX call to update the scholarship grant
                            forceUpdateScholarship = true;
                            updateEnrollment(enrollmentId, newStatus, selectedScholarship, enrollmentSelect, forceUpdateScholarship);
                            } else {
                            console.log("Update cancelled by user.");
                            }
                        } else {
                            console.log("Enrollment status updated successfully.");
                            $('#messageText').text('Enrollment status updated successfully.');
                            $('#messageModal').modal('show');
                            // Add or remove the 'green-option' class based on the new status
                            if (newStatus === 'enrolled') {
                            enrollmentSelect.addClass('green-option');
                            } else {
                            enrollmentSelect.removeClass('green-option');
                            }
                        }
                    },
                    error: function (response) {
                    console.log("Error updating enrollment status:", response);
                    var errorMessage = 'Error updating enrollment status.';
                    if (response.status === 400) {
                        errorMessage = response.responseJSON.error;
                    }
                    $('#messageText').text(errorMessage);
                    $('#messageModal').modal('show');
                    // Revert the status change in the select element
                    enrollmentSelect.val(enrollmentSelect.find('option[selected]').val());
                }
                });

                $('#enrollmentStatusModal').on('hidden.bs.modal', function () {
                    if ($('#confirmUpdateModal').hasClass('show')) {
                        $('body').addClass('modal-open');
                    }
                }).modal('hide');
            }
  
    });
    </script>
    <script>
    $(document).ready(function() {
        // Event listener for opening the assessment application modal window
        $(document).on('click', 'a[data-target="#assessmentModal"]', function(e) {
            e.preventDefault();

            // Get the assessment ID from the data-id attribute of the clicked element
            var assessmentId = $(this).data('id');
            console.log("Making AJAX request for assessment " + assessmentId);
            $.ajax({
                url: "{{ route('admin.assessment.show', ':id') }}".replace(':id', assessmentId),
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    console.log("Received response: ", response);
                    // Update the modal content with the assessment details
                    $('#assessmentModal .modal-body').html(response);
                    // Show the modal
                    $('#assessmentModal').modal('show');
                },
                error: function(response) {
                    console.log("Error:", response);
                }
            });
        });

    // Event listener for updating the assessment application status
    $('.assessment-status-select').on('change', function() {
        var assessmentId = $(this).data('assessment-id');
        var newStatus = $(this).val();
        var userName = $(this).closest('tr').find('td:nth-child(2)').text();
        var courseName = $(this).closest('tr').find('td:nth-child(3)').text();
        var applicationNumber = $(this).closest('tr').find('td:nth-child(1)').text();

        if (newStatus === 'scheduled') {
            $('#statusModalLabel').text('Schedule Assessment');
            $('#statusModalBody').html(
                '<p>Please select a date for <strong>' + userName + '</strong>\'s assessment for the <strong>' + courseName + '</strong> course (Application No: <strong>' + applicationNumber + '</strong>).</p>' +
                '<div class="form-group">' +
                '<label for="scheduledDate">Scheduled Date:</label>' +
                '<input type="date" class="form-control" id="scheduledDate">' +
                '</div>'
            );
        } else {
            $('#statusModalLabel').text('Confirm Status Update');
            $('#statusModalBody').html('Are you sure you want to update the status to <strong>' + newStatus + '</strong>?');
        }

        $('#statusModal').modal('show');

        // Set the event listener for the "Confirm Update" button
        $('#confirmUpdate').off('click').on('click', function() {
            var scheduledDate = null;
            if (newStatus === 'scheduled') {
                scheduledDate = $('#scheduledDate').val();
            }

            // Check if there's an existing schedule before calling updateAssessmentStatus
            $.ajax({
               url: "{{ route('assessments.checkSchedule', 'ASSESSMENT_ID') }}".replace('ASSESSMENT_ID', assessmentId),
                type: 'GET',
                success: function(response) {
                    if (response.has_schedule && newStatus === 'scheduled') {
                        if (confirm('A schedule is already set for this user\'s assessment. Would you like to update the schedule?')) {
                            updateAssessmentStatus(assessmentId, newStatus, scheduledDate);
                        }
                    } else {
                        updateAssessmentStatus(assessmentId, newStatus, scheduledDate);
                    }
                },
                error: function(response) {
                    console.log("Error checking schedule:", response);
                }
            });
        });

    });

    function updateAssessmentStatus(assessmentId, newStatus, scheduledDate = null) {
            console.log("Updating assessment application status for assessment " + assessmentId + " to " + newStatus);
            $.ajax({
                url: "{{ route('admin.assessments.updateStatus') }}",
                type: 'POST',
                data: {
                    _method: 'PUT',
                    assessment: assessmentId,
                    status: newStatus,
                    scheduled_date: scheduledDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log("Assessment application status updated successfully.");
                    $('#messageText').text('Assessment application status updated successfully.');
                    $('#messageModal').modal('show');
                    $('#statusModal').modal('hide');
                    location.reload(); // Add this line to reload the page and update the displayed status
                },
                error: function(response) {
                    console.log("Error updating assessment application status:", response);
                    $('#messageText').text('Error updating assessment application status.');
                    $('#messageModal').modal('show');
                }
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('#application-filter-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const courseNameFilter = document.querySelector('#course_name').value;
            const statusFilter = document.querySelector('#status').value;
            const table = document.querySelector('#application-table');
            const rows = table.querySelectorAll('tbody tr:not(#no-records-row)'); // Exclude the 'No records found' row from filtering
            const noRecordsRow = document.querySelector('#no-records-row');
            let hasVisibleRows = false;

            rows.forEach(row => {
                const courseName = row.querySelector('td:nth-child(3)').textContent;
                const status = row.querySelector('td:nth-child(5) select').value;

                if ((courseNameFilter === '' || courseNameFilter === courseName) &&
                    (statusFilter === '' || statusFilter === status)) {
                    row.style.display = '';
                    hasVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            });

            if (hasVisibleRows) {
                noRecordsRow.style.display = 'none';
            } else {
                noRecordsRow.style.display = '';
            }
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Enrollment table filtering
    document.querySelector('.enrollment-filter-form').addEventListener('submit', function (event) {
        event.preventDefault();

        const courseNameFilter = document.querySelector('#enrollment_course_name').value;
        const statusFilter = document.querySelector('#enrollment_status').value;
        const enrollmentTypeFilter = document.querySelector('#enrollment_type').value;
        const table = document.querySelector('#enrollment-table');
        const rows = table.querySelectorAll('tbody tr:not(#enrollment-no-records-row)');
        const noRecordsRow = document.querySelector('#enrollment-no-records-row');
        let hasVisibleRows = false;

        rows.forEach(row => {
            const courseName = row.querySelector('td:nth-child(3)').textContent.trim();
            const status = row.querySelector('td:nth-child(5) select').value;
            const enrollmentType = row.querySelector('td:nth-child(4)').textContent.trim();

            console.log('Filters: ' + enrollmentTypeFilter);
            console.log('Row values:', courseName, status, enrollmentType);

            if ((courseNameFilter === '' || courseNameFilter === courseName) &&
                (statusFilter === '' || statusFilter === status) &&
                (enrollmentTypeFilter === '' || enrollmentTypeFilter === enrollmentType)) {
                row.style.display = '';
                hasVisibleRows = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (hasVisibleRows) {
            noRecordsRow.style.display = 'none';
        } else {
            noRecordsRow.style.display = '';
        }
    });
});

</script>
<script>
function openFeedbackModal(enrollmentId) {
    $('#enrollment_id').val(enrollmentId);
    $('#feedbackModal').modal('show');
}

$(document).ready(function() {
    $('#feedbackModal form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var enrollmentId = $('#enrollment_id').val();
        var formData = form.serialize() + '&enrollment_id=' + enrollmentId;
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: formData,
            success: function(response) {
                console.log('Success:', response);
                $('#feedbackModal').modal('hide');
                alert('Feedback submitted successfully.'); // Display the alert confirmation
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error: ' + textStatus + ' - ' + errorThrown);
                console.log('Error:', errorThrown, '- jqXHR:', jqXHR);
                console.log('Response:', jqXHR.responseJSON);
            }
        });
    });

    $('#feedbackModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var enrollmentId = button.data('id');
        $('#enrollment_id').val(enrollmentId);
    });
});

</script>

@endsection
