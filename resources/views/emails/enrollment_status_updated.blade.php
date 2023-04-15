@component('mail::message')
# Enrollment Status Updated

Dear {{ $enrollment->user->name }},

Your enrollment status has been updated to "{{ $enrollment->status }}".

@if($enrollment->status === 'enrolled')
Congratulations! You have been enrolled with the "{{ $enrollment->scholarship_grant }}" scholarship.

Please find more information about your enrollment below:

**Course**: {{ $enrollment->course->name }}
{{-- **Start Date**: {{ $enrollment->course->start_date->format('F j, Y') }}
**End Date**: {{ $enrollment->course->end_date->format('F j, Y') }} --}}
@endif

<a href="{{ route('enrollment.details', $enrollment->id) }}" target="_blank" class="button button-blue">View Enrollment Details</a>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
