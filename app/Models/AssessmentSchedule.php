<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_application_id',
        'scheduled_date',
    ];

    public function assessmentApplication()
    {
        return $this->belongsTo(AssessmentApplication::class);
    }
}
