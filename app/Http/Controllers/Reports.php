<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\StudentAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Course;


class Reports extends Controller
{
    public function index(Request $request)
    {
        // Filter the enrollments data
        $enrollments = Enrollment::query();

        // You can add more filters based on the form input
        if ($request->has('status')) {
            $enrollments->where('status', $request->status);
        }

        $enrollments = $enrollments->get();

        // Filter the scheduled assessments data
        $scheduledAssessments = StudentAssessment::query();

        if ($request->has('date_from') && $request->has('date_to')) {
            $scheduledAssessments->whereBetween('schedule_date', [$request->date_from, $request->date_to]);
        }

        $scheduledAssessments = $scheduledAssessments->get();

        // Calculate the analytics data
        $totalEnrollments = Enrollment::count();
        $totalCompleted = Enrollment::where('status', 'completed')->count();
        $totalInProgress = Enrollment::where('status', 'inProgress')->count();

        $enrollmentStatusCount = Enrollment::select('status', DB::raw('count(*) as count'))->groupBy('status')->get();

       $enrollmentCourseData = Course::leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id')
        ->select('courses.name', DB::raw('count(enrollments.id) as enrollments_count'))
        ->groupBy('courses.id', 'courses.name')
        ->get()
        ->map(function ($course) {
            return [
                'course' => $course->name,
                'enrollments' => $course->enrollments_count
            ];
        });

        // Count the enrollment types (scholarship and regular_training)
        $enrollmentTypeData = Enrollment::select('enrollment_type', DB::raw('count(*) as count'))
            ->groupBy('enrollment_type')
            ->get();

        // Count the student assessments by their remarks (Completed and Not Competent)
        $assessmentRemarksData = StudentAssessment::select('remarks', DB::raw('count(*) as count'))
            ->whereNotNull('schedule_date')
            ->groupBy('remarks')
            ->get();

       // Pass the data to the view
        return view('admin.reports', compact('enrollments', 'scheduledAssessments', 'totalEnrollments', 'totalCompleted', 'totalInProgress', 'enrollmentStatusCount', 'enrollmentCourseData', 'enrollmentTypeData', 'assessmentRemarksData'));
    }

        
}
