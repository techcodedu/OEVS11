<?php

namespace App\Http\Controllers;
use App\Models\Enrollment;
use App\Models\AssessmentApplication;
use App\Models\Course;

use Illuminate\Http\Request;

class OApplication extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['course', 'user', 'personalInformation'])
                                ->where(function ($query) {
                                    $query->where('cancellation_status', '!=', 'cancelled')
                                            ->orWhereNull('cancellation_status');
                                })
                                ->paginate(10);

        $assessments = AssessmentApplication::with(['course', 'user', 'schedule'])
                                    ->where(function ($query) {
                                        $query->where('cancellation_status', '!=', 'cancelled')
                                            ->orWhereNull('cancellation_status');
                                    })
                                    ->paginate(10);
        
        // Call the resetApplicationsCount function
        $this->resetApplicationsCount();

        //  retrieve the courses
        $courses = Course::all();
                            
        return view('admin.oapplication', compact('enrollments', 'assessments','courses'));
    }

    public function showAssessment(AssessmentApplication $assessment)
    {
        return view('admin.assessment.show', compact('assessment'));
    
    }

    public function resetApplicationsCount()
    {
        Enrollment::where('viewed', false)->update(['viewed' => true]);
        AssessmentApplication::where('viewed', false)->update(['viewed' => true]);

        return response()->json(['success' => 'Applications count reset successfully']);
    }


}
