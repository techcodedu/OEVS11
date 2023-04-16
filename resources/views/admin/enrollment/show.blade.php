<!-- admin/enrollment/show.blade.php -->
<style>
    .custom-card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .table-sm th,
    .table-sm td {
        padding: 0.4rem;
    }

   /* Custom badge colors */
    .badge-over-the-counter {
        background-color: #4caf50;
        color: #ffffff;
    }

    .badge-last-day {
        background-color: #2196f3;
        color: #ffffff;
    }

    .badge-yes {
        background-color: #28a745;
        color: #ffffff;
    }

    .badge-no {
        background-color: #dc3545;
        color: #ffffff;
    }

    .custom-card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }


    .badge-payment-schedule,
    .badge-enrollment-status,
    .badge-enrollment-type,
    .badge-course-name,
    .badge-employment-status {
        background-color: #d3cd10;
    }
    .badge-application-id,
    .badge-currently-schooling {
        background-color: #0bafb5;
    }
</style>
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header custom-card-header">Personal Information</div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                   <tr>
                        <td>Full Name</td>
                        <td>{{ $enrollment->personalInformation->fullname }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>{{ $enrollment->personalInformation->address }}</td>
                    </tr>
                    <tr>
                        <td>Age</td>
                        <td>{{ $enrollment->personalInformation->age }}</td>
                    </tr>
                    <tr>
                        <td>Contact Number</td>
                        <td>{{ $enrollment->personalInformation->contact_number }}</td>
                    </tr>
                    <tr>
                        <td>Facebook</td>
                        <td>{{ $enrollment->personalInformation->facebook }}</td>
                    </tr>
                    <tr>
                        <td>Currently Schooling</td>
                        <td><span class="badge badge-pill badge-currently-schooling">{{ $enrollment->personalInformation->currently_schooling }}</span></td>
                    </tr>
                    <tr>
                        <td>Employment Status</td>
                        <td><span class="badge badge-pill badge-employment-status">{{ $enrollment->personalInformation->employment_status }}</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header custom-card-header">Enrollment Details</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Application ID</td>
                        <td><span class="badge badge-pill badge-application-id">{{ $enrollment->id }}</span></td>
                    </tr>
                    <tr>
                        <td>Course Name</td>
                        <td><span class="badge badge-pill badge-course-name">{{ $enrollment->course->name }}</span></td>
                    </tr>
                    <tr>
                        <td>Enrollment Type</td>
                        <td><span class="badge badge-pill badge-enrollment-type">{{ $enrollment->enrollment_type }}</span></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><span class="badge badge-pill badge-enrollment-status">{{ $enrollment->status }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header custom-card-header">Payment Details</div>
            <div class="card-body">
                @if($enrollment->payment)
                    <table class="table table-sm">
                         <tr>
                            <td>Payment Method</td>
                            <td>
                                <span class="badge badge-pill {{ $enrollment->payment->payment_method == 'over_the_counter' ? 'badge-over-the-counter' : 'badge-last-day' }}">
                                    {{ $enrollment->payment->payment_method == 'over_the_counter' ? 'Over the Counter': 'None' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Payment Schedule</td>
                            <td>
                                <span class="badge badge-pill {{ $enrollment->payment->payment_method == 'over_the_counter' ? 'badge-over-the-counter' : 'badge-last-day' }}">
                                        {{ $enrollment->payment->payment_schedule == 'last_day_one_time' ? 'Last Day of Training': 'None' }}
                                </span>
                            </td>
                        </tr>
                       <tr>
                        <td>Registration Paid</td>
                        <td>
                            <span class="badge badge-pill {{ $payment->registration_is_paid == 1 ? 'badge-yes' : 'badge-no' }}">
                                {{ $payment->registration_is_paid == 1 ? 'Yes' : 'No' }}
                            </span>
                        </td>
                    </tr>

                    </table>
                    </table>
                @else
                    <p>No payment information available.</p>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header custom-card-header">Enrollment Documents</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Document Type</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                     <tbody>
                        @foreach ($enrollment->enrollmentDocuments as $document)
                        <tr>
                            <td>{{ $document->path ? pathinfo(storage_path('app/' . $document->path), PATHINFO_FILENAME) . '.' . pathinfo(storage_path('app/' . $document->path), PATHINFO_EXTENSION) : 'N/A' }}</td>
                            <td>{{ $document->document_type }}</td>
                            <td><a href="{{ asset('storage/' . str_replace(' ', '%20', $document->path)) }}" target="_blank"><i class="fas fa-download"></i></a></td>
                        </tr>
                    @endforeach
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
   
 