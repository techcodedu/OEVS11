@extends('layouts.app')

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
                                                <td>{{ optional($student->assessment)->schedule_date }}</td>
                                                <td>
                                                    <input type="hidden" name="schedule[{{ $loop->index }}][schedule_date]" value="">
                                                    <select name="schedule[{{ $loop->index }}][remarks]" id="remarks-{{ $student->id }}" data-enrollment-id="{{ $student->id }}" class="form-control" {{ optional($student->assessment)->schedule_date ? '' : 'disabled' }}>
                                                        <option value="">Select</option>
                                                        <option value="Competent">Competent</option>
                                                        <option value="Not Competent">Not Competent</option>
                                                    </select>
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
                let singleRowForm = this.closest('.single-row-form');
                let scheduleDateInput = singleRowForm.querySelector('input[name="schedule_date"]');
                let remarksDropdown = singleRowForm.querySelector('select[name="remarks"]');

                if (this.checked) {
                    if (document.getElementById('assessment_date').value) {
                        scheduleDateInput.value = document.getElementById('assessment_date').value;
                    }
                    remarksDropdown.disabled = !scheduleDateInput.value;

                    if (scheduleDateInput.value) {
                        singleRowForm.submit();
                    }
                } else {
                    remarksDropdown.disabled = true;
                    scheduleDateInput.value = '';
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
    <!-- ... -->
    <script>
        document.querySelectorAll('select[name^="schedule"]').forEach(function(dropdown) {
            dropdown.addEventListener('change', function() {
                let enrollment_id = this.getAttribute('data-enrollment-id');
                let remarks = this.value;

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
                    }
                })
                .catch(error => {
                    console.error('Error updating remarks:', error);
                });
            });
        });
    </script>
@endsection

