@extends('layouts.simple.master-sales')
@section('title', 'Schedules')

@section('css')
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
<h3>Scheduling & Payment Confirmation </h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">New Customer</li>
<li class="breadcrumb-item">Registration</li>
<li class="breadcrumb-item">Scheduling</li>
<li class="breadcrumb-item active">Confirmation</li>
@endsection

@section('content')
<div class="container-fluid checkout">
   <div class="card">
      <div class="card-header">
         <h4>Scheduling Details</h4>
      </div>
      <div class="card-body">
         <div class="row">
            <div class="col-xl-6 col-sm-12">
               <form action="{{ route('confirmSchedule.submit') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <!-- Hidden fields to send necessary data -->
                  <input type="hidden" name="child_id" value="{{ $child_id }}">
                  <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                  <input type="hidden" name="consultDetails" value="{{ json_encode($consultDetails) }}">
                  {{-- <input type="hidden" name="selected_slots" value="{{ json_encode($selectedSlots) }}">
                  <input type="hidden" name="additional_sessions" value="{{ $additionalSessions }}"> --}}
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
                     <h5>Parent Details</h5>
                     <div class="table-responsive">
                     <table class="table table-bordered">
                        <tr>
                           <th>Father's Name</th>
                           <td>{{$fatherInfo->father_name ?? 'N/A'}} </td>
                           <th>Mother's Name</th>
                           <td>{{$motherInfo->mother_name ?? 'N/A'}} </td>
                        </tr><tr>
                           <th>Phone No.</th>
                           <td>{{$fatherInfo->father_phone ?? 'N/A'}} </td>
                           <th>Phone No.</th>
                           <td>{{$motherInfo->mother_phone ?? 'N/A'}} </td>
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
                        <li>{{$childInfo->package->package_name}}</li>
                     </ul>
                     <ul class="sub-total total">
                        <li>Total <span class="count">RM {{$totalPrice}} </span></li>
                     </ul>
                     <div class="col">
                        <div class="table-responsive">
                           <table class="table table-border">
                              <thead>
                                 <tr>
                                    <th>Session</th>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Time</th>
                                 </tr>
                              </thead><tbody>
                                 <tr>
                                    <td>Consultation</td>
                                    <td>{{ $consultDetails['consult_date'] ?? 'N/A' }}</td>
                                    <td>{{ $consultDetails['consult_day'] ?? 'N/A'}}</td>
                                    <td>{{ $consultDetails['consult_time'] ?? 'N/A'}}</td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <div class="animate-chk">
                        <div class="row">
                           <div class="col">
                              <label for="file" class="d-block">Payment Receipt</label>
                              <input type="file" name="file" id="file" class="form-control" required>
                           </div>
                        </div>
                     </div>
                     <div class="order-place"><button class="btn btn-primary" type="submit">Submit</button></div>
                  </div>
               </div>
            </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
@endsection