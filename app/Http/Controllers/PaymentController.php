<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::whereHas('enrollment', function ($query) {
            $query->where('enrollment_type', 'regular_training');
        })->with('user')->get();

        return view('admin.regular_students.index', compact('payments'));
    }

    public function history(Payment $payment)
    {
        // Load the related payment history data
        $paymentHistories = $payment->paymentHistories;
        return view('admin.regular_students.payment_history', compact('paymentHistories'));
    }

    public function recordForm(Payment $payment)
    {
        // Fetch any necessary data and pass it to the view
        return view('admin.regular_students.record_form', compact('payment'));
    }

    public function recordPayment(Payment $payment, Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'date_paid' => 'required|date',
            // Add any other necessary validation rules
        ]);

        // Create a new payment history record using the validated data
        $paymentHistory = new PaymentHistory($validatedData);
        $paymentHistory->payment_id = $payment->id;
        $paymentHistory->save();

        // Return a response, either a success message or a redirect
        return redirect()->route('admin.regular_students.index')->with('success', 'Payment recorded successfully.');
    }


}
