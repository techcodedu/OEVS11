<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\TrainingSchedule;
use App\Models\StudentAssessment;
use App\Models\Course;
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
            ->with(['user', 'course', 'trainingSchedule', 'studentAssessment'])
            ->get();

        $courses = Course::all();
        $enrollments = Enrollment::whereNotNull('scholarship_grant')->distinct('scholarship_grant')->get();

        return view('admin.students_assessments.index', compact('students', 'courses', 'enrollments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule' => 'required|array',
            'schedule.*.enrollment_id' => 'required|integer|exists:enrollments,id',
            'schedule.*.schedule_date' => 'nullable|date',
        ]);

        $scheduleData = array_filter($request->schedule, function ($schedule) {
            return isset($schedule['selected']) && isset($schedule['schedule_date']);
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

    public function updateScheduleDate(Request $request)
    {
        Log::info('Request data: ', $request->all());
        $validatedData = $request->validate([
            'enrollment_id' => 'required|integer|exists:enrollments,id',
            'schedule_date' => 'nullable|date',
            'remove' => 'sometimes|boolean',
        ]);

        $enrollmentId = $validatedData['enrollment_id'];
        $newScheduleDate = $validatedData['schedule_date'];

        $studentAssessment = StudentAssessment::where('enrollment_id', $enrollmentId)->first();

        if (isset($validatedData['remove']) && $validatedData['remove']) {
            $newScheduleDate = null;
        }

        if ($studentAssessment) {
            $studentAssessment->update(['schedule_date' => $newScheduleDate]);
        } else {
            StudentAssessment::create([
                'enrollment_id' => $enrollmentId,
                'schedule_date' => $newScheduleDate,
            ]);
        }

        return response()->json(['message' => 'Schedule date updated successfully']);
    }


    public function removeScheduleDate(Request $request)
    {
        Log::info('Request data: ', $request->all());
        $enrollmentId = $request->input('enrollment_id');
        
        // Assuming you have a StudentAssessment model and it's related to the Student model
        $studentAssessment = StudentAssessment::where('enrollment_id', $enrollmentId)->first();
        
        if ($studentAssessment) {
            $studentAssessment->delete(); // Delete the record
            return response()->json(['message' => 'Schedule date removed successfully.']);
        } else {
            return response()->json(['message' => 'Error: Schedule date not found.'], 404);
        }
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
