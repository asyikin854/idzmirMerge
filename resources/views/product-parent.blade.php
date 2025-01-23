@extends('layouts.authentication.master')
@section('title', 'Programs')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/range-slider.css')}}">
@endsection

@section('content')
<div class="container-fluid product-wrapper">
   <div class="product-grid">
     <div class="feature-products">
       <div class="row">
         <div class="col-md-6 products-total">
           <div class="square-product-setting d-inline-block"><a class="icon-grid grid-layout-view" href="#" data-original-title="" title=""><i data-feather="grid"></i></a></div>
           <div class="square-product-setting d-inline-block"><a class="icon-grid m-0 list-layout-view" href="#" data-original-title="" title=""><i data-feather="list"></i></a></div>
           <div class="grid-options d-inline-block">
             <ul>
               <li><a class="product-2-layout-view" href="#" data-original-title="" title=""><span class="line-grid line-grid-1 bg-primary"></span><span class="line-grid line-grid-2 bg-primary"></span></a></li>
               <li><a class="product-3-layout-view" href="#" data-original-title="" title=""><span class="line-grid line-grid-3 bg-primary"></span><span class="line-grid line-grid-4 bg-primary"></span><span class="line-grid line-grid-5 bg-primary"></span></a></li>
               <li><a class="product-4-layout-view" href="#" data-original-title="" title=""><span class="line-grid line-grid-6 bg-primary"></span><span class="line-grid line-grid-7 bg-primary"></span><span class="line-grid line-grid-8 bg-primary"></span><span class="line-grid line-grid-9 bg-primary"></span></a></li>
             </ul>
           </div>
         </div>
         
       </div>

     </div>
     <div class="product-wrapper-grid">
       <div class="row">
        @foreach($packages as $package)
         <div class="col-xl-3 col-sm-6 xl-4">
           <div class="card">
             <div class="product-box">
              @if($package->path && Str::endsWith($package->path, ['jpg', 'jpeg', 'png', 'gif'])) 
              {{-- Display the image if it's an image file --}}
              <div class="product-img">
                  <img class="img-fluid" src="{{ asset($package->path) }}" alt="{{ $package->package_name }}">
                  <div class="product-hover">
                      <ul>
                          <li>
                              <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#modal_{{$package->id}}">
                                  <i class="icon-eye"></i>
                              </button>
                          </li>
                      </ul>
                  </div>
              </div>
          @else
              {{-- Display a default icon or link for non-image files --}}
              <div class="product-img">
                  <img class="img-fluid" src="{{ asset('assets/images/logo/logo1.png') }}" alt="File">
                  <div class="product-hover">
                      <ul>
                        <li>
                          <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#modal_{{$package->id}}">
                              <i class="icon-eye"></i>
                          </button>
                      </li>
                      </ul>
                  </div>
              </div>
          @endif
               
               <!-- Modal for each product -->
               <div class="modal fade" id="modal_{{$package->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{$package->id}}" aria-hidden="true">
                 <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                   <div class="modal-content">
                     <div class="modal-header">
                       <div class="product-box row">
                         <div class="product-img col-lg-6"><img class="img-fluid" src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt=""></div>
                         <div class="product-details col-lg-6 text-start">
                           <h4>{{$package->package_name}}</h4>
                           <div class="product-price">RM {{$package->package_wkday_price}}
                             <del>RM {{$package->package_normal_price}}</del>
                           </div>
                           <div class="product-view">
                             <h6 class="f-w-600">Program Description</h6>
                             <ul style="list-style-type: circle; margin-left:5px">
                               @if ($package->package_long_desc1)
                                 <li>{{ $package->package_long_desc1 }}</li>
                               @endif
                               @if ($package->package_long_desc2)
                                 <li>{{ $package->package_long_desc2 }}</li>
                               @endif
                               @if ($package->package_long_desc3)
                                 <li>{{ $package->package_long_desc3 }}</li>
                               @endif
                             </ul><br>
                             <ol>
                               @if ($package->package_short_desc1)
                                 <li>{{ $package->package_short_desc1 }}</li>
                               @endif
                               @if ($package->package_short_desc2)
                                 <li>{{ $package->package_short_desc2 }}</li>
                               @endif
                               @if ($package->package_short_desc3)
                                 <li>{{ $package->package_short_desc3 }}</li>
                               @endif
                               @if ($package->package_short_desc4)
                                 <li>{{ $package->package_short_desc4 }}</li>
                               @endif
                               @if ($package->package_short_desc5)
                                 <li>{{ $package->package_short_desc5 }}</li>
                               @endif
                             </ol>
                           </div>
                           <div class="product-size">
                             <div class="table-responsive">
                               <table class="table table-bordered" style="background-color: #cfcece !important">
                                 <tr>
                                   <th>Normal Price</th>
                                   <td>RM {{$package->package_normal_price}}</td>
                                 </tr>
                                 <tr>
                                   <th>Discounted Price Weekday</th>
                                   <td>RM {{$package->package_wkday_price}}</td>
                                 </tr>
                                 <tr>
                                   <th>Discounted Price Weekend</th>
                                   <td>RM {{$package->package_wkend_price}}</td>
                                 </tr>
                               </table>
                             </div>
                           </div>
                           <div class="product-qnty">
                             <div class="addcart-btn">
                               <a class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</a>
                               <form action="{{ route('packageProceed-parent', ['child_id' => $childInfo->id, 'package_id' => $package->id]) }}" method="POST" style="display:inline;">
                                 @csrf
                                 <input type="hidden" name="package_id" value="{{$package->id}}">
                                 <button class="btn btn-primary ms-2" type="submit">Proceed</button>
                               </form>
                             </div>
                           </div>
                         </div>
                       </div>
                       <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                   </div>
                 </div>
               </div>

               <div class="product-details">
                 <a href="#" data-bs-toggle="modal" data-bs-target="#modal_{{$package->id}}">
                   <h4>{{$package->package_name}} | {{$package->package_step}} </h4></a>
                   <span style="font-size: 15px">Total Sessions: {{$package->session_quantity}}</span>
                 <div class="product-price">RM {{$package->package_wkday_price}} 
                   <del>RM {{$package->package_normal_price}}</del>
                 </div>
               </div>
             </div>
           </div>
         </div>
        @endforeach
       </div>
     </div>
   </div>
 </div>
@endsection

@section('script')
<script src="{{asset('assets/js/range-slider/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('assets/js/range-slider/rangeslider-script.js')}}"></script>
<script src="{{asset('assets/js/touchspin/vendors.min.js')}}"></script>
<script src="{{asset('assets/js/touchspin/touchspin.js')}}"></script>
<script src="{{asset('assets/js/touchspin/input-groups.min.js')}}"></script>
<script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{asset('assets/js/product-tab.js')}}"></script>
@endsection
