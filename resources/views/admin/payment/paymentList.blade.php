@extends('layouts.simple.master-admin')
@section('title', 'Payment Status')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Transaction List</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">All Transactions</li>
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
                        <span>The list is showing the latest transactions made by each customer. Click on the name of the student to view more.</span>
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
                                  @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration}} </td>
                                        <td><a href="{{route('admin.payment.details', $payment->childInfo->id)}}">{{$payment->childInfo->child_name}}</a> </td>
                                        <td>{{$payment->payment_id}}</td>
                                        <td>{{$payment->total_amount}}</td>
                                        <td>{{$payment->payment_method}}</td>
                                        <td>
                                          @if ($payment->status === 'paid')
                                              <span class="badge rounded-pill badge-success">Success</span>
                                          @elseif ($payment->status === 'pending')
                                              <span class="badge rounded-pill badge-warning">Pending</span>
                                          @elseif ($payment->status === 'failed')
                                              <span class="badge rounded-pill badge-danger">Failed</span>
                                          @endif
                                      </td>
                                      <td>{{$payment->session_id}}</td>
                                        <td>{{$payment->created_at}}</td>
                                        <td>
                                            @if($payment->path)
                                            <a href="{{ asset('storage/' . $payment->path) }}" target="_blank">View Receipt</a>
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
