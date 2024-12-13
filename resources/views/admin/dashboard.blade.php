@extends('layouts.simple.master-admin')
@section('title', ' Dashboard')

@section('css')
@endsection


@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
@endsection



@section('breadcrumb-title')
    <h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection



@section('content')
    <div class="container-fluid">
        <!-- Chart widget top Start-->
        <div class="row">
            <div class="col-xl-4 col-md-12 box-col-12">
                <div class="card o-hidden">
                    <div class="chart-widget-top">
                        <div class="row card-body pb-0 m-0">
                            <div class="col-xl-9 col-lg-8 col-9 p-0">
                                <h6 class="mb-2">Total Student</h6>
                                <h4>{{$totalStudent}} </h4>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-3 text-end p-0">
                                <h6 class="txt-success"></h6>
                            </div>
                        </div>
                        <div>
                            <div id="chart-widget2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-12 box-col-12">
                <div class="card o-hidden">
                    <div class="chart-widget-top">
                        <div class="row card-body pb-0 m-0">
                            <div class="col-xl-9 col-lg-8 col-9 p-0">
                                <h6 class="mb-2">Monthly Sales</h6>
                                <h4>RM {{$monthlySales}}</h4><span>Compare to last month</span>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-3 text-end p-0">
                                <h6 class="txt-success">{{$salesPercentage}}% </h6>
                            </div>
                        </div>
                        <div id="chart-widget1">
                            <div class="flot-chart-placeholder" id="chart-widget-top-second"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-12 box-col-12">
                <div class="card o-hidden">
                    <div class="chart-widget-top">
                        <div class="row card-body pb-0 m-0">
                            <div class="col-xl-9 col-lg-8 col-9 p-0">
                                <h6 class="mb-2">Total Sales</h6>
                                <h4>RM {{$totalSales}} </h4><span></span>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-3 text-end p-0">
                                <h6 class="txt-success"></h6>
                            </div>
                        </div>
                        <div id="chart-widget3">
                            <div class="flot-chart-placeholder" id="chart-widget-top-third"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Chart widget top Ends-->
        <!-- Chart widget with bar chart Start-->
        <div class="row">
            <div class="col-md-12 box-col-12">
                <div class="card o-hidden">
                    <div class="card-header">
                        <h5>Programs</h5>
                    </div>
                    <div class="bar-chart-widget">
                        <div class="bottom-content card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div id="chart-widget4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart widget with bar chart Ends-->

    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
    <script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
    <script src="{{ asset('assets/js/chart-widget-admin.js') }}"></script>
@endsection
