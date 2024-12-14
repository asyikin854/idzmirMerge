@extends('layouts.simple.master-sales')
@section('title', 'Payment Status')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Payment Status</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Payment Status</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <!-- Complex headers (rowspan and colspan) Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h3>Transactions</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-6">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Std Name</th>
                                        <th>Payment ID</th>
                                        <th>Total Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Session ID</th>
                                        <th>Payment Date</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @forelse($payment as $payments)
                                    <tr>
                                        <td>{{ $loop->iteration}} </td>
                                        <td>{{$payments->childInfo->child_name}} </td>
                                        <td>{{$payments->payment_id}}</td>
                                        <td>{{$payments->total_amount}}</td>
                                        <td>{{$payments->payment_method}}</td>
                                        <td>
                                          @if ($payments->status === 'paid')
                                              <span class="badge rounded-pill badge-success">Success</span>
                                          @elseif ($payments->status === 'pending')
                                              <span class="badge rounded-pill badge-warning">Pending</span>
                                          @elseif ($payments->status === 'failed')
                                              <span class="badge rounded-pill badge-danger">Failed</span>
                                          @endif
                                      </td>
                                      <td>{{$payments->session_id}}</td>
                                        <td>{{$payments->created_at}}</td>
                                        <td>
                                            @if($payments->path)
                                            <a href="{{ asset($payments->path) }}" target="_blank">View Receipt</a>
                                        @else
                                            <p>-</p>
                                        @endif
                                        </td>
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
