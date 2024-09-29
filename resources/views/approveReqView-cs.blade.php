@extends('layouts.simple.master-cs')
@section('title', 'Reschedule Approve')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Reschedule Approve</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Reschedule Approval</li>
    <li class="breadcrumb-item active">Reschedule Approve</li>
@endsection

@section('content')
<div class="container-fluid basic_table">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <form action="{{ route('cs.approveReq', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Therapist</th>
                            </tr>
                        </thead><tbody>
                            <tr>
                                <td>{{ $schedule->date }}</td>
                                <td>{{ $schedule->day }}</td>
                                <td>{{ $schedule->time }}</td>
                                <td><select name="therapist" id="therapist" class="form-select" required>
                                    @foreach($availableTherapists as $therapist)
                                        <option value="{{ $therapist->name }}">{{ $therapist->name }}</option>
                                    @endforeach
                                </select></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Approve</button>
                </form>
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

