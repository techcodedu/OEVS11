<form id="record-payment-form" action="{{ route('admin.regular_students.payment.record', $payment->id) }}" method="post">
    @csrf
    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="number" class="form-control" id="amount" name="amount" required>
    </div>
    <div class="form-group">
        <label for="date_paid">Date Paid</label>
        <input type="datetime-local" class="form-control" id="date_paid" name="date_paid" required>
    </div>
</form>
