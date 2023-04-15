<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $enrollment;
    public $enrollmentUrl;

    public function __construct(Enrollment $enrollment, $enrollmentUrl)
    {
        $this->enrollment = $enrollment;
        $this->enrollmentUrl = $enrollmentUrl;
    }

    public function build()
    {
        return $this->markdown('emails.enrollment_status_updated')
                    ->with([
                        'enrollment' => $this->enrollment,
                        'enrollmentUrl' => $this->enrollmentUrl
                    ]);
    }
}
