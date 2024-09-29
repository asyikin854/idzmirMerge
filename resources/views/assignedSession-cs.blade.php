@extends('layouts.simple.master-cs')
@section('title', 'Assigned Sessions')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Approved Sessions</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Assigned Sessions</li>
@endsection

@section('content')
<div class="container-fluid basic_table">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <span>Click on the name of a student to view details</span>
              </div>
              <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>No.</th>
                              <th>Name</th>
                              <th>Gender</th>
                              <th>IC Number</th>
                              <th>Age</th>
                              <th>Package</th>
                          </tr>
                      </thead>
                      <tbody>
                        @forelse ($childInfos as $childInfo)
                        <tr class="clickable-row" data-href="{{ route('assignedDetails-cs', $childInfo->id) }}">
                            <td scope="row">{{ $loop->iteration}} </td>
                            <td>{{ $childInfo->child_name}} </td>
                            <td>{{ $childInfo->child_sex}} </td>
                            <td>{{ $childInfo->child_ic}} </td>
                            <td>{{ $childInfo->age}}</td>
                            <td>{{ $childInfo->package->package_name}}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5">There are no assigned sessions</td>
                            </tr>
                        @endforelse
                  </tbody>
                  </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div></div>
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

