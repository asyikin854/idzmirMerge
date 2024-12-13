@extends('layouts.simple.master-admin')
@section('title', 'Payment Details')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Transaction Details</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">All Transactions</li>
    <li class="breadcrumb-item active">Transaction Details</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                  <div class="card-header">
                    <h3>Customer Details</h3>
                  </div>
                  <div class="card-block row">
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                      <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Username</th>
                                <td>{{ $childInfo->parentAccount->username ?? 'N/A'}}</td>
                                <th>Email</th>
                                <td>{{ $childInfo->parentAccount->email ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Student Name</th>
                                <td>{{ $childInfo->child_name ?? 'N/A'}}</td>
                                <th>Program | Sessions No</th>
                                <td>{{ $childInfo->package->package_name ?? 'N/A'}} | {{ $childInfo->package->session_quantity ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Father's Name</th>
                                <td>{{ $childInfo->fatherInfo->father_name ?? 'N/A'}}</td>
                                <th>Phone No</th>
                                <td>{{ $childInfo->fatherInfo->father_phone ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Mother's Name</th>
                                <td>{{ $childInfo->motherInfo->mother_name ?? 'N/A'}}</td>
                                <th>Phone No</th>
                                <td>{{ $childInfo->motherInfo->mother_phone ?? 'N/A'}}</td>
                            </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <!-- Zero Configuration  Starts-->
            <!-- Complex headers (rowspan and colspan) Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h3>Transactions</h3>
                        <span>The list is showing all transactions made by the customer. Click on the Session Id to view the sessions.</span>
                    </div>
                    <div class="card-body">
                        <div class="dt-ext table-responsive">
                            <table class="display" id="export-button">
                                <thead>
                                    <tr>
                                        <th>No</th>
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
                                        <td>{{$payment->payment_id}}</td>
                                        <td>{{$payment->total_amount}}</td>
                                        <td>{{$payment->payment_method}}</td>
                                        <td>
                                          @if ($payment->status === 'success')
                                              <span class="badge rounded-pill badge-success">Success</span>
                                          @elseif ($payment->status === 'pending')
                                              <span class="badge rounded-pill badge-warning">Pending</span>
                                          @elseif ($payment->status === 'failed')
                                              <span class="badge rounded-pill badge-danger">Failed</span>
                                          @endif
                                      </td>
                                      <td>  <a href="#" 
                                        data-session-id="{{ $payment->session_id }}" 
                                        class="view-session-link" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#exampleModal">
                                        {{ $payment->session_id }}
                                     </a></td>
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
    <div class="modal fade modal-bookmark" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
           <div class="modal-content">
              <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Session Schedule</h5>
                 <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                    <!-- Dynamic Content Placeholder -->
                    <table class="table table-lg" id="session-details-table">
                      <thead>
                        <tr>
                          <th scope="col">Session</th>
                          <th scope="col">Date</th>
                          <th scope="col">Time</th>
                          <th scope="col">Therapist</th>
                          <th scope="col">Attendance</th>
                        </tr>
                      </thead>
                      <tbody id="session-details-body">
                        <!-- Dynamic Rows Will Be Inserted Here -->
                      </tbody>
                    </table>
                </div>
              </div>
           </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/custom.js') }}"></script>
    <script>
        // Handle Click Event on Session ID
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.view-session-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    const sessionId = this.getAttribute('data-session-id');
                    const url = `{{ route('getChildSchedulesBySessionId') }}?session_id=${sessionId}`;
    
                    // Clear existing content
                    document.getElementById('session-details-body').innerHTML = '';
    
                    // Fetch data using AJAX
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.childSchedules) {
                                const tbody = document.getElementById('session-details-body');
                                data.childSchedules.forEach(schedule => {
                                    const row = `
                                        <tr>
                                            <td>Session ${schedule.session}</td>
                                            <td>${schedule.date}</td>
                                            <td>${schedule.time}</td>
                                            <td>${schedule.therapist ?? 'N/A'}</td>
                                            <td>${schedule.attendance ?? 'N/A'}</td>
                                        </tr>
                                    `;
                                    tbody.insertAdjacentHTML('beforeend', row);
                                });
                            } else {
                                document.getElementById('session-details-body').innerHTML = '<tr><td colspan="6">No schedules found.</td></tr>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching session details:', error);
                            document.getElementById('session-details-body').innerHTML = '<tr><td colspan="6">An error occurred.</td></tr>';
                        });
                });
            });
        });
    </script>
@endsection
