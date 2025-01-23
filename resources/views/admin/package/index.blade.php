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
    <h3>All Products & Services</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Product & Services</li>
@endsection


@section('content')
<div class="container-fluid">
  <div class="row">
<div class="col-sm-12">
                <div class="card">
                  <div class="card-header">
                    <h3>Product & Services List</h3>
                  </div>
                  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                  <div class="card-body">
                      <div class="dt-ext table-responsive">
                        <a href="{{ route('admin.package.create') }}" class="btn btn-secondary">+ Add New Package</a><br><br>
<table class="display" id="export-button">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Step</th>
            <th>Normal Price</th>
            <th>Weekday Price</th>
            <th>Weekend Price</th>
            {{-- <th>Long Description 1</th>
            <th>Long Description 2</th>
            <th>Long Description 3</th>
            <th>Short Description 1</th>
            <th>Short Description 2</th>
            <th>Short Description 3</th>
            <th>Short Description 4</th>
            <th>Short Description 5</th> --}}
            <th>Citizenship</th>
            <th>Weekly</th>
            <th>Consultation</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($packages as $package)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $package->package_name }}</td>
            <td>{{ $package->package_step }}</td>
            <td>{{ $package->package_normal_price }}</td>
            <td>{{ $package->package_wkday_price }}</td>
            <td>{{ $package->package_wkend_price }}</td>
            {{-- <td>{{ $package->package_long_desc1 }}</td>
            <td>{{ $package->package_long_desc2 }}</td>
            <td>{{ $package->package_long_desc3 }}</td>
            <td>{{ $package->package_short_desc1 }}</td>
            <td>{{ $package->package_short_desc2 }}</td>
            <td>{{ $package->package_short_desc3 }}</td>
            <td>{{ $package->package_short_desc4 }}</td>
            <td>{{ $package->package_short_desc5 }}</td> --}}
            <td>{{ ucfirst($package->citizenship) }}</td>
            <td>{{ ucfirst($package->weekly) }}</td>
            <td>{{ ucfirst($package->consultation) }}</td>
            <td>
                <a href="{{ route('admin.package.edit', $package->id) }}" class="btn btn-info">Edit</a><br><br>
                <form action="{{ route('admin.package.destroy', $package->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this package?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">&nbsp; Delete &nbsp;</button>
                </form>
            </td>
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