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
                  <div class="dt-ext table-responsive">
                    <table class="display" id="export-button">
                      <thead>
                          <tr>
                              <th>No.</th>
                              <th>Name</th>
                              <th>Session</th>
                              <th>Day & Time</th>
                              <th>Date</th>
                              <th>Package</th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse ($schedules as $schedule)
                          <tr class="clickable-row" data-href="{{route('approvedReport-cs', $schedule->id)}}">
                                  <td>{{ $loop->iteration}} </td>
                                  <td>{{ $schedule->childInfo->child_name }}</td>
                                  <td>{{ $schedule->session }}</td>
                                  <td>{{ $schedule->day }}, {{ $schedule->time }} </td>
                                  <td>{{ $schedule->date }}</td>
                                  <td>{{ $schedule->childInfo->package->package_name}} </td>
                          </tr>
                          @empty
                          <tr>
                              <td colspan="5">There are no pending session skills update </td>
                          </tr>
                          @endforelse
                      </tbody>
                  </table>
                  </div>
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
          var rows = document.querySelectorAll('.clickable-row');
          rows.forEach(function (row) {
              row.addEventListener('click', function () {
                  window.location = this.dataset.href;
              });
          });
      });
    </script>
@endsection

