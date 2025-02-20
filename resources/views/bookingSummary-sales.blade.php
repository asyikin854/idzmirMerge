
@extends('layouts.simple.master-sales')
@section('title', 'Booking Summary')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Booking Summary</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Customer</li>
<li class="breadcrumb-item">On Boarding</li>
<li class="breadcrumb-item">FA Registration</li>
<li class="breadcrumb-item">Slot Booking</li> 
<li class="breadcrumb-item active">Booking Summary</li> 
@endsection

@section('content')
<div class="container">
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-body">
               <div class="invoice">
                  <div>
                     <div>
                        <div class="row">
                           <div class="col-sm-6">
                              <div class="media">
                                 <div class="media-left"><img class="media-object img-60" src="{{asset('assets/images/logo/logo1.png')}}" alt="" style="width: 100px"></div>
                                 <div class="media-body m-l-20 text-right">
                                    <h4 class="media-heading">IdzmirKidsHub</h4>
                                    <p>system@idzmirkidshub.com<br><span>014-414542342</span></p>
                                 </div>
                              </div>
                              <!-- End Info-->
                           </div>
                           <div class="col-sm-6">
                              <div class="text-md-end text-xs-center">
                                 <h3>Booking ID #<span>{{ $sessionId }}</span></h3>
                                 <p>Issued: {{ now()->format('F d, Y') }}</p>
                              </div>
                              <!-- End Title-->
                           </div>
                        </div>
                     </div>
                     <hr>
                     <!-- End InvoiceTop-->
                     <div class="row">
                        <div class="col-md-4">
                           <div class="media">
                              @if($fatherInfo)
                              <div class="media-body m-l-20">
                                 <h4 class="media-heading">{{ $fatherInfo->father_name ?? 'N/A'}}</h4>
                                 <p>{{ $fatherInfo->father_email ?? 'N/A'}}<br><span>{{ $fatherInfo->father_phone ?? 'N/A'}}</span></p>
                              </div>
                              @else
                              <p>There are no father Information</p>
                              @endif
                              @if($motherInfo)
                              <div class="media-body m-l-20">
                                 <h4 class="media-heading">{{ $motherInfo->mother_name ?? 'N/A'}}</h4>
                                 <p>{{ $motherInfo->mother_email ?? 'N/A'}}<br><span>{{ $motherInfo->mother_phone ?? 'N/A'}}</span></p>
                              </div>
                              @else
                              <p>There are no mother information</p>
                              @endif
                           </div>
                        </div>
                        <div class="col-md-8">
                           <div class="text-md-end" id="project">
                              <h6>Program</h6>
                              <p>{{ $package->package_name }}</p>
                           </div>
                        </div>
                     </div>
                     <!-- End Invoice Mid-->
                     <div>
                        <div class="table-responsive invoice-table" id="table">
                           <table class="table table-bordered table-striped">
                              <tbody>
                                 <tr>
                                    <td class="item">
                                       <h6 class="p-2 mb-0">Program Session</h6>
                                    </td>
                                    <td class="Hours">
                                       <h6 class="p-2 mb-0">Time</h6>
                                    </td>
                                    <td class="Rate">
                                       <h6 class="p-2 mb-0">Date</h6>
                                    </td>
                                 </tr>
                                 @foreach ($selectedSlots as $index => $slot)
                                 <tr>
                                    <td>
                                       <label>Session {{ $index + 1 }}</label>
                                    </td>
                                    <td>
                                       <p class="itemtext">{{ $slot->start_time }}</p>
                                    </td>
                                    <td>
                                       <p class="itemtext">{{ $slot->date }}</p>
                                    </td>
                                 </tr>
                                 @endforeach
                                 <tr>
                                    <td colspan="3" class="item">
                                       <h6 class="p-2 mb-0">Consultation Slot</h6>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <label>Consultation</label>
                                    </td>
                                    <td>
                                       <p class="itemtext">{{ $consultDetails['consult_time'] }}</p>
                                    </td>
                                    <td>
                                       <p class="itemtext">{{ $consultDetails['consult_date'] }}</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3" class="item">
                                       <h6 class="p-2 mb-0">Additional Session</h6>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <label>{{ $additionalSessions > 0 ? $additionalSessions . ' Sessions' : 'None' }}</label>
                                    </td>
                                    <td>
                                       <p class="itemtext">-</p>
                                    </td>
                                    <td>
                                       <p class="itemtext">-</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td></td>
                                    <td class="Rate">
                                       <h6 class="mb-0 p-2">Total Session</h6>
                                    </td>
                                    <td class="payment">
                                       <h6 class="mb-0 p-2">{{ $package->session_quantity + $additionalSessions }}</h6>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="payment"><h6 class="mb-0 p-2">RM {{ $totalPrice }}</h6></td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                        <!-- End Table-->
                        <div class="row">
                           {{-- <div class="col-md-8">
                              <div>
                                 <p class="legal"><strong>Thank you for trusting us!</strong>  Payment is expected within 7 days; please process this invoice within that time to continue our program</p>
                              </div>
                           </div> --}}
                           <div class="col-md-4">
                              <form class="text-end" action="{{ route('confirmBookSlot-sales', ['child_id' => $child_id]) }}" method="POST">
                                 @csrf
                                 <button class="btn btn-success">Submit Invoice</button>
                              </form>
                           </div>
                        </div>
                     </div>
                     <!-- End InvoiceBot-->
                  </div>
                  <div class="col-sm-12 text-center mt-3">
                     <button class="btn btn btn-primary me-2" type="button" onclick="window.print()">Print</button>
                     <button class="btn btn-secondary" type="button">Cancel</button>
                  </div>
                  <!-- End Invoice-->
                  <!-- End Invoice Holder-->
                  <!-- Container-fluid Ends-->
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
<script src="{{asset('assets/js/print.js')}}"></script>
@endsection