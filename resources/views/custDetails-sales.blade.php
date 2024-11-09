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
    <li class="breadcrumb-item">Customers</li>
    <li class="breadcrumb-item">New Customer</li>
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
                                    <th>Name</th>
                                    <td>{{ $customer->name }}</td>
                                    <th>Phone</th>
                                    <td>{{ $customer->phone }}</td>
                                <tr><tr>
                                    <th>Email</th>
                                    <td>{{ $customer->email }}</td>
                                    <th>Status</th>
                                    <td>{{ $customer->status }}</td>
                                </tr><tr>
                                    <th>Progress</th>
                                    <td>{{ $customer->progress }}</td>
                                    <th>Address</th>
                                    <td>{{ $customer->address }}</td>
                                </tr><tr>
                                    <th>Remark</th>
                                    <td>{{ $customer->remark }}</td>
                                    <th>Posscode, city, country</th>
                                    <td>{{ $customer->posscode }}, {{ $customer->city}}, {{ $customer->country}} </td>
                                </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
    </div></div></div></div></div>
@endsection

