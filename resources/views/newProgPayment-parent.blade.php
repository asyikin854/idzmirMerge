@extends('layouts.simple.master-parent')
@section('title', 'Payment')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Payment Details</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Program</li>
    <li class="breadcrumb-item">Change Program</li>
    <li class="breadcrumb-item active">New Program Schedule</li>
    <li class="breadcrumb-item active">Payment</li>
@endsection

@section('content')
<div class="container-fluid checkout">
   <div class="card">
      <div class="card-header">
         <h4>Payment Details</h4>
      </div>
      <div class="card-body">
         <div class="row">
            <div class="col-xl-6 col-sm-12">
               <form action="{{ route('submitNewProgPayment') }}" method="POST">
                  @csrf
                  <!-- Hidden fields to send necessary data -->
                  <input type="hidden" name="child_id" value="{{ $child_id }}">
                  <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                  <input type="hidden" name="parent_id" value="{{ $parentAccount->id }}">
                  <input type="hidden" name="selected_slots" value="{{ json_encode($selectedSlots) }}">
                  <input type="hidden" name="additional_sessions" value="{{ $additionalSessions }}">
                  <input type="hidden" name="session_id" value="{{ $sessionId }}">
                  <input type="hidden" name="package_id" value="{{ $package->id}} ">
                  <input type="hidden" name="type" value="{{ $type }} ">

                  <div class="row">
                     <div class="mb-3 col-sm-12">
                     <h5>Child Details</h5>
                     <div class="table-responsive">
                     <table class="table table-bordered">
                        <tr>
                           <th>Child Name</th>
                           <td>{{$childInfo->child_name}} </td>
                        </tr><tr>
                           <th>IC Number</th>
                           <td>{{$childInfo->child_ic}} </td>
                        </tr><tr>
                           <th>Program</th>
                           <td>{{$childInfo->package->package_name}} </td>
                        </tr>
                     </table></div></div>

                     <div class="mb-3 col-sm-12">
                     <h5>Account Details</h5>
                     <div class="table-responsive">
                     <table class="table table-bordered">
                        <tr>
                           <th>Username</th>
                           <td>{{$parentAccount->username}} </td>
                        </tr><tr>
                           <th>Email</th>
                           <td>{{$parentAccount->email}} </td>
                        </tr>
                     </table></div></div>

                     <div class="mb-3 col-sm-12">
                     <h5>Parent Details</h5>
                     <div class="table-responsive">
                     <table class="table table-bordered">
                        <tr>
                           <th>Father's Name</th>
                           <td>{{$fatherInfo->father_name}} </td>
                           <th>Mother's Name</th>
                           <td>{{$motherInfo->mother_name}} </td>
                        </tr><tr>
                           <th>Phone No.</th>
                           <td>{{$fatherInfo->father_phone}} </td>
                           <th>Phone No.</th>
                           <td>{{$motherInfo->mother_phone}} </td>
                        </tr>
                     </table></div></div>
                  </div>
            </div>
            <div class="col-xl-6 col-sm-12">
               <div class="checkout-details">
                  <div class="order-box">
                     <div class="title-box">
                        <div class="checkbox-title">
                           <h4>Program</h4>
                           <span>Total</span>
                        </div>
                     </div>
                     <ul class="qty">
                        <li>{{$childInfo->package->package_name}} <span>RM {{$basePrice}} </span></li>
                        <li>Additional Session = {{$additionalSessions}} <span>RM {{$additionalPrice}} </span></li>
                     </ul>
                     <ul class="sub-total total">
                        <li>Total <span class="count">RM {{$totalPrice}} </span></li>
                     </ul>
                     <div class="animate-chk">
                        <div class="row">
                           <div class="col">
                              <label class="d-block" for="edo-ani">
                              <input class="radio_animated" id="edo-ani" type="radio" name="rdo-ani" checked="" data-original-title="" title="">Online Banking
                           </div>
                        </div>
                     </div>
                     <div class="order-place"><button class="btn btn-primary" type="submit">Proceed</button></div></form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
@endsection