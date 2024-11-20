<!-- resources/views/admin/parents/index.blade.php -->
@extends('layouts.simple.master-admin')
@section('title', 'Bootstrap Basic Tables')

@section('css')
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">

@endsection

@section('css')

@section('breadcrumb-title')
    <h3>Parents Lists</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Parents Lists</li>
@endsection


@section('content')
<div class="container-fluid">
  <div class="row">
<div class="col-sm-12">
                <div class="card">
                  <div class="card-header">
                    <h3>Parent List</h3>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive">
                        <table class="display" id="basic-6">
                          <thead>
                            <tr>
                               <th>No.</th>
                               <th>Std Name</th>
                               <th>Father Name</th>
                               <th>Mother Name</th>
                               <th>Username</th>
                               <th>Email</th>
                               <th>Program | Sessions</th>
                               <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                            @foreach($childInfos as $childInfo)
                            <tr>
                                <td>{{$loop->iteration}} </td>
                                <td>{{ $childInfo->child_name }}</td>
                                <td>{{ $childInfo->fatherInfo->father_name ?? 'N/A' }}</td>
                                <td>{{ $childInfo->motherInfo->mother_name ?? 'N/A' }}</td>
                                <td>{{ $childInfo->parentAccount->username ?? 'N/A'}}</td>
                                <td>{{ $childInfo->parentAccount->email ?? 'N/A'}}</td>
                                <td>{{ $childInfo->package->package_name ?? 'N/A'}} | {{ $childInfo->package->session_quantity ?? 'N/A'}}</td>
                                <td>
                                    <!-- Example of an action -->
                                    <a href="{{ route('admin.parents.show', $childInfo->id) }}" class="btn btn-info">View Profile</a>
                                </td>
                            </tr>
                            @endforeach      
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection