<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\AssessmentApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;



class AssessmentController extends Controller
{
    //
    public function showAssessmentForm($courseId, $userId, $enrollmentType)
    {
        $course = Course::findOrFail($courseId);
        $user = User::findOrFail($userId);

        // Check if the user meets the assessment requirements for the course
        // ...

        return view('assessment.form', compact('course', 'user'));
    }
    public function submitApplication(Request $request)
    {
         Log::info('submitApplication() called');
         Log::info('Request data:', $request->all());

        try{
             // Validation
            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'school_training_center_company' => 'required|string|max:255',
                'applicant_address' => 'required|string|max:255',
                'assessment_title' => 'required|string|max:255',
                'application_type' => 'required|in:full_qualification,COC,renewal',
                'client_type' => 'required|in:TVET_graduating_student,TVET_graduate,industry_worker,K12,OFW',
                'surname' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'applicant_address' => 'required|string|max:255',
                'gender' => 'required|in:male,female,other',
                'civil_status' => 'required|in:single,married,divorced,widowed',
                'user_id' => [
                'required',
                Rule::unique('assessment_applications')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->user_id)
                                ->where('course_id', $request->course_id);
                }),
    ],
            ]);

            Log::info('Validation passed');

        }catch(\Illuminate\Validation\ValidationException $e){
            Log::error('Validation failed:', $e->errors());
        }
       
        $courseId = $request->input('course_id');
        $userId = Auth::id();

        $assessmentApplication = new AssessmentApplication;
        $assessmentApplication->user_id = $userId;
        $assessmentApplication->course_id = $courseId;
        $assessmentApplication->school_training_center_company = $request->input('school_training_center_company');
        $assessmentApplication->applicant_address = $request->input('applicant_address'); // <-- Change this line
        $assessmentApplication->assessment_title = $request->input('assessment_title');
        $assessmentApplication->application_type = $request->input('application_type');
        $assessmentApplication->client_type = $request->input('client_type');
        $assessmentApplication->surname = $request->input('surname');
        $assessmentApplication->first_name = $request->input('first_name');
        $assessmentApplication->middle_name = $request->input('middle_name');
        $assessmentApplication->applicant_address = $request->input('applicant_address');
        $assessmentApplication->gender = $request->input('gender');
        $assessmentApplication->civil_status = $request->input('civil_status');
        $assessmentApplication->status = AssessmentApplication::STATUS_PENDING;

        $course = Course::find($courseId);
        $courseInitials = strtoupper(substr($course->name, 0, 3));
        $nextApplicationNumber = AssessmentApplication::where('course_id', $courseId)->count() + 1;
        $formattedApplicationNumber = $courseInitials . '-' . str_pad($nextApplicationNumber, 5, '0', STR_PAD_LEFT);

        $assessmentApplication->application_number = $formattedApplicationNumber;
        $assessmentApplication->save();


        // Send email notification to the admin or assessment coordinator
        // ...

        return redirect()->route('index')->with('success', __('Assessment application submitted successfully.'));
    }


}
