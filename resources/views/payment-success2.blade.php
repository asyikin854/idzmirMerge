@extends('layouts.simple.master-parent')
@section('title', 'Payment')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Payment Successful</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payment Status</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
      <div class="card">
        <div class="container mt-5">
            <div class="alert alert-success">
                <h1>Payment is Successful!</h1>
                <p>Your payment has been processed successfully. Thank you for your transaction.</p>
                <p>Please wait for our team to approved and assign your sessions.</p>
            </div>
        </div>
      </div></div></div>
@endsection