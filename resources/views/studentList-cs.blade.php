@extends('layouts.simple.master-cs')
@section('title', 'Student List')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Students</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">All Student</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <!-- Complex headers (rowspan and colspan) Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h3>Student List</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-6">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>IC Number</th>
                                        <th>Date of Birth</th>
                                        <th>Age</th>
                                        <th>Nationality</th>
                                        <th>Race</th>
                                        <th>Birth Place</th>
                                        <th>Religion</th>
                                        <th>Sex</th>
                                        <th>Country</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @forelse($childInfos as $childInfo)
                                    <tr>
                                        <td>{{ $loop->iteration}} </td>
                                        <td><a href="{{route('stdDetails-cs', $childInfo->id)}}">{{$childInfo->child_name}}</a></td>
                                        <td>{{$childInfo->child_ic}}</td>
                                        <td>{{$childInfo->child_dob}}</td>
                                        <td>{{$childInfo->age}}</td>
                                        <td>{{$childInfo->child_nationality}}</td>
                                        <td>{{$childInfo->child_race}}</td>
                                        <td>{{$childInfo->child_bp}}</td>
                                        <td>{{$childInfo->child_religion}}</td>
                                        <td>{{$childInfo->child_sex}}</td>
                                        <td>{{$childInfo->child_country}}</td>
                                    </tr>
                                  @empty
                                    <tr>
                                      <td colspan="6">There are no transaction history</td>
                                    </tr>
                                  @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Complex headers (rowspan and colspan) Ends-->
            
            <!-- Scroll - vertical dynamic Ends-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection
