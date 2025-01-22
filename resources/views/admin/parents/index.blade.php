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
                  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                  <div class="card-body">
                      <div class="dt-ext table-responsive">
                        <table class="display" id="export-button">
                          <thead>
                            <tr>
                               <th>No.</th>
                               <th>Std Name</th>
                               <th>Father Name</th>
                               <th>Mother Name</th>
                               <th>Username</th>
                               <th>Email</th>
                               <th>Program | Sessions</th>
                               <th>Edit</th>
                               <th>Delete</th>
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
                                <td><button type="button" class="btn btn-danger" onclick="confirmDelete('{{ route('admin.parents.destroy', $childInfo->id) }}')">
                                  Delete
                              </button></td>
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
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              Are you sure you want to delete this student data including all other relatede record? This action cannot be undone.
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <form id="deleteForm" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">Delete</button>
              </form>
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
  function confirmDelete(actionUrl) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = actionUrl; // Set the form action dynamically
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteModal.show(); // Show the modal
}

</script>
@endsection