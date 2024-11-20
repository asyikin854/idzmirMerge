@extends('layouts.simple.master-cs')
@section('title', 'Reschedule Request')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Reschedule Request</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Reschedule Approval</li>
@endsection

@section('content')
<div class="container-fluid basic_table">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
           @endif
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <caption>List of student</caption>
                      <thead>
                        <tr>
                          <th scope="col">No</th>
                          <th scope="col">Name</th>
                          <th scope="col">IC Number</th>
                          <th scope="col">Package</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse ($childInfos as $childInfo)
                        <tr class="clickable-row" data-href="{{ route('approveReqView-cs', $childInfo->childSchedule->first()->id) }}">
                            <td scope="row">{{ $loop->iteration}} </td>
                            <td>{{ $childInfo->child_name}} </td>
                            <td>{{ $childInfo->child_ic}} </td>
                            <td>{{ $childInfo->package->package_name}}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4">There are no pending Reschedule Approval</td>
                            </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div></div>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          var rows = document.querySelectorAll('.clickable-row');
          rows.forEach(function (row) {
              row.addEventListener('click', function () {
                  window.location = this.dataset.href;
              });
          });
      });
    </script>
@endsection

