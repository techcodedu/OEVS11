@extends('layouts.app')
<style>
    .badge-remarks {
    display: inline-flex;
    align-items: center;
    font-size: 1rem;
    padding: 0.25em 0.5em;
    border-radius: 0.25rem;
    }

    .badge-remarks .fas {
        margin-right: 0.5em;
    }

    .badge-remarks .fa-check-circle {
        color: #28a745; /* You can change this color to match your theme */
    }

    .badge-remarks .fa-times-circle {
        color: #dc3545; /* You can change this color to match your theme */
    }

    .badge-remarks .fa-exclamation-circle {
        color: #6c757d; /* You can change this color to match your theme */
    }
    .remarks-dropdown {
    width: 150px;
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


</style>
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Assessments') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="col-md-12 mt-3">
                            {{-- filter section --}}
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
                        <div class="card-body">
                            <form action="{{ route('student_assessments.store') }}" method="POST" id="assessment-form">
                                @csrf
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Course</th>
                                            <th>Enrollment Type</th>
                                            <th>End of Training</th>
                                            <th>Schedule Date</th>
                                            <th>Actions</th>
                                            <th style="text-align:center">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr data-enrollment-id="{{ $student->id }}" data-enrollment-type="{{ $student->enrollment_type }}" data-scholarship-grant="{{ $student->scholarship_grant }}">
                                                <td>
                                                   <input type="checkbox" name="schedule[{{ $loop->index }}][selected]" value="1" {{ optional($student->studentAssessment)->schedule_date ? 'disabled' : '' }}>
                                                    <input type="hidden" name="schedule[{{ $loop->index }}][enrollment_id]" value="{{ $student->id }}">
                                                </td>
                                                <td>{{ $student->id }}</td>
                                                <td class="student-name">{{ $student->user->name }}</td>
                                                <td >{{ $student->user->email }}</td>
                                                <td class="course-name">{{ $student->course->name }}</td>
                                                <td>{{ $student->enrollment_type }}</td>
                                                <td>{{ optional($student->trainingSchedule)->end_date }}</td>
                                               <td>
                                                    <span class="schedule-date-display">
                                                        @if(optional($student->studentAssessment)->schedule_date)
                                                            {{ \Carbon\Carbon::parse(old('schedule.'.$loop->index.'.schedule_date', optional($student->studentAssessment)->schedule_date))->format('F j, Y') }}
                                                        @else
                                                            Not scheduled
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="d-flex align-items-center">
                                                    <input type="hidden" name="schedule[{{ $loop->index }}][schedule_date]" value="{{ old('schedule.'.$loop->index.'.schedule_date', optional($student->assessment)->schedule_date) }}">
                                                    <select name="schedule[{{ $loop->index }}][remarks]" id="remarks-{{ $student->id }}" data-enrollment-id="{{ $student->id }}" data-student-name="{{ $student->user->name }}" data-course-name="{{ $student->course->name }}" class="form-control remarks-dropdown mr-2" {{ optional($student->studentAssessment)->schedule_date ? '' : 'disabled' }} style="width: 115px;">
                                                        <option value="">Select</option>
                                                        <option value="Competent">Competent</option>
                                                        <option value="Not Competent">Not Competent</option>
                                                    </select>
                                                    <button type="button" class="btn btn-sm btn-outline-primary update-schedule-date mr-2" title="Update Schedule Date"><i class="fas fa-edit"></i></button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-schedule-date" title="Remove Schedule Date"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                                <td>
                                                    <span id="badge-{{ $student->id }}" class="badge badge-remarks">
                                                        <i class="{{ optional($student->studentAssessment)->remarks === 'Competent' ? 'fas fa-check-circle' : (optional($student->studentAssessment)->remarks === 'Not Competent' ? 'fas fa-times-circle' : 'fas fa-exclamation-circle') }}"></i>
                                                        <span id="text-{{ $student->id }}">{{ optional($student->studentAssessment)->remarks ? optional($student->studentAssessment)->remarks : 'Remarks yet' }}</span>
                                                    </span>  
                                                </td>    
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot style="display:none;">
                                        <tr>
                                            <td colspan="10" class="text-center">No records found</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                             <label for="assessment_date">Assessment Date:</label>
                                             <input type="date" name="assessment_date" id="assessment_date" class="form-control">
                                        </div>
                                    </div>
                                 </div>
                                 <button type="submit" class="btn btn-primary">Save Assessment Schedules</button>
                            </form>
                        </div>
                        <div class="card-footer clearfix">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal for updating the remarks --}}
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Assessment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                The student <strong id="studentName"></strong> completely passed the assessment for <strong id="courseName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Confirm</button>
            </div>
            </div>
        </div>
    </div>
    {{-- modal for update schedule --}}
    <div class="modal" tabindex="-1" id="updateScheduleModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Schedule Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="date" class="form-control" id="newScheduleDateInput">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveNewScheduleDate">Save</button>
            </div>
            </div>
        </div>
        </div>
        {{-- confirmation box --}}
        <div class="modal fade" id="confirmUpdateModal" tabindex="-1" role="dialog" aria-labelledby="confirmUpdateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUpdateModalLabel">Confirm Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="confirmUpdateModalBody">
                    <!-- The confirmation message will be added dynamically here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdate">Update</button>
                </div>
                </div>
            </div>
            </div>

   <!-- ... -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('checkAll').addEventListener('change', function () {
            let isChecked = this.checked;
            document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                // Check if the checkbox is not disabled
                if (!checkbox.disabled) {
                    checkbox.checked = isChecked;
                }
            });
        });


    </script>
    <script>
        document.querySelectorAll('input[type="checkbox"]:not(#checkAll)').forEach(function (checkbox, index) {
            checkbox.addEventListener('change', function () {
                let singleRowForm = this.closest('tr');
                let scheduleDateInput = singleRowForm.querySelector('input[name="schedule[' + index + '][schedule_date]"]');
                let scheduleDateDisplay = singleRowForm.querySelector('.schedule-date-display');
                let remarksDropdown = singleRowForm.querySelector('select[name="schedule[' + index + '][remarks]"]');

                if (this.checked) {
                    remarksDropdown.disabled = !scheduleDateInput.value;
                } else {
                    remarksDropdown.disabled = true;
                }
            });
        });

       document.getElementById('assessment_date').addEventListener('change', function () {
        let remarksDropdowns = document.querySelectorAll('select[name^="schedule"]');
        let scheduleDateInputs = document.querySelectorAll('input[name^="schedule"][name$="[schedule_date]"]');
        let checkboxes = document.querySelectorAll('input[type="checkbox"]:not(#checkAll)');

        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                scheduleDateInputs[i].value = this.value;
            }
            remarksDropdowns[i].disabled = !scheduleDateInputs[i].value;
            checkboxes[i].checked = !!scheduleDateInputs[i].value;
        }
    });
        

    </script>
    <script>
        let currentEnrollmentId;

        document.querySelectorAll('.update-schedule-date').forEach(function (button) {
            button.addEventListener('click', function () {
                let row = this.closest('tr');
                let scheduleDateDisplay = row.querySelector('.schedule-date-display');
                let scheduleDateInput = row.querySelector('input[name^="schedule"][name$="[schedule_date]"]');

                currentEnrollmentId = row.getAttribute('data-enrollment-id');

                let currentDate = scheduleDateDisplay.textContent.trim();
                document.getElementById('newScheduleDateInput').value = currentDate;

                $('#updateScheduleModal').modal('show');
            });
        });

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', options);
        }


        document.getElementById('saveNewScheduleDate').addEventListener('click', function () {
            let newScheduleDate = document.getElementById('newScheduleDateInput').value;

            if (newScheduleDate) {
                let row = document.querySelector(`tr[data-enrollment-id="${currentEnrollmentId}"]`);
                let studentName = row.querySelector('.student-name').textContent.trim();
                let courseName = row.querySelector('.course-name').textContent.trim();

                let formattedDate = formatDate(newScheduleDate);

                document.getElementById('confirmUpdateModalBody').innerHTML = `You are updating the schedule of assessment for ${studentName} in this qualification ${courseName} on ${formattedDate}.`;

                $('#updateScheduleModal').modal('hide');
                $('#confirmUpdateModal').modal('show');
            }
        });

        document.getElementById('confirmUpdate').addEventListener('click', function () {
            let newScheduleDate = document.getElementById('newScheduleDateInput').value;

            if (newScheduleDate) {
                fetch('{{ route("student_assessments.update_schedule_date") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ enrollment_id: currentEnrollmentId, schedule_date: newScheduleDate }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            console.log(data.message);

                            let row = document.querySelector(`tr[data-enrollment-id="${currentEnrollmentId}"]`);
                            let scheduleDateDisplay = row.querySelector('.schedule-date-display');
                            let scheduleDateInput = row.querySelector('input[name^="schedule"][name$="[schedule_date]"]');
                            let remarksDropdown = row.querySelector('.remarks-dropdown');

                            scheduleDateDisplay.textContent = newScheduleDate;
                            scheduleDateInput.value = newScheduleDate;
                            remarksDropdown.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error updating schedule date:', error);
                    });

                $('#confirmUpdateModal').modal('hide');
            }
        });

    </script>
    <script>
    
    // Add the event listener for the remove-schedule-date button

    // Add the event listener for the remove-schedule-date button
    document.querySelectorAll('.remove-schedule-date').forEach(function (button) {
        button.addEventListener('click', function () {
            let row = this.closest('tr');
            let enrollmentId = row.getAttribute('data-enrollment-id');

            fetch('{{ route("student_assessments.remove_schedule_date") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ enrollment_id: enrollmentId }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        console.log(data.message);

                        let scheduleDateDisplay = row.querySelector('.schedule-date-display');
                        let scheduleDateInput = row.querySelector('input[name^="schedule"][name$="[schedule_date]"]');
                        let remarksDropdown = row.querySelector('.remarks-dropdown');
                        let enrollmentCheckbox = row.querySelector('input[type="checkbox"]'); // Modify this line

                        scheduleDateDisplay.textContent = 'Not Scheduled';
                        scheduleDateInput.value = '';
                        remarksDropdown.disabled = true;
                        enrollmentCheckbox.disabled = false; // Add this line
                    }
                })
                .catch(error => {
                    console.error('Error removing schedule date:', error);
                });
        });
    });




    </script>
    <!-- ... -->
    <script>
    document.querySelectorAll('select[name^="schedule"]').forEach(function(dropdown) {
        dropdown.addEventListener('change', function() {
            let enrollment_id = this.getAttribute('data-enrollment-id');
            let remarks = this.value;
            let studentName = this.getAttribute('data-student-name');
            let courseName = this.getAttribute('data-course-name');

            let message = remarks === 'Competent'
                ? `The student ${studentName} completely passed the assessment for ${courseName}?`
                : `The student ${studentName} failed the assessment for ${courseName}?`;

            document.querySelector('#confirmationModal .modal-body').textContent = message;
            $('#confirmationModal').modal('show');

            document.getElementById('confirmButton').onclick = function() {
                $('#confirmationModal').modal('hide');

                fetch('{{ route("student_assessments.update_remarks") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({enrollment_id: enrollment_id, remarks: remarks}),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        console.log(data.message);

                        
                        // Inside the .then(data => { ... }) block after the AJAX call
        
                        let badge = document.getElementById('badge-' + enrollment_id);
                        let icon = badge.querySelector('.fas');
                        icon.classList.remove('fa-check-circle', 'fa-times-circle', 'fa-exclamation-circle');
                        icon.classList.add(remarks === 'Competent' ? 'fa-check-circle' : (remarks === 'Not Competent' ? 'fa-times-circle' : 'fa-exclamation-circle'));

                        // Update the text content of the span element
                        let textSpan = document.getElementById('text-' + enrollment_id);
                        textSpan.textContent = remarks ? remarks : 'Remarks yet';



                    }
                })
                .catch(error => {
                    console.error('Error updating remarks:', error);
                });
            }
        });
    });


    </script>
    <script>
    $(document).ready(function () {
        function applyFilters() {
            const courseName = $("#course_name").val();
            const enrollmentType = $("#enrollment_type").val();
            const scholarshipGrant = $("#scholarship_grant").val();
            let visibleRowCount = 0;

            $("tbody tr").each(function () {
                const row = $(this);
                const rowCourseName = row.find(".course-name").text();
                const rowEnrollmentType = row.attr("data-enrollment-type");
                const rowScholarshipGrant = row.attr("data-scholarship-grant");

                const showRow = (!courseName || rowCourseName === courseName) &&
                    (!enrollmentType || rowEnrollmentType === enrollmentType) &&
                    (!scholarshipGrant || rowScholarshipGrant === scholarshipGrant);

                if (showRow) {
                    row.show();
                    visibleRowCount++;
                } else {
                    row.hide();
                }
            });

            if (visibleRowCount === 0) {
                $("tfoot").show();
            } else {
                $("tfoot").hide();
            }
        }
        // Add this event listener for the filter form submission
        $('.shared-filter-form').on('submit', function (e) {
            e.preventDefault();
            applyFilters();
        });

    });
</script>
<script>
    $(document).ready(function () {
    // ... other code ...

    // Add event listener to the form
    $("#assessment-form").on("submit", function (event) {
        const selectedCheckboxes = $("input[type=checkbox]:checked");

        if (selectedCheckboxes.length === 0) {
            event.preventDefault();
            alert("You didn't select any record from the list of students.");
        }
    });

    // ... other code ...
});

</script>

@endsection

