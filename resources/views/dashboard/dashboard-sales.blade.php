@extends('layouts.simple.master-sales')
@section('title', 'Dashboard')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('breadcrumb-title')
<h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
      <div class="col-xxl-5 col-ed-6 col-xl-8 box-col-7">
        <div class="row">
          <div class="col-sm-12">
            <div class="card o-hidden welcome-card">            
              <div class="card-body">
                <h4 class="mb-3 mt-1 f-w-500 mb-0 f-22">{{$salesName}}<span> <img src="{{ asset('assets/images/dashboard-3/hand.svg') }}" alt="hand vector"></span></h4>
                <p>You can check all the customer details in all customer page.</p>
              </div><img class="welcome-img" src="{{ asset('assets/images/dashboard-3/widget.svg') }}" alt="search image">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="card course-box"> 
              <div class="card-body"> 
                <div class="course-widget"> 
                  <div class="course-icon"> 
                    <svg class="fill-icon">
                      <use href="{{ asset('assets/svg/icon-sprite.svg#course-2') }}"></use>
                    </svg>
                  </div>
                  <div> 
                    <h4 class="mb-0">{{ $totalCustomer}} </h4><span class="f-light">Total Customer</span><a class="btn btn-light f-light" href="{{ route('newCustomer-sales')}}">View List<span class="ms-2"> 
                        <svg class="fill-icon f-light">
                          <use href="{{ asset('assets/svg/icon-sprite.svg#arrowright') }}"></use>
                        </svg></span></a>
                  </div>
                </div>
              </div>
              <ul class="square-group">
                <li class="square-1 warning"></li>
                <li class="square-1 primary"></li>
                <li class="square-2 warning1"></li>
                <li class="square-3 danger"></li>
                <li class="square-4 light"></li>
                <li class="square-5 warning"></li>
                <li class="square-6 success"></li>
                <li class="square-7 success"></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="card course-box"> 
              <div class="card-body"> 
                <div class="course-widget"> 
                  <div class="course-icon warning"> 
                    <svg class="fill-icon">
                      <use href="{{ asset('assets/svg/icon-sprite.svg#course-1') }}"></use>
                    </svg>
                  </div>
                  <div> 
                    <h4 class="mb-0">{{$newRegister}} </h4><span class="f-light">New Register</span><a class="btn btn-light f-light" href="{{ route('registeredCustomer-sales')}}">View List<span class="ms-2"> 
                        <svg class="fill-icon f-light">
                          <use href="{{ asset('assets/svg/icon-sprite.svg#arrowright') }}"></use>
                        </svg></span></a>
                  </div>
                </div>
              </div>
              <ul class="square-group">
                <li class="square-1 warning"></li>
                <li class="square-1 primary"></li>
                <li class="square-2 warning1"></li>
                <li class="square-3 danger"></li>
                <li class="square-4 light"></li>
                <li class="square-5 warning"></li>
                <li class="square-6 success"></li>
                <li class="square-7 success"></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xxl-2 col-ed-3 col-xl-4 col-sm-6 box-col-5">
        <div class="card get-card">
          <div class="card-header card-no-border">
            <h5>Sales Progress</h5><span class="f-14 f-w-500 f-light">Total Subscription</span>
          </div>
          <div class="card-body pt-0">
            <div class="progress-chart-wrap">
              <div id="progresschart"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xxl-3 col-ed-5 col-xl-5 col-sm-6 box-col-5">
        <div class="card"> 
          <div class="card-body">
            <div class="default-datepicker"> 
              <div class="datepicker-here" data-language="en"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xxl-4 col-ed-7 col-xl-7 col-md-6 box-col-7"> 
        <div class="card">
          <div class="card-header card-no-border">
            <div class="header-top"> 
              <h5>Activity Hours</h5>
              <div class="dropdown icon-dropdown">
                <button class="btn dropdown-toggle" id="activitydropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="activitydropdown"><a class="dropdown-item" href="#">Today</a><a class="dropdown-item" href="#">Tomorrow</a><a class="dropdown-item" href="#">Yesterday </a></div>
              </div>
            </div>
          </div>
          <div class="card-body pt-0">
            <div class="row m-0 overall-card">
              <div class="col-xl-8">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
                <div class="chart-right">
                  <div class="row">
                    <div class="col-xl-12">
                      <div class="card-body p-0">
                        <div class="activity-wrap">
                          <div id="activity-chart"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 p-0">
                <div class="row g-sm-3 g-2 mt-0">
                  <div class="col-xl-12 col-md-6 col-sm-4">
                    <div class="light-card balance-card">
                      <div> <span class="f-light">Time Spent</span>
                        <h6 class="mt-1 mb-0">30<span class="badge badge-light-success rounded-pill ms-1">140%</span></h6>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-12 col-md-6 col-sm-4">
                    <div class="light-card balance-card">
                      <div> <span class="f-light">Lessons taken</span>
                        <h6 class="mt-1 mb-0">45<span class="badge badge-light-success rounded-pill ms-1">86%</span></h6>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-12 col-md-6 col-sm-4">
                    <div class="light-card balance-card">
                      <div> <span class="f-light">Exams passed</span>
                        <h6 class="mt-1 mb-0">12<span class="badge badge-light-success rounded-pill ms-1">120%</span></h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
<script>
  var totalCustomer = {{ $totalCustomer }};
  var newRegister = {{ $newRegister }};
  var unregistered = {{ $unregistered }};
</script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/dashboard_sales.js') }}"></script>
@endsection
