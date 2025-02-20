@extends('layouts.simple.master-admin')
@section('title', 'Details')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Parent Details</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Lists Parent</li>
    <li class="breadcrumb-item active">Details</li>
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
              <div class="card-header">
              </div>
              <div class="card-block row">
                <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>Parent Account Details</h3>
                      </div>
                      <div class="card-block row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                        @if($parentAccount)
                          <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Username</th>
                                    <td>{{ $parentAccount->username ?? 'N/A'}}</td>
                                    <th>Email</th>
                                    <td>{{ $parentAccount->email ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-center"><button class="btn btn-warning" 
                                        data-toggle="modal" 
                                        data-target="#editParentModal">Edit</button></th>
                                </tr>
                            </table>
                          </div>
                          @include('admin.parents.editParentModal') 

                          @else
                            <h5>There is no parent account for this student</h5>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>Mother Information</h3>
                      </div>
                      <div class="card-block row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                        @if($motherInfo)
                          <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $motherInfo->mother_name ?? 'N/A'}}</td>
                                    <th>Phone No</th>
                                    <td>{{ $motherInfo->mother_phone ?? 'N/A'}}</td>
                                <tr><tr>
                                  <th>IC Number / Passport No</th>
                                  <td>{{ $motherInfo->mother_ic ?? 'N/A'}}</td>
                                  <th>Race</th>
                                  <td>{{ $motherInfo->mother_race ?? 'N/A'}}</td>
                                </tr>
                                  <tr>
                                    <th>Occupation</th>
                                    <td>{{ $motherInfo->mother_occ ?? 'N/A'}}</td>
                                    <th>Email</th>
                                    <td>{{ $motherInfo->mother_email ?? 'N/A'}}</td>
                                </tr><tr>
                                    <th>Address</th>
                                    <td colspan="3">{{ $motherInfo->mother_address ?? 'N/A'}}, {{ $motherInfo->mother_posscode ?? 'N/A'}}, {{ $motherInfo->mother_city ?? 'N/A'}}</td>
                                </tr><tr>
                                    <th>Work Address</th>
                                    <td>{{ $motherInfo->mother_work_address ?? 'N/A'}}, {{ $motherInfo->mother_work_posscode ?? 'N/A'}}, {{ $motherInfo->mother_work_city ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-center"><button class="btn btn-warning" 
                                        data-toggle="modal" 
                                        data-target="#editMotherModal">Edit</button></th>
                                </tr>
                            </table>
                          </div>
                          @include('admin.parents.editMotherModal') 

                          @else
                            <h5>There are no mother Information</h5>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>Father Information</h3>
                      </div>
                      <div class="card-block row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            @if ($fatherInfo)
                          <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $fatherInfo->father_name ?? 'N/A'}}</td>
                                    <th>Phone No</th>
                                    <td>{{ $fatherInfo->father_phone ?? 'N/A'}}</td>
                                <tr><tr>
                                  <th>IC Number / Passport No</th>
                                  <td>{{ $fatherInfo->father_ic ?? 'N/A'}}</td>
                                  <th>Race</th>
                                  <td>{{ $fatherInfo->father_race ?? 'N/A'}}</td>
                                </tr>
                                  <tr>
                                    <th>Occupation</th>
                                    <td>{{ $fatherInfo->father_occ ?? 'N/A'}}</td>
                                    <th>Email</th>
                                    <td>{{ $fatherInfo->father_email ?? 'N/A'}}</td>
                                </tr><tr>
                                    <th>Address</th>
                                    <td colspan="3">{{ $fatherInfo->father_address ?? 'N/A'}}, {{ $fatherInfo->father_posscode ?? 'N/A'}}, {{ $fatherInfo->father_city ?? 'N/A'}}</td>
                                </tr><tr>
                                    <th>Work Address</th>
                                    <td>{{ $fatherInfo->father_work_address ?? 'N/A'}}, {{ $fatherInfo->father_work_posscode ?? 'N/A'}}, {{ $fatherInfo->father_work_city ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-center"><button class="btn btn-warning" 
                                        data-toggle="modal" 
                                        data-target="#editFatherModal">Edit</button></th>
                                </tr>
                            </table>
                          </div>
                          @include('admin.parents.editFatherModal')    
                   
                          @else
                                <p>There are no father Information</p>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>Student Information</h3>
                      </div>
                      <div class="card-block row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                          <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $childInfo->child_name }}</td>
                                    <th>Date of Birth, Age</th>
                                    <td>{{ $childInfo->child_dob }}, {{ $childInfo->age }}</td>
                                <tr><tr>
                                  <th>IC Number</th>
                                  <td>{{ $childInfo->child_ic }}</td>
                                  <th>Birth Place</th>
                                  <td>{{ $childInfo->child_bp }}</td>
                                </tr>
                                  <tr>
                                    <th>Sex</th>
                                    <td>{{ $childInfo->child_sex }}</td>
                                    <th>Religion</th>
                                    <td>{{ $childInfo->child_religion }}</td>
                                </tr><tr>
                                    <th>Nationality</th>
                                    <td>{{ $childInfo->child_nationality }}</td>
                                    <th>Race</th>
                                    <td>{{ $childInfo->child_race }}</td>
                                </tr><tr>
                                    <th>Address</th>
                                    <td>{{ $childInfo->child_address }} <br> {{ $childInfo->child_posscode }} <br> {{ $childInfo->child_city }}, {{ $childInfo->child_country }}</td>
                                    <th>Program</th>
                                    <td>{{ $childInfo->package->package_name ?? 'N/A'}} | {{ $childInfo->package->package_step ?? 'N/A' }} </td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-center"><button class="btn btn-warning" 
                                        data-toggle="modal" 
                                        data-target="#editChildModal">Edit Child & Intervention Details</button></th>
                                </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>Intervention / Treatment History</h3>
                      </div>
                      <div class="card-block row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                          <div class="table-responsive">
                            <table class="table table-lg">
                                @if($childInfo->pediatricians || $childInfo->recommend)
                                <tr>
                                    <th>Pediatricians</th>
                                    <td>{{ $childInfo->pediatricians ?? 'N/A' }}</td>
                                    <th>Recommended by Hospital/Clinic</th>
                                    <td>{{ $childInfo->recommend ?? 'N/A' }}</td>
                                </tr>
                                @endif
                                
                                @if($childInfo->deadline || $childInfo->diagnosis)
                                <tr>
                                    <th>Deadline Diagnose by Doctor</th>
                                    <td>{{ $childInfo->deadline ?? 'N/A' }}</td>
                                    <th>Diagnosis/Condition</th>
                                    <td>{{ $childInfo->diagnosis ?? 'N/A' }}</td>
                                </tr>
                                @endif
                                
                                @if($childInfo->occ_therapy || $childInfo->sp_therapy || $childInfo->others)
                                <tr>
                                    <th>Others Unit Involved</th>
                                    <td colspan="3">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Unit</th>
                                                <th>Occupational Therapy</th>
                                                <th>Speech Therapy</th>
                                                <th>Others</th>
                                            </tr>
                                            <tr>
                                                <th>Place</th>
                                                <td>{{ $childInfo->occ_therapy ?? 'N/A' }}</td>
                                                <td>{{ $childInfo->sp_therapy ?? 'N/A' }}</td>
                                                <td>{{ $childInfo->others ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @else
                                <td>There are no Intervention or Treatment history</td>
                                @endif
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>Student Schedule</h3>
                      </div>
                      <div class="card-block row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                          <div class="table-responsive">
                            <table class="table table-lg">
                              <thead>
                                <tr>
                                    <th scope="col">Session</th>
                                    <th scope="col">Date</th>
                                  <th scope="col">Time</th>
                                  <th scope="col">Therapist</th>  
                                  <th scope="col">Attendance</th>
                                  <th scope="col">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                @forelse ($childInfo->childSchedule as $childSchedule)
                                <tr>
                                    <td>Session {{ $childSchedule->session }}</td>
                                    <td>{{ $childSchedule->date }}</td>
                                    <td>{{ $childSchedule->time }}</td>
                                    <td>{{ $childSchedule->therapist}} </td>
                                    <td>{{ $childSchedule->attendance ?? 'N/A' }} </td>
                                    <td><a href="{{route('rescheduleView-cs', $childSchedule->id)}}"><button class="btn btn-info">Edit</button></a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No schedules found for this student .</td>
                                </tr>
                            @endforelse
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <a href="{{route('admin.parents')}}"><button class="btn btn-primary">Return</button></a>
                  </div>
              </div>
            </div>
          </div>
    </div></div>
    @include('admin.parents.editChildModal') 

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection