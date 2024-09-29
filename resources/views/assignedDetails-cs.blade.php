@extends('layouts.simple.master-cs')
@section('title', 'Session Details')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Session Details</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Approved Sessions</li>
    <li class="breadcrumb-item active">Session Details</li>
@endsection

@section('content')
<div class="container-fluid basic_table">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td>Name: {{$childInfo->child_name}}</td>
                            <td>IC: {{$childInfo->child_ic}} </td>
                            <td>Program: {{$childInfo->package->package_name}} </td>
                        </tr>
                    </table><br>

                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Therapist</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td>Session {{ $schedule->session }}</td>
                                    <td>{{ $schedule->day }}</td>
                                    <td>{{ $schedule->time }}</td>
                                    <td>{{ $schedule->therapist}} </td>
                                </tr>
                            @endforeach
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

