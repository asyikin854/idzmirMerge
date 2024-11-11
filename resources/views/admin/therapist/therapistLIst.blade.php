@extends('layouts.simple.master-admin')
@section('title', 'Student List')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Therapists</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Lists Therapists</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <!-- Complex headers (rowspan and colspan) Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h3>Therapist List</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-6">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @forelse($therapists as $therapist)
                                    <tr>
                                        <td>{{ $loop->iteration}} </td>
                                        <td>{{$therapist->name}}</td>
                                        <td>{{$therapist->email}}</td>
                                        <td>{{$therapist->username}}</td>
                                        <td><a href="#"><button class="btn btn-info">Edit Credentials</button></a></td>
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
