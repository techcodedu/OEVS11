    @php
    use Carbon\Carbon;
    @endphp
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @if($paymentHistories->isEmpty())
                        <p>No Pay History Transaction at The moment</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Amount</th>
                                    <th>Date Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentHistories as $paymentHistory)
                                    <tr>
                                        <td>{{ $paymentHistory->id }}</td>
                                        <td>₱ {{ $paymentHistory->amount }}</td>
                                         <td>{{ $paymentHistory->date_paid ? Carbon::parse($paymentHistory->date_paid)->format('F j, Y \a\t g:i A') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    <div class="card-footer clearfix"></div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
