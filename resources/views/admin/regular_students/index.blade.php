@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Payments') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                     <div class="card">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Enrollment ID</th>
                                    <th>User ID</th>
                                    <th>Full Name</th>
                                    <th>Payment Method</th>
                                    <th>Payment Schedule</th>
                                    <th>Registration Paid</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment->enrollment_id }}</td>
                                    <td>{{ $payment->user_id }}</td>
                                    <td>{{ $payment->user->name }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>{{ $payment->payment_schedule }}</td>
                                    <td>{{ $payment->registration_is_paid == 1 ? 'Yes' : 'No' }}</td>
                                    <td>
                                       <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewHistoryModal" data-payment-id="{{ $payment->id }}"><i class="fas fa-history"></i> View History</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#recordPaymentModal" data-payment-id="{{ $payment->id }}"><i class="fas fa-plus"></i> Record Payment</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer clearfix"></div>  
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    <!-- View History Modal -->
<!-- View History Modal -->
<div class="modal fade" id="viewHistoryModal" tabindex="-1" role="dialog" aria-labelledby="viewHistoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewHistoryModalLabel">Payment History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Payment history content will be loaded here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1" role="dialog" aria-labelledby="recordPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="recordPaymentModalLabel">Record Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Payment recording form will be loaded here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save Payment</button>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Load payment history modal content
    $('button[data-target="#viewHistoryModal"]').on('click', function() {
        const paymentId = $(this).data('payment-id');
        const url = `/admin/payments/${paymentId}/history`;
        console.log('History URL:', url);
        $.get(url, function(data) {
            $('#viewHistoryModal .modal-body').html(data);
        });
    });

    // Load record payment modal content
    $('button[data-target="#recordPaymentModal"]').on('click', function() {
        const paymentId = $(this).data('payment-id');
        const url = `/admin/payments/${paymentId}/record-form`;
        console.log('Record URL:', url);
        $.get(url, function(data) {
            $('#recordPaymentModal .modal-body').html(data);
        });
    });

    // Save payment form submission
    $('#recordPaymentModal .btn-primary').on('click', function() {
        console.log('Submitting form...');
        $('#record-payment-form').submit();
    });
});
</script>

@endsection