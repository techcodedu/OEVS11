@extends('layouts.frontapp')

@section('content')

<!-- Enrollment Details Modal -->
<!-- Enrollment Details Modal -->
<div class="modal fade" id="enrollmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="enrollmentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="enrollmentDetailsModalLabel">Enrollment Details for {{ $enrollment->user->name }}</h5>
              <button type="button" id="modalCloseBtn" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            </div>
            <div class="modal-body">
                <p><strong>Course:</strong> {{ $enrollment->course->name }}</p>
                <p><strong>Status:</strong> <span class="badge {{ $enrollment->status == 'approved' ? 'badge-success' : 'badge-warning' }}">{{ $enrollment->status }}</span></p>
                <p><strong>Enrollment Date:</strong> {{ $enrollment->created_at->format('Y-m-d') }}</p>
                @if($enrollment->scholarship_grant)
                <p><strong>Scholarship Grant:</strong> {{ $enrollment->scholarship_grant }}</p>
                @endif
                <!-- Add other enrollment fields as needed -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#enrollmentDetailsModal').modal('show');
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const enrollmentDetailsModal = document.getElementById('enrollmentDetailsModal');
    const modalCloseBtn = document.getElementById('modalCloseBtn');

    // Listen for the 'hidden.bs.modal' event, which is triggered when the modal is closed
    enrollmentDetailsModal.addEventListener('hidden.bs.modal', function () {
        // Redirect the user to the index route
        window.location.href = '{{ route("index") }}';
    });

    // Listen for the 'click' event on the close button
    modalCloseBtn.addEventListener('click', function () {
        // Trigger the 'hidden.bs.modal' event
        enrollmentDetailsModal.dispatchEvent(new Event('hidden.bs.modal'));
    });
});

</script>
@endsection
