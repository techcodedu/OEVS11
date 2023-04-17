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
                        <div class="card-body">
                            <form action="{{ route('student_assessments.store') }}" method="POST">
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
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="schedule[{{ $loop->index }}][selected]" value="1">
                                                    <input type="hidden" name="schedule[{{ $loop->index }}][enrollment_id]" value="{{ $student->id }}">
                                                </td>
                                                <td>{{ $student->id }}</td>
                                                <td>{{ $student->user->name }}</td>
                                                <td>{{ $student->user->email }}</td>
                                                <td>{{ $student->course->name }}</td>
                                                <td>{{ $student->enrollment_type }}</td>
                                                <td>{{ optional($student->trainingSchedule)->end_date }}</td>
                                               <td><span class="schedule-date-display">{{ old('schedule.'.$loop->index.'.schedule_date', optional($student->studentAssessment)->schedule_date) }}</span></td>
                                                <td class="d-flex align-items-center">
                                                    <input type="hidden" name="schedule[{{ $loop->index }}][schedule_date]" value="{{ old('schedule.'.$loop->index.'.schedule_date', optional($student->assessment)->schedule_date) }}">
                                                    <select name="schedule[{{ $loop->index }}][remarks]" id="remarks-{{ $student->id }}" data-enrollment-id="{{ $student->id }}" data-student-name="{{ $student->user->name }}" data-course-name="{{ $student->course->name }}" class="form-control remarks-dropdown mr-2" {{ optional($student->studentAssessment)->schedule_date ? '' : 'disabled' }}>
                                                        <option value="">Select</option>
                                                        <option value="Competent">Competent</option>
                                                        <option value="Not Competent">Not Competent</option>
                                                    </select>
                                                    <button type="button" class="btn btn-sm btn-outline-primary update-schedule-date mr-2" title="Update Schedule Date"><i class="fas fa-edit"></i></button>
                                                    <span id="badge-{{ $student->id }}" class="badge badge-remarks">
                                                        <i class="{{ optional($student->studentAssessment)->remarks === 'Competent' ? 'fas fa-check-circle' : (optional($student->studentAssessment)->remarks === 'Not Competent' ? 'fas fa-times-circle' : 'fas fa-exclamation-circle') }}"></i>
                                                        {{ optional($student->studentAssessment)->remarks ? optional($student->studentAssessment)->remarks : 'Remarks yet' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
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

   <!-- ... -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('checkAll').addEventListener('change', function () {
            let remarksDropdowns = document.querySelectorAll('select[name^="schedule"]');
            let scheduleDateInputs = document.querySelectorAll('input[name^="schedule"][name$="[schedule_date]"]');
            let checkboxes = document.querySelectorAll('input[type="checkbox"]:not(#checkAll)');

            for (let i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = this.checked;
                if (this.checked) {
                    remarksDropdowns[i].disabled = !document.getElementById('assessment_date').value;
                    scheduleDateInputs[i].value = document.getElementById('assessment_date').value;
                } else {
                    remarksDropdowns[i].disabled = true;
                    scheduleDateInputs[i].value = '';
                }
            }
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
                if (document.getElementById('assessment_date').value) {
                    scheduleDateInput.value = document.getElementById('assessment_date').value;
                    scheduleDateDisplay.textContent = scheduleDateInput.value;
                }
                remarksDropdown.disabled = !scheduleDateInput.value;

                if (scheduleDateInput.value) {
                    singleRowForm.querySelector('form').submit();
                }
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
        document.querySelectorAll('.update-schedule-date').forEach(function (button) {
        button.addEventListener('click', function () {
            let row = this.closest('tr');
            let scheduleDateDisplay = row.querySelector('.schedule-date-display');
            let scheduleDateInput = row.querySelector('input[name^="schedule"][name$="[schedule_date]"]');
            let remarksDropdown = row.querySelector('.remarks-dropdown');

            let newScheduleDate = prompt('Enter new schedule date (YYYY-MM-DD):', scheduleDateDisplay.textContent.trim());
            if (newScheduleDate) {
                scheduleDateDisplay.textContent = newScheduleDate;
                scheduleDateInput.value = newScheduleDate;
                remarksDropdown.disabled = false;
            }
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

                        // Remove the previous text node
                        badge.removeChild(badge.childNodes[1]);

                        // Create a new text node with the updated text
                        let newText = document.createTextNode(' ' + (remarks ? remarks : 'Remarks yet'));

                        // Add the new text node after the icon
                        badge.insertBefore(newText, icon.nextSibling);

                    }
                })
                .catch(error => {
                    console.error('Error updating remarks:', error);
                });
            }
        });
    });


    </script>
@endsection

