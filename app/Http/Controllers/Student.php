<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Log;
use App\Models\AssessmentApplication;
use App\Models\TrainingSchedule;
use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;


class Student extends Controller
{
   public function applications()
    {
        $user = Auth::user();

        // Fetch the student's course enrollments and assessment applications, excluding cancelled ones
        $enrollments = $user->enrollments()
            ->whereNull('cancellation_status')
            ->get();

        $assessment_applications = $user->assessmentApplications()
        ->where(function ($query) {
        $query->where('status', 'pending')
              ->orWhere('status', 'scheduled');
            })
        ->whereNull('cancellation_status')
        ->get();

        // Log the fetched data
        // Log::info('User ID:', [$user->id]);
        // Log::info('Enrollments:', $enrollments->toArray());
        // Log::info('Assessment Applications:', $assessment_applications->toArray());

        return view('student.applications', compact('user', 'enrollments', 'assessment_applications'));
    }
     // cancellation of assessment application

    public function cancelAssessmentApplication($id)
    {

        $application = AssessmentApplication::findOrFail($id);

        // Check if the application was created within the last 3 days
        if ($application->created_at->diffInDays(Carbon::now()) <= 3) {
            $application->cancellation_status = 'Cancelled';
            $application->save();

            return redirect()->back()->with('success', 'Assessment application cancelled successfully');
        }

        return redirect()->back()->with('error', 'Assessment application cancellation is only allowed within 3 days of submission');
    }

    public function registration(){
        return view('admin.studentregistration');
    }
      public function profile(){
        return view('admin.studentprofile');
    }

    public function getTrainingSchedule($id)
    {
        $trainingSchedule = TrainingSchedule::with('enrollment')->findOrFail($id);
        
        $start_date_formatted = Carbon::parse($trainingSchedule->start_date)->format('F j, Y');
        $end_date_formatted = Carbon::parse($trainingSchedule->end_date)->format('F j, Y');
        
        $schedule = [
            'start_date_formatted' => $start_date_formatted,
            'end_date_formatted' => $end_date_formatted,
            'scholarship_grant' => $trainingSchedule->enrollment->scholarship_grant
        ];

        return response()->json($schedule);
    }
    
    public function viewFeedback($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $feedback = $enrollment->feedback;

        return response()->json($feedback);
    }
    
}
