@extends('layouts.simple.master-admin')

@section('title', 'Ready To School Schedules')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Ready To School Schedules</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Ready To School Schedules</li>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row">
      
        <div class="col-sm-12 col-xl-6">
            <form action="{{ route('admin.child.intervention') }}" method="GET" class="form-inline">
                <label for="month" class="mr-2">Filter by Month</label>
                <input type="month" id="month" class="form-control digits" name="month" value="{{ $selectedMonth ?? now()->format('Y-m') }}" class="form-control">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
         <div class="card">
           
            <div class="card-header bg-warning">               
                    
                        <h5>Productivity Session By Team (Result)</h5>
                        <!-- Month Filter Form -->
                        
                        

            </div>
            <div class="card-body">

                                    <p><strong>Actual Active Session:</strong> {{ $actualActiveSession }}</p>
                        <p><strong>Target Active Session:</strong> {{ $targetActiveSession }}</p>
                        <p><strong>Variance Active Session:</strong> {{ $varianceActiveSession }}</p>
                        <p> <strong> Performance Capacity Utilization : </strong> {{ $PerformanceCapacityUtilization }} %</p>
                    </div>
                </div>
            </div>

                <!-- PROGRAM READY TO SCHOOL WITH ASSESSMENT BY STUDENT -->
                <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header bg-warning">               
                            
                                <h5>PROGRAM FULL ASSESSMENT BY STUDENT</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Session 4:</strong> {{ $totalChildrenPackageActive }}</p>
                        <p><strong>Session 8:</strong> {{ $totalChildrenPackageActive }}</p>
                        <p><strong>Student Intervention With Consistency:</strong> {{ $TotalActiveChild }}</p>
                        <p><strong>Performance:</strong> {{ $PerformanceActiveStudent }} %</p>
                    </div>
                        </div>
                    </div>

                    <!-- ACTIVE PROGRAM RTS CAPACITY PER HOUR -->
                <div class="col-sm-12 col-xl-6">
                    <div class="card">
                        <div class="card-header bg-info">               
                                
                            <h5>ACTIVE PROGRAM FULL ASSESSMENT CAPACITY PER HOUR</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Session 4:</strong> {{ $ActiveProgramRTS4 }} </p>
                            <p><strong>Session 8 (Active Session 8 * 8):</strong> {{ $ActiveProgram8 }} </p>
                            <p><strong>Total Hours (Present 4 + Present 8):</strong> {{ $TotalHours }}</p>
                            <p><strong>Performance (Total Hours / (Target Active Session * 2.5)):</strong> {{ $PerformanceActiveProgramByCapacityPerHour }} %</p>
                        </div>
                            </div>
                        </div>

                        <!-- PROCESS RISK A. RETENTION -->
                <div class="col-sm-12 col-xl-6">
                    <div class="card">
                        <div class="card-header bg-danger">               
                                
                            <h5>PROCESS RISK A. RETENTION</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Total inactive sessions:</strong> {{ $totalInactiveStudentByAttendance }}</p>
                            <p><strong>Total inactive students:</strong> {{ $totalInactiveStudentByStatus }}</p>
                            <p><strong>Performance Lost Risk by Session:</strong> {{ $PerformanceLostRiskBySession }} %</p>
                            <p><strong>Performance Lost Risk by Students:</strong> {{ $PerformanceLostRiskByStudent }} %</p>
                        </div>
                            </div>
                        </div>


                <div class="row">
                    <div class="card">
                                <div class="card-body">
                    <div class="dt-ext table-responsive">
                        <p>
                        <table class="display" id="export-button">
                                <thead>
                        <tr>
                            <th>Child ID</th>
                            <th>Child Name</th>
                            <th>Package</th>
                            <th>Session</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Price</th>
                            <th>Therapist</th>
                            <th>Attendance</th>
                            <th>Remark</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($children as $child)
                            <tr>
                                <td>{{ $child->child_id }}</td>
                                <td>{{ $child->child_name }}</td>
                                <td>{{ $child->package_name }}</td>
                                <td>{{ $child->session }}</td>
                                <td>{{ $child->day }}</td>
                                <td>{{ \Carbon\Carbon::parse($child->time)->format('H:i:s') }}</td>
                                <td>{{ $child->price }}</td>
                                <td>{{ $child->therapist }}</td>
                                <td>{{ $child->attendance }}</td>
                                <td>{{ $child->remark }}</td>
                                <td>{{ $child->status }}</td>
                                <td>{{ \Carbon\Carbon::parse($child->date)->format('Y-m-d') }}</td>
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
   </div>
</div>
@endsection

@section('script')
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
@endsection
