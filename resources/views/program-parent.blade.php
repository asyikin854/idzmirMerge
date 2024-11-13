@extends('layouts.simple.master-parent')
@section('title', 'Program')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Program Details</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Program</li>
@endsection

@section('content')
<div class="container-fluid">

  <div class="row">
    <div class="card">
  <div class="col-xs-12">
    <div class="col-md-12">
      <div class="mb-6">
          <h5>{{ $package->package_step }} | {{ $package->package_name }}</h5>
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
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <th>Monthly Price</th>
              </tr><tr>
                <td>RM {{ $childSchedules->price }}</td>
              </tr>
            </table>
          </div>      
      </div>
      @php
    $isPastAllDates = true;
    $currentDate = \Carbon\Carbon::today();

    foreach ($pendingSchedules as $schedule) {
        if (\Carbon\Carbon::parse($schedule->date)->gte($currentDate)) {
            $isPastAllDates = false;
            break;
        }
    }
@endphp
      <div class="col-md-12">
      <div class="mb-6">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Session</th>
                <th>Status</th>
                <th>Date</th>
                <th>Day</th>
                <th>Time</th>
                <th>Type</th>
                <th>Therapist</th>
              </tr></thead>
              <tbody>
              @foreach ($pendingSchedules as $schedule)
                  <tr>
                    <td>{{$schedule->session}} </td>
                    <td>@if ($schedule->status === 'approved')
                      <span class="badge rounded-pill badge-success">Approved</span>
                  @elseif ($schedule->status === 'pending')
                      <span class="badge rounded-pill badge-warning">Pending</span>
                  @elseif ($schedule->status === 'request')
                      <span class="badge rounded-pill badge-warning">Request</span>
                  @elseif ($schedule->status === 'disapproved')
                      <span class="badge rounded-pill badge-danger">Rejected</span>
                  @endif</td>
                    <td>{{$schedule->date}} </td>
                    <td>{{$schedule->day}} </td>
                    <td>{{$schedule->time}} </td>
                    <td>{{$schedule->type}} </td>
                    <td>{{$schedule->therapist}} </td>
                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <br><br>
      <div class="mb-6">
        <center>
          <a href="{{route('changeProgram-parent', ['child_id' => $childInfo->id, 'package_id' => $package->id])}}" 
          onclick="return {{ $isPastAllDates ? 'true' : 'false' }};"
          style="{{ !$isPastAllDates ? 'pointer-events: none; cursor: not-allowed;' : '' }}">
         <button type="button" 
                 class="btn btn-primary" 
                 {{ !$isPastAllDates ? 'disabled' : '' }}>
           Change Program
         </button>
       </a>
        </center>
      </div>
    </div>
  </div>
</div></div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
@endsection
