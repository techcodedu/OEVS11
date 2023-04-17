<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\StudentAssessment;
use App\Models\PersonalInformation;
use App\Models\EnrollmentDocument;


class DashboardController extends Controller
{
    public function index() {
    $enrollmentsCount = Enrollment::enrolled()->count();
    $onlineRegistered = PersonalInformation::count();
     $uniqueEnrollmentDocumentsCount = EnrollmentDocument::distinct('enrollment_id')->count('enrollment_id');
    $scheduledAssessmentsCount = StudentAssessment::whereNotNull('schedule_date')->count();
   

    return view('admin.dashboard', compact('enrollmentsCount', 'scheduledAssessmentsCount','onlineRegistered','uniqueEnrollmentDocumentsCount'));
}

}