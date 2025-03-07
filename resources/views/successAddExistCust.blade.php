@extends('layouts.authentication.master')
@section('title', 'Schedules')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container">
    <div class="row">
       <div class="col-sm-12">
          <div class="card">
             <div class="card-header">
                <h5>Existing Customer Record has been added successfully</h5>
                <span>Verification email and invoice has been sent to parent.</span>
                <span>Refresh the Registered Customer in customer service to view the new record submitted</span>
             </div>
          </div>
          <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display" id="basic-6">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Std Name</th>
                                <th>Program</th>
                                <th>Registered At</th>
                            </tr>
                        </thead><tbody>
                            @foreach($childInfos as $childInfo)
                            <tr>
                                <td>{{$loop->iteration}} </td>
                                <td>{{$childInfo->child_name}} </td>
                                <td>{{$childInfo->package->package_name ?? 'N/A'}} </td>
                                <td>{{$childInfo->created_at}} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br><hr><br>
                <a href="{{route('register-parent')}}"><button class="btn btn-primary">+ Add Existing Customer</button></a>
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