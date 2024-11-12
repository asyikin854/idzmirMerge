@extends('layouts.authentication.master')
@section('title', 'Payment')

@section('css')
@endsection

@section('content')
<div class="container mt-5">
    <div class="alert alert-success">
        <h1>Payment Successful!</h1>
        <p>Your payment has been processed successfully. Thank you for your transaction.</p>
        <a href="{{ route('/') }}" class="btn btn-primary">Go Back to Home</a>
    </div>
</div>
@endsection