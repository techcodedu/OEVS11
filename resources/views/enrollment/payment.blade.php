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
                        <form action="{{ route('enrollment.payment.store', $enrollment) }}" method="POST">
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

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    <!-- Enrollment payment view file -->

    @endsection
    @section('scripts')
        <script>
         document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          console.log('Submit event captured');
          const formData = new FormData(form);
          console.log('Values:');
          for (const [name, value] of formData) {
            console.log(`${name}: ${value}`);
          }
          fetch(form.action, {
              method: form.method,
              headers: {
                  'Accept': 'application/json', // Add this line to make sure the server knows we want JSON
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // Add this line to include the CSRF token
              },
              body: formData
          })
          .then(response => {
              if (!response.ok) {
                  return response.json().then(validationErrors => {
                      console.error('Validation errors:', validationErrors);
                      throw new Error('Network response was not ok');
                  });
              }
              return response.json();
          })
          .then(data => {
              console.log(data); // Log the received data to the console
              if (data.success) {
                  // Redirect to the next page if the payment was saved successfully
                  window.location.href = "{{ route('enrollment.step3', $enrollment) }}";
              } else {
                  console.error('Payment not saved');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('There was an error processing your payment. Please try again later.');
          })

          .finally(() => {
              form.reset(); // Reset the form after submission
          });

        });
      });


        </script>
    @endsection

