<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentApplication extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'course_id',
        'school_training_center_company',
        'address',
        'assessment_title',
        'application_type',
        'client_type',
        'surname',
        'first_name',
        'middle_name',
        'applicant_address',
        'gender',
        'civil_status',
        'status',
        'cancellation_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function schedule()
    {
        return $this->hasOne(AssessmentSchedule::class);
    }

}
