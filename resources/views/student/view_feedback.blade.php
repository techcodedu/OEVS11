@extends('layouts.frontapp')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Feedback Message</div>

                <div class="card-body">
                    <p>{{ $feedback }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
