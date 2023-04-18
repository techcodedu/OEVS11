<?php

use App\Http\Controllers\Auth\UsersLoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Certificate;
use App\Http\Controllers\CourseControll;
use App\Http\Controllers\CourseEnrollmentController;
use App\Http\Controllers\TrainingScheduleController;
use App\Http\Controllers\CourseInformation;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\OApplication;
use App\Http\Controllers\Reports;
use App\Http\Controllers\Student;
use App\Http\Controllers\StudentPayments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentAssessmentController;
use App\Models\Enrollment;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();



Route::get('/register', function () {
    return view('auth.register');
})->name('register');

//  logout
Route::get('/signin', [UsersLoginController::class, 'showLoginForm'])->name('signin');
Route::post('/signin', [UsersLoginController::class, 'signin'])->name('signin');
Route::redirect('/login', '/signin');

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::post('/logout', [UsersLoginController::class, 'logout'])->name('logout');
//Admin
//Categories


// Course

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/courses', [CourseControll::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseControll::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseControll::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseControll::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseControll::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseControll::class, 'destroy'])->name('courses.destroy');

    Route::get('/courses/instructors', [InstructorController::class, 'index'])->name('admin.instructors.index');
    Route::get('/courses/instructors/create', [InstructorController::class, 'create'])->name('admin.instructors.create');
    Route::post('/courses/instructors', [InstructorController::class, 'store'])->name('admin.instructors.store');
    Route::get('/courses/instructors/{instructor}', [InstructorController::class, 'show'])->name('admin.instructors.show');

    Route::get('/instructors/{instructor}/edit', [InstructorController::class,'edit'])->name('admin.instructors.edit');
    Route::put('/admin/instructors/{instructor}', [InstructorController::class, 'update'])->name('admin.instructors.update');
    
    Route::delete('/courses/instructors/{instructor}', [InstructorController::class, 'destroy'])->name('instructors.destroy');



    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users');
    Route::get('courseinfo', [CourseInformation::class, 'index'])->name('admin.courseinfo');

    

    // update status of online enrollment
    Route::get('/enrollment/{enrollment}', [CourseEnrollmentController::class, 'showEnrollmentDetails'])->name('admin.enrollment.show');
    Route::put('/admin/enrollments/{enrollment}', [CourseEnrollmentController::class, 'updateStatus'])->name('admin.enrollments.updateStatus');
   
    // assessment and enrollment applicaton application list
    Route::get('application', [OApplication::class, 'index'])->name('admin.oapplication');
    Route::get('admin/assessment/{assessment}', [OApplication::class, 'showAssessment'])->name('admin.assessment.show');
    // realtime update in assessment
    Route::put('/admin/assessments/updateStatus', [CourseEnrollmentController::class, 'updateAssessmentStatus'])->name('admin.assessments.updateStatus');
    Route::get('/assessments/{assessment}/check-schedule', [CourseEnrollmentController::class, 'checkSchedule'])->name('assessments.checkSchedule');

    // notifications
    Route::get('/reset-applications-count', 'OApplication@resetApplicationsCount')->name('reset-applications-count');

    // users activate and deactivate
    Route::post('/admin/users/{id}/toggle-activation', [UserController::class, 'toggleActivation'])->name('admin.users.toggleActivation');

    // use to validate if shcolarship grant has already set
    Route::get('/enrollments/{enrollment}/check-scholarship', [CourseEnrollmentController::class, 'checkScholarship'])->name('enrollments.checkScholarship');

     //route to training schedule
    Route::get('/training-schedules', [TrainingScheduleController::class, 'index'])->name('training-schedules.index');

    // admin set for the schedule of trainees who are enrolled
    Route::post('/save_bulk_schedule', [TrainingScheduleController::class, 'saveBulkSchedule']);
    Route::post('/update_schedule/{id}', [TrainingScheduleController::class, 'updateSchedule'])->name('update_schedule');
    Route::delete('/remove_schedule/{id}', [TrainingScheduleController::class, 'removeSchedule'])->name('remove_schedule');

    //admin send feedback to student credentials
    Route::post('/application/enrollment/feedback', [CourseEnrollmentController::class, 'storeFeedback'])->name('enrollment.storeFeedback');

    // admin student payments
    Route::get('/admin/payments', 'App\Http\Controllers\PaymentController@index')->name('admin.regular_students.index');
    Route::get('/admin/payments/{payment}/history', 'App\Http\Controllers\PaymentController@history')->name('admin.regular_students.payment_history');
    Route::get('/admin/payments/{payment}/record-form', 'App\Http\Controllers\PaymentController@recordForm')->name('admin.regular_students.record_form');
    Route::post('/admin/payments/{payment}/record', 'App\Http\Controllers\PaymentController@recordPayment')->name('admin.regular_students.payment.record');

    //admin set students assessment
    Route::get('student-assessments', [StudentAssessmentController::class, 'index'])->name('admin.students_assessments.index');
    Route::post('student-assessments', [StudentAssessmentController::class, 'store'])->name('student_assessments.store');
   
    Route::post('student-assessments/update-remarks', [StudentAssessmentController::class, 'updateRemarks'])->name('student_assessments.update_remarks');
    Route::post('/student_assessments/update_schedule_date', [StudentAssessmentController::class, 'updateScheduleDate'])->name('student_assessments.update_schedule_date');

    
    Route::post('/student_assessments/remove_schedule_date', [StudentAssessmentController::class, 'removeScheduleDate'])->name('student_assessments.remove_schedule_date');


    // Admin reports
    Route::get('/reports', [Reports::class, 'index'])->name('admin.reports');










    Route::get('certificate', [Certificate::class, 'index'])->name('admin.certificate');
    
    Route::get('studentreg', [Student::class, 'registration'])->name('admin.studentregistration');
    Route::get('sprofile', [Student::class, 'profile'])->name('admin.studprofile');
    Route::get('studentprofile', [StudentPayments::class, 'index'])->name('admin.payments');
});
    //   front
    Route::get('/', [FrontEndController::class, 'index'])->name('index');
    Route::get('/availablecourses', [FrontEndController::class, 'index'])->name('availablecourses');
    Route::get('/about', [FrontEndController::class, 'about'])->name('about');
    Route::get('/front/signin', [FrontEndController::class, 'index'])->name('front/signin');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::group(['middleware' => ['role:student']], function () {
    Route::get('/enroll/{course}', [CourseEnrollmentController::class, 'enroll'])->name('enroll');

     // ASSESSMENT
    Route::get('/assessment/{course}/{user}/{enrollment_type}', [AssessmentController::class, 'showAssessmentForm'])->name('assessment.application');
    Route::post('/assessment/submit', [AssessmentController::class, 'submitApplication'])->name('assessment.submit');


    Route::get('/student/applications', [Student::class, 'applications'])->name('student.applications');
    Route::get('/courses/{course}', [CourseEnrollmentController::class, 'show'])->name('courses.show');

    // assessment and enrollment cancellation
    Route::get('/enrollments/{id}/cancel', [CourseEnrollmentController::class, 'cancelEnrollment'])->name('enrollments.cancel');
    Route::get('/assessment_applications/{id}/cancel', [Student::class, 'cancelAssessmentApplication'])->name('assessment_applications.cancel');

    Route::post('/enrollment/{courseId}/{userId}', [CourseEnrollmentController::class, 'storeEnrollment'])->name('enrollment.store');
    Route::get('/enrollment/{courseId}/{userId}/{enrollmentType}', [CourseEnrollmentController::class, 'enroll'])->name('enrollment.enroll');
    Route::get('/courses/{course}/enrollment/{user}', [CourseEnrollmentController::class, 'showEnrollmentForm'])->name('enrollment.form');
    Route::post('/enrollment/step1/{courseId}/{userId}', [CourseEnrollmentController::class, 'submitStep1'])->name('enrollment.step1.submit');
    Route::get('/enrollment/step2/{enrollment}', [CourseEnrollmentController::class, 'step2'])->name('enrollment.step2');
    Route::post('/enrollment/step2/process/{enrollment}', [CourseEnrollmentController::class, 'storeStep2'])->name('enrollment.step2.submit');
    Route::get('/enrollment/step3/{enrollment}', [CourseEnrollmentController::class, 'showStep3Form'])->name('enrollment.step3');
    Route::post('/enrollment/step3/process/{enrollment}', [CourseEnrollmentController::class, 'storeStep3'])->name('enrollment.step3.submit');
    Route::get('/enrollment/complete/{enrollment}', [CourseEnrollmentController::class, 'complete'])->name('enrollment.complete');

    // payment
    Route::get('/enrollment/{enrollment}/payment', [CourseEnrollmentController::class, 'showPaymentForm'])->name('enrollment.payment');
    Route::post('/enrollment/{enrollment}/payment/save', [CourseEnrollmentController::class, 'storePayment'])->name('enrollment.payment.save');

    // email view details of enrollment
    Route::get('/enrollment/details/{enrollment}', [CourseEnrollmentController::class, 'emailLinkDetails'])->name('enrollment.details');

    // My application student front view schedule
    Route::get('/get_schedule/{id}', [App\Http\Controllers\Student::class, 'getTrainingSchedule'])->name('get_schedule');

    //view feedback by student
     Route::get('/student/applications/view-feedback/{id}', [App\Http\Controllers\Student::class, 'viewFeedback'])->name('student.viewFeedback');

    // Update student avatar or picture
     Route::post('/update-avatar', [UserController::class, 'updateAvatar'])->name('update-avatar');
   


});
Route::get('/courses/category/{id}', [FrontEndController::class, 'category'])->name('courses.category');

