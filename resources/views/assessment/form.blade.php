@extends('layouts.frontapp')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-4">
                <div class="card">
                    <div class="card-header">{{ __('Assessment Application Form') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('assessment.submit') }}">
                            @csrf

                            <!-- Hidden Course ID -->
                            <input type="hidden" name="course_id" value="{{ $course->id }}">

                            <!-- Hidden User ID -->
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <!-- School/Training Center/Company -->
                            <div class="form-group row">
                                <label for="school_training_center_company" class="col-md-4 col-form-label text-md-right">{{ __('School/Training Center/Company') }}</label>

                                <div class="col-md-6">
                                    <input id="school_training_center_company" type="text" class="form-control @error('school_training_center_company') is-invalid @enderror" name="school_training_center_company" value="{{ old('school_training_center_company') }}" required>

                                    @error('school_training_center_company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Surname -->
                            <div class="form-group row">
                                <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>

                                <div class="col-md-6">
                                    <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname', explode(' ', $user->name)[1] ?? '') }}" required>

                                    @error('surname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- First Name -->
                            <div class="form-group row">
                                <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', explode(' ', $user->name)[0]) }}" required>

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Middle Name -->
                            <div class="form-group row">
                                <label for="middle_name" class="col-md-4 col-form-label text-md-right">{{ __('Middle Name') }}</label>
                                <div class="col-md-6">
                                    <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name', (count(explode(' ', $user->name)) >= 3) ? explode(' ', $user->name)[2] : '') }}" required>
                                    @error('middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                             <!-- Applicant Address -->
                            <div class="form-group row">
                                <label for="applicant_address" class="col-md-4 col-form-label text-md-right">{{ __('Applicant Address') }}</label>

                                <div class="col-md-6">
                                    <input id="applicant_address" type="text" class="form-control @error('applicant_address') is-invalid @enderror" name="applicant_address" value="{{ old('applicant_address') }}" required>

                                    @error('applicant_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="form-group row">
                                <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                                <div class="col-md-6">
                                    <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="" disabled selected>Select your gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>

                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                             <!-- Civil Status -->
                            <div class="form-group row">
                                <label for="civil_status" class="col-md-4 col-form-label text-md-right">{{ __('Civil Status') }}</label>

                                <div class="col-md-6">
                                    <select id="civil_status" class="form-control @error('civil_status') is-invalid @enderror" name="civil_status" required>
                                        <option value="" disabled selected>Select your civil status</option>
                                        <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>

                                    @error('civil_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                             <div class="form-group row">
                                <label for="application_type" class="col-md-4 col-form-label text-md-right">{{ __('Application Type') }}</label>

                                <div class="col-md-6">
                                    <select id="application_type" class="form-control @error('application_type') is-invalid @enderror" name="application_type" required>
                                        <option value="" disabled selected>Select application type</option>
                                        <option value="full_qualification" {{ old('application_type') == 'full_qualification' ? 'selected' : '' }}>
                                                  Full Qualification</option>
                                        <option value="COC" {{ old('application_type') == 'COC' ? 'selected' : '' }}>COC</option>
                                        <option value="renewal" {{ old('application_type') == 'renewal' ? 'selected' : '' }}>Renewal</option>
                                    </select>

                                    @error('application_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                             <!-- Client Type -->
                            <div class="form-group row">
                                <label for="client_type" class="col-md-4 col-form-label text-md-right">{{ __('Client Type') }}</label>

                                <div class="col-md-6">
                                    <select id="client_type" class="form-control @error('client_type') is-invalid @enderror" name="client_type" required>
                                        <option value="" disabled selected>Select client type</option>
                                        <option value="TVET_graduating_student" {{ old('client_type') == 'TVET_graduating_student' ? 'selected' : '' }}>TVET Graduating Student</option>
                                        <option value="TVET_graduate" {{ old('client_type') == 'TVET_graduate' ? 'selected' : '' }}>TVET Graduate</option>
                                        <option value="industry_worker" {{ old('client_type') == 'industry_worker' ? 'selected' : '' }}>Industry Worker</option>
                                        <option value="K12" {{ old('client_type') == 'K12' ? 'selected' : '' }}>K12</option>
                                        <option value="OFW" {{ old('client_type') == 'OFW' ? 'selected' : '' }}>OFW</option>
                                    </select>

                                    @error('client_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                             <!-- Assessment Title -->
                            <div class="form-group row">
                                <label for="assessment_title" class="col-md-4 col-form-label text-md-right">{{ __('Assessment Title') }}</label>

                                <div class="col-md-6">
                                    <input id="assessment_title" type="text" class="form-control @error('assessment_title') is-invalid @enderror" name="assessment_title" value="{{ old('assessment_title', $course->name) }}" required readonly>

                                    @error('assessment_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
