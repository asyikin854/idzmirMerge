@extends('layouts.simple.master-parent')
@section('title', 'Payment')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Payment Failed</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payment Status</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
      <div class="card">
        <div class="container mt-5">
            <div class="alert alert-danger">
                <h1>Your payment is unsuccessful.</h1>
                <p>Your transaction failed. Please try again.</p>
                <br>
                <a href="{{route('newProgPayment-parent', ['child_id' => $child_id, 'package_id' => $package_id])}}"><button class="btn btn-warning">Try again</button></a>
                <p>You can contact our customer service for assistant</p>
            </div>
        </div>
      </div></div></div>
@endsection