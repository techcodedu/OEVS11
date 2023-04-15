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
                        <form method="POST" action="{{ route('enrollment.payment.save', ['enrollment' => $enrollment]) }}">
                            @csrf
                            <!-- Payment method -->
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select class="form-control" name="payment_method" id="payment_method">
                                    <option value="GCASH">GCASH</option>
                                    <option value="over_the_counter">Over the Counter</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <!-- Payment schedule -->
                            <div class="form-group">
                                <label for="payment_schedule">Payment Schedule</label>
                                <select class="form-control" name="payment_schedule" id="payment_schedule">
                                    <option value="weekly_installment">Weekly Installment</option>
                                    <option value="last_day_one_time">Last Day One Time</option>
                                </select>
                            </div>

                            <!-- Submit button -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit Payment</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
