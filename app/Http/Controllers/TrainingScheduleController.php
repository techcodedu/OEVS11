<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingSchedule;
use App\Models\PersonalInformation;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;

class TrainingScheduleController extends Controller
{
  public function index()
    {
        // Add this line to get all the courses
        $courses = Course::all();

        $enrollments = Enrollment::where('status', 'enrolled')
                        ->with('user', 'course', 'trainingSchedule') // Include trainingSchedule in the with() method
                        ->get();
        $scholarshipGrants = $enrollments->pluck('scholarship_grant')->unique()->toArray();

        return view('admin.training_schedule.index', compact('enrollments', 'courses', 'scholarshipGrants'));
    }

    public function saveBulkSchedule(Request $request)
    {
        $this->validate($request, [
            'enrollment_id' => 'required|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $enrollment_ids = $request->input('enrollment_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        foreach ($enrollment_ids as $enrollment_id) {
            // Create a new schedule for each selected enrollment
            $schedule = new TrainingSchedule();
            $schedule->enrollment_id = $enrollment_id;
            $schedule->start_date = $start_date;
            $schedule->end_date = $end_date;
            $schedule->save();
        }

        return redirect()->back()->with('success', 'Schedules saved successfully for selected students.');
    }
    public function updateSchedule(Request $request, $id)
    {
        $this->validate($request, [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $schedule = TrainingSchedule::findOrFail($id);
        $schedule->start_date = $start_date;
        $schedule->end_date = $end_date;
        $schedule->save();

        return redirect()->back()->with('success', 'Schedule updated successfully for the student.');
    }

    public function removeSchedule($id)
    {
        $schedule = TrainingSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->back()->with('success', 'Schedule removed successfully for the student.');
    }




}
