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
                                            <th>Select</th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Course</th>
                                            <th>Enrollment Type</th>
                                            <th>End of Training</th>
                                            <th>Schedule Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td><input type="checkbox" name="schedule[{{ $loop->index }}][enrollment_id]" value="{{ $student->id }}"></td>
                                                <td>{{ $student->id }}</td>
                                                <td>{{ $student->user->name }}</td>
                                                <td>{{ $student->user->email }}</td>
                                                <td>{{ $student->course->name }}</td>
                                                <td>{{ $student->enrollment_type }}</td>
                                                <td>{{ optional($student->trainingSchedule)->end_date }}</td>
                                                <td><input type="date" name="schedule[{{ $loop->index }}][assessment_date]" class="form-control"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
@endsection

