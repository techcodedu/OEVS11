<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\TrainingSchedule;
use App\Models\StudentAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentAssessmentController extends Controller
{
    public function index()
    {
        $students = Enrollment::whereIn('enrollment_type', ['scholarship', 'regular_training'])
            ->whereHas('trainingSchedule', function ($query) {
                $query->whereNotNull('end_date');
            })
            ->with(['user', 'course', 'trainingSchedule'])
            ->get();

        return view('admin.students_assessments.index', compact('students'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'schedule' => 'required|array',
            'schedule.*.enrollment_id' => 'required|integer|exists:enrollments,id',
            'schedule.*.schedule_date' => 'required|date',
        ]);

        $scheduleData = array_filter($request->schedule, function ($schedule) {
            return isset($schedule['selected']);
        });

        // Debugging code to print scheduleData to the log
        Log::info('Submitted schedule data:', $scheduleData);

        foreach ($scheduleData as $schedule) {
            StudentAssessment::create([
                'enrollment_id' => $schedule['enrollment_id'],
                'schedule_date' => $schedule['schedule_date'],
            ]);
        }

        return redirect()->route('admin.students_assessments.index')
            ->with('success', 'Assessment schedules saved successfully.');
    }

    


    public function updateRemarks(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|integer|exists:student_assessments_schedule,enrollment_id',
            'remarks' => 'required|in:Competent,Not Competent',
        ]);

        $assessment = StudentAssessment::where('enrollment_id', $request->input('enrollment_id'))->firstOrFail();
        $assessment->update(['remarks' => $request->input('remarks')]);

        return response()->json(['message' => 'Remarks updated successfully.']);
    }



}
