@extends('layouts.authentication.master')
@section('title', 'Payment')

@section('css')
@endsection

@section('content')
<div class="container mt-5">
    <div class="alert alert-danger">
        <h1>Payment Failed</h1>
        <p>Unfortunately, your payment was not successful. Please try again or contact support for assistance.</p>
        @if ($errors->has('error'))
            <p><strong>Error:</strong> {{ $errors->first('error') }}</p>
        @endif
        <a href="{{ route('checkout-parent', ['child_id' => $child_id, 'package_id' => $package_id]) }}" class="btn btn-primary">Try Again</a>
    </div>
</div>
@endsection