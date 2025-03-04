@extends('layouts.simple.master-cs')
@section('title', 'Report List')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">

@endsection

@section('breadcrumb-title')
    <h3>Approved Report List</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Approved Reports</li>
@endsection

@section('content')
<div class="container-fluid basic_table">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('approvedReportList-cs') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="name">Filter by Name:</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}" placeholder="Enter name">
                            </div>
                            <div class="col-md-4">
                                <label for="date">Filter by Date:</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-4 align-self-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('approvedReportList-cs') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
              <div class="card-body">
                <form id="bulk-action-form" method="POST" action="{{ route('bulkDownloadReports-cs') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select name="bulk_action" class="form-select" id="bulk-action">
                                <option value="">Bulk Actions</option>
                                <option value="download">Download Selected Reports</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" id="apply-bulk-action">Apply</button>
                        </div>
                    </div>
                  <div class="dt-ext table-responsive">
                    <table class="display" id="export-button">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th> <!-- Checkbox for selecting all -->
                                <th>No.</th>
                                <th>Name</th>
                                <th>Session</th>
                                <th>Day & Time</th>
                                <th>Date</th>
                                <th>Package</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schedules as $schedule)
                            <tr>
                                <td><input type="checkbox" name="selected_reports[]" value="{{ $schedule->sessionReport->id }}"></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $schedule->childInfo->child_name }}</td>
                                <td>{{ $schedule->session }}</td>
                                <td>{{ $schedule->day }}, {{ $schedule->time }}</td>
                                <td>{{ $schedule->date }}</td>
                                <td>{{ $schedule->childInfo->package->package_name }}</td>
                                <td><a href="{{ route('approvedReport-cs', $schedule->id) }}"><button type="button" class="btn btn-info">View</button></a></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">There are no approved reports.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                  </div>
                </form>
              </div>
            </div>
          </div>
    </div></div>
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
    document.addEventListener('DOMContentLoaded', function () {
        // Select All Checkbox
        document.getElementById('select-all').addEventListener('change', function () {
            var checkboxes = document.querySelectorAll('input[name="selected_reports[]"]');
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });

        // Bulk Action Form Submission
        document.getElementById('bulk-action-form').addEventListener('submit', function (e) {
            var selectedReports = document.querySelectorAll('input[name="selected_reports[]"]:checked');
            if (selectedReports.length === 0) {
                e.preventDefault();
                alert('Please select at least one report to proceed.');
            }
        });
    });
</script>
@endsection

