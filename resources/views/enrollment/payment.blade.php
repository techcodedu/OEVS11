@extends('layouts.frontapp')
@section('title', 'Payment Page')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h2>Payment Policy</h2>
                    </div>
                    <div class="card-body">
                        <!-- End of debugging information -->
                        <form action="{{ route('enrollment.payment.store', $enrollment) }}" method="POST" id="payment-form" data-enrollment-id="{{ $enrollment->id }}">
                            @csrf
                             <input type="hidden" name="enrollment_type" value="{{ $enrollment->enrollment_type }}">

                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select id="payment_method" name="payment_method" class="form-control" required>
                                    <option value="" disabled selected>Select payment method</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method }}">{{ ucfirst(str_replace('_', ' ', $method)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="payment_schedule">Payment Schedule</label>
                                <select id="payment_schedule" name="payment_schedule" class="form-control" required>
                                    <option value="" disabled selected>Select payment schedule</option>
                                    @foreach ($paymentSchedules as $schedule)
                                        <option value="{{ $schedule }}">{{ ucfirst(str_replace('_', ' ', $schedule)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" id="due_date" name="due_date" class="form-control">
                                <p>This is optional, if you want to set our timeline of payment</p>
                            </div>

                            <div class="form-group">
                                <label>Refund Policy</label>
                                <p>Put your refund policy text here.</p>
                            </div>

                            <div class="form-group">
                                <label>Registration Fee</label>
                                <p>The registration fee is 500 pesos.</p>
                            </div>

                           <button type="submit" id="submit-payment" data-enrollment-id="{{ $enrollment->id }}">Submit Payment</button>

                        </form>
                    </div>
                </div>
            </div>
    </div>
    <!-- Enrollment payment view file -->

    @endsection
@section('scripts')
    <script>
  document.querySelector('#submit-payment').addEventListener('click', async function savePayment(e) {
    e.preventDefault();

    const formElement = e.target.closest('form');
    const formData = new FormData(formElement);

    try {
      const enrollmentId = e.target.dataset.enrollmentId;
      const response = await axios.post(`/enrollment/${enrollmentId}/submitted`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
      });

      const data = response.data;

      if (data.success) {
        alert(data.message);
      } else {
        alert("Error saving payment");
      }
    } catch (error) {
      console.error("There was a problem with the axios request:", error);
    }
  });

    </script>
@endsection

