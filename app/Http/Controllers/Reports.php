<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\StudentAssessment;
use Illuminate\Http\Request;

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

        return view('admin.reports', compact('enrollments', 'scheduledAssessments', 'totalEnrollments', 'totalCompleted', 'totalInProgress'));
    }
}
