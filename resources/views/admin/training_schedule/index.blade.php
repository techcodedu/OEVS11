@extends('layouts.app')

<style>
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

.bg-highlight {
        background-color: #ffc107; /* Choose any color you prefer */
    }
</style>

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Scheduling of Training') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="col-md-12 mt-3">
                            <form method="GET" action="" class="shared-filter-form training-schedule-filter-form">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="form-group">
                                        <label for="course_name" class="mr-1 d-inline-block">Course Name</label>
                                        <select name="course_name" id="course_name" class="form-control form-control-sm d-inline-block">
                                            <option value="">All Courses</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->name }}">{{ $course->name }}</option>
                                            @endforeach
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
                                        <label for="scholarship_grant" class="mr-1 d-inline-block">Scholarship Grant</label>
                                        <select name="scholarship_grant" id="scholarship_grant" class="form-control form-control-sm d-inline-block">
                                            <option value="">All Grants</option>
                                            @foreach($enrollments as $enrollment)
                                                <option value="{{ $enrollment->scholarship_grant }}">{{ $enrollment->scholarship_grant }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <form method="POST" action="/save_bulk_schedule" class="bulk-schedule-form">
                            @csrf
                            <div class="col-lg-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>Student Name</th>
                                            <th>Enrollment Type</th>
                                            <th>Course Name</th>
                                            <th>Enrollment Status</th>
                                            <th>Scholarship Grant</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    @php
                                        use Carbon\Carbon;
                                    @endphp
                                    <tbody>
                                        @foreach($enrollments as $enrollment)
                                            @if($enrollment->status === 'enrolled')
                                                @php
                                                    $hasSchedule = $enrollment->trainingSchedule !== null;
                                                @endphp
                                                <tr @if($hasSchedule) class="bg-highlight" @endif>
                                                    <td>
                                                        <input type="checkbox" name="enrollment_id[]" value="{{ $enrollment->id }}" @if($hasSchedule) disabled @endif>
                                                    </td>
                                                    <td>{{ $enrollment->user->name }}</td>
                                                    <td>{{ $enrollment->enrollment_type }}</td>
                                                    <td>{{ $enrollment->course->name }}</td>
                                                    <td>{{ $enrollment->status }}</td>
                                                    <td>{{ $enrollment->scholarship_grant }}</td>
                                                    <td>
                                                        @if($hasSchedule && $enrollment->trainingSchedule->start_date)
                                                            <span class="badge badge-primary">{{ \Carbon\Carbon::parse($enrollment->trainingSchedule->start_date)->format('F j, Y') }}</span>
                                                        @else
                                                            No Schedule
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($hasSchedule && $enrollment->trainingSchedule->end_date)
                                                            <span class="badge badge-primary">{{ \Carbon\Carbon::parse($enrollment->trainingSchedule->end_date)->format('F j, Y') }}</span>
                                                        @else
                                                            No Schedule
                                                        @endif
                                                    @if($hasSchedule && $enrollment->trainingSchedule)
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-warning" onclick="openOverrideModal(this, {{ $enrollment->trainingSchedule->id }})" data-toggle="tooltip" data-placement="top" title="Override">
                                                                <i class="fas fa-check-circle"></i> <!-- Change the icon to the "edit" icon -->
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeSchedule({{ $enrollment->trainingSchedule->id }})" data-toggle="tooltip" data-placement="top" title="Remove Schedule">
                                                                <i class="fas fa-trash"></i> <!-- Keep the "trash" icon for "Remove Schedule" -->
                                                            </button>
                                                        </td>
                                                    @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="card-footer clearfix"></div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Start Date:</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="end_date">End Date:</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label> <!-- Empty label for alignment -->
                                            <button type="submit" class="btn btn-sm btn-primary d-block" onclick="return confirm('Are you sure you want to save the schedule for the selected students? After saving this overrding for each student schedule will be one by one');">Save Schedule for Selected Students</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
            <!-- /.row -->
            </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- Modal -->
   <div class="modal fade" id="overrideModal" tabindex="-1" role="dialog" aria-labelledby="overrideModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="overrideModalLabel">Override Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  method="POST" id="overrideForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="new_start_date">New Start Date:</label>
                        <input type="date" id="new_start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new_end_date">New End Date:</label>
                        <input type="date" id="new_end_date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- /.content -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Training Schedule table filtering
        document.querySelector('.training-schedule-filter-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const courseNameFilter = document.querySelector('#course_name').value;
            const enrollmentTypeFilter = document.querySelector('#enrollment_type').value;
            const scholarshipGrantFilter = document.querySelector('#scholarship_grant').value;
            const table = document.querySelector('.table');
            const rows = table.querySelectorAll('tbody tr');
            let hasVisibleRows = false;

            rows.forEach(row => {
                const courseNameCell = row.querySelector('td:nth-child(4)');
                const enrollmentTypeCell = row.querySelector('td:nth-child(3)');
                const scholarshipGrantCell = row.querySelector('td:nth-child(6)');

                if (courseNameCell && enrollmentTypeCell && scholarshipGrantCell) {
                    const courseName = courseNameCell.textContent.trim();
                    const enrollmentType = enrollmentTypeCell.textContent.trim();
                    const scholarshipGrant = scholarshipGrantCell.textContent.trim();

                    if ((courseNameFilter === '' || courseNameFilter === courseName) &&
                        (enrollmentTypeFilter === '' || enrollmentTypeFilter === enrollmentType) &&
                        (scholarshipGrantFilter === '' || scholarshipGrantFilter === scholarshipGrant)) {
                        row.style.display = '';
                        hasVisibleRows = true;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Show or hide a "No records found" row if necessary
            const noRecordsRow = document.querySelector('#no-records-row');
            if (hasVisibleRows) {
                if (noRecordsRow) noRecordsRow.style.display = 'none';
            } else {
                if (!noRecordsRow) {
                    const newRow = document.createElement('tr');
                    newRow.id = 'no-records-row';
                    newRow.innerHTML = '<td colspan="7" class="text-center">No records found</td>';
                    table.querySelector('tbody').appendChild(newRow);
                } else {
                    noRecordsRow.style.display = '';
                }
            }
        });
    });

    </script>
    <script>
    let currentForm;

    function openOverrideModal(button, scheduleId) {
    currentForm = button.closest('form');
    document.getElementById('overrideForm').action = '/update_schedule/' + scheduleId;
        $('#overrideModal').modal('show');
    }

    </script>
    <script>
        document.getElementById('checkAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="enrollment_id[]"]');
            for (const checkbox of checkboxes) {
                if (!checkbox.disabled) {
                    checkbox.checked = this.checked;
                }
            }
        });

    </script>
    <script>
        function removeSchedule(scheduleId) {
            if (confirm('Are you sure you want to remove the schedule for this student?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/remove_schedule/${scheduleId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfToken);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

    </script>

@endsection