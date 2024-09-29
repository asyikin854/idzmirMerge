@extends('layouts.simple.master-cs')
@section('title', 'Report List')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Pending Report Approval</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Report Approval</li>
@endsection

@section('content')
<div class="container-fluid basic_table">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>No.</th>
                              <th>Name</th>
                              <th>Session</th>
                              <th>Day & Time</th>
                              <th>Date</th>
                              <th>Package</th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse ($schedules as $schedule)
                          <tr class="clickable-row" data-href="{{route('reportApproval-cs', $schedule->id)}}">
                                  <td>{{ $loop->iteration}} </td>
                                  <td>{{ $schedule->childInfo->child_name }}</td>
                                  <td>{{ $schedule->session }}</td>
                                  <td>{{ $schedule->day }}, {{ $schedule->time }} </td>
                                  <td>{{ $schedule->date }}</td>
                                  <td>{{ $schedule->childInfo->package->package_name}} </td>
                          </tr>
                          @empty
                          <tr>
                              <td colspan="5">There are no pending session skills update </td>
                          </tr>
                          @endforelse
                      </tbody>
                  </table>
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

