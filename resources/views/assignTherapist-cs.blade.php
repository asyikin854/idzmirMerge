@extends('layouts.simple.master-cs')
@section('title', 'Assign Therapist')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Assigning Therapist</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Assign Therapist</li>
    <li class="breadcrumb-item active">Assigning Therapist</li>
@endsection

@section('content')
<div class="container-fluid basic_table">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
              </div>
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <tr>
                          <td>{{ $childInfo->child_name }}</td>
                          <td>Age: {{ $childInfo->age }}</td>
                          <td>Package: {{ $childInfo->package->package_name }} | {{ $childInfo->package->package_step }}</td>
                      </tr>
                  </table><br>
                  </div>
                  <div class="table-responsive">
                    <form action="{{ route('assign.therapist') }}" method="POST">
                      @csrf
                      <table class="table table-border">
                          <thead>
                              <tr>
                                  <th>Session</th>
                                  <th>Day</th>
                                  <th>Time</th>
                                  <th>Assign Therapist</th>
                                  <th>Date</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($childInfo->childSchedule as $childSchedule)
                              <tr>
                                  <td>{{ $childSchedule->session }}</td>
                                  <td>{{ $childSchedule->day }}</td>
                                  <td>{{ $childSchedule->time }}</td>
                                  <td>
                                      <select name="therapist[{{ $childSchedule->id }}]" class="form-select">
                                          @foreach($availableTherapists[$childSchedule->id] as $therapist)
                                          <option value="{{ $therapist->name }}">{{ $therapist->name }}</option>
                                          @endforeach
                                      </select>
                                  </td>
                                  <td>{{ $childSchedule->date }} </td>
                                  <input type="hidden" name="status[{{ $childSchedule->id }}]" value="approved">
                                  <input type="hidden" name="schedules[]" value="{{ $childSchedule->id }}">
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                      <center><button class="btn btn-success" type="submit">Confirm</button></center>
                  </form>
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

