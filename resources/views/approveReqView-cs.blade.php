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
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Child Name</th>
                                <td>{{ $childInfo->child_name }} </td>
                                <th>Program</th>
                                <td>{{ $childInfo->package->package_name }} | {{ $childInfo->package->package_step }}
                                    <br>{{ $childInfo->package->session_quantity }} Sessions
                                </td>
                            </tr><tr>
                                <th>Mother Phone No.</th>
                                <td>{{ $childInfo->motherInfo->mother_phone }}</td>
                                <th>Father Phone No.</th>
                                <td>{{ $childInfo->fatherInfo->father_phone }}</td>
                            </tr>
                        </table>
                    </div>
                </div></div></div>
                <div class="card">
                <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                <span>Reschedule request date and time</span><br>
                  <form id="rescheduleForm" action="{{ route('cs.approveReq', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Therapist</th>
                                <th>Action</th>
                            </tr>
                        </thead><tbody>
                            <tr>
                                <td>{{ $schedule->date }}</td>
                                <td>{{ $schedule->day }}</td>
                                <td>{{ $schedule->time }}</td>
                                <td><select name="therapist" id="therapist" class="form-select" required>
                                    <option selected disabled>Available therapist</option>
                                    @foreach($availableTherapists as $therapist)
                                        <option value="{{ $therapist->name }}">{{ $therapist->name }}</option>
                                    @endforeach
                                </select></td>
                                <td><select name="status" id="status" class="form-select" required>
                                    <option selected disabled>status</option>
                                    <option value="approved">approved</option>
                                    <option value="pending">reject</option>    
                                </select></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                </div>
              </div>
            </div>
          </div>
    </div></div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the form element
            var form = document.getElementById('rescheduleForm');
            var statusSelect = document.getElementById('status');
  
            // Add event listener on form submission
            form.addEventListener('submit', function (e) {
                var selectedStatus = statusSelect.value;
  
                // Check if the selected status is "pending"
                if (selectedStatus === 'pending') {
                    // Prevent form submission
                    e.preventDefault();
  
                    // Show the custom alert
                    alert('This reschedule request is rejected, you will be redirected to the student details page to change the session slots.');
  
                    // After alert, redirect to stdDetails-cs
                    window.location.href = "{{ route('stdDetails-cs', ['id' => $schedule->child_id]) }}";
                }
            });
        });
      </script>
@endsection

