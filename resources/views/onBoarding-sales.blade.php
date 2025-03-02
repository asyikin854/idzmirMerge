@extends('layouts.simple.master-sales')
@section('title', 'Registered Customer')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>On Boarding Customers</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Customer</li>
    <li class="breadcrumb-item active">On Boarding</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <!-- Complex headers (rowspan and colspan) Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h3>Customer list</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-6">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Child Name</th>
                                        <th>Program</th>
                                        <th>Registered at</th>
                                        <th>Father Name</th>
                                        <th>Mother Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @forelse($childInfos as $childInfo)
                                    <tr>
                                        <td>{{ $loop->iteration}} </td>
                                        <td><a href="{{route('custDetails2-sales', $childInfo->id)}}">{{$childInfo->child_name}}</a></td>
                                        <td>{{$childInfo->package->package_name ?? 'N/A'}}</td>
                                        <td>{{$childInfo->created_at}}</td>
                                        <td>{{$childInfo->fatherInfo->father_name ?? 'N/A' }}</td>
                                        <td>{{$childInfo->motherInfo->mother_name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($childInfo->status === 'in progress')
                                                <span class="badge rounded-pill badge-warning">In Progress</span>
                                            @elseif ($childInfo->status === 'done')
                                                <span class="badge rounded-pill badge-success">Done</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($childInfo->status === 'done')
                                                <a href="{{route('regOnBoarding-sales', $childInfo->id)}}">
                                                    <button class="btn btn-info">-> FA</button>
                                                </a>
                                            @else
                                                <button class="btn btn-info" disabled>-> FA</button>
                                            @endif
                                        </td>
                                    </tr>
                                  @empty
                                    <tr>
                                      <td colspan="6">There are no registered Customer</td>
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
