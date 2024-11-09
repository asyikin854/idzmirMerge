@extends('layouts.simple.master-sales')
@section('title', 'Customer Details')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Customer Details</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">All Customer</li>
    <li class="breadcrumb-item active">Registered Customer</li>
    <li class="breadcrumb-item active">Customer Details</li>
@endsection

@section('content')
<div class="container-fluid basic_table">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
              </div>
              <div class="card-block row">
                <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>Customer Information</h3>
                      </div>
                      <div class="card-block row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                          <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Child Name</th>
                                    <td>{{ $childInfo->child_name }}</td>
                                    <th>Date of Birth, Age</th>
                                    <td>{{ $childInfo->child_dob }}, {{$childInfo->age}} </td>
                                <tr><tr>
                                    <th>Father's Name</th>
                                    <td>{{ $childInfo->fatherInfo->father_name ?? 'N/A' }}</td>
                                    <th>Phone NO</th>
                                    <td>{{ $childInfo->fatherInfo->father_phone ?? 'N/A' }}</td>
                                </tr><tr>
                                    <th>Mother's Name</th>
                                    <td>{{ $childInfo->motherInfo->mother_name ?? 'N/A' }}</td>
                                    <th>Phone NO</th>
                                    <td>{{ $childInfo->motherInfo->mother_phone ?? 'N/A' }}</td>
                                </tr><tr>
                                    <th>Program</th>
                                    <td>{{ $childInfo->package->package_name ?? 'N/A' }}</td>
                                    <th>Address</th>
                                    <td>{{ $childInfo->child_address }},<br> {{ $childInfo->child_posscode }}, {{ $childInfo->child_city}}, {{ $childInfo->child_country}} </td>
                                </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
    </div></div></div></div></div>
@endsection

