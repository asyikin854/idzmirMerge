@extends('layouts.simple.master-admin')
@section('title', 'Customer Service List')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Customer Service</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Lists Customer Service</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <!-- Complex headers (rowspan and colspan) Starts-->
            <div class="col-sm-12">

                <div class="card">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                 @endif
                    <div class="card-header pb-0 card-no-border">
                        <h3>Customer Service List</h3>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Add New Customer Service +</button><p></p>

                        <div class="table-responsive">
                            <table class="display" id="basic-6">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @forelse($csList as $cs)
                                    <tr>
                                        <td>{{ $loop->iteration}} </td>
                                        <td>{{$cs->name}}</td>
                                        <td>{{$cs->email}}</td>
                                        <td>{{$cs->username}}</td>
                                        <td>    <button class="btn btn-info edit-cs-btn" 
                                            data-id="{{ $cs->id }}"
                                            data-name="{{ $cs->name }}"
                                            data-username="{{ $cs->username }}"
                                            data-email="{{ $cs->email }}"
                                            type="button" data-bs-toggle="modal" data-bs-target="#editCsModal">
                                            Edit Credentials
                                        </button></td>
                                    </tr>
                                  @empty
                                    <tr>
                                      <td colspan="6">There are no Customer Service</td>
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
                 <h5 class="modal-title" id="exampleModalLabel">Add New Customer Service</h5>
                 <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="{{route('addNewCs')}}" method="POST" class="form-bookmark">
                    @csrf

                    <div class="row">
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="name">Name *</label>
                          <input class="form-control" name="name" id="name" type="text" required autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-12">
                        <label for="username">Username</label>
                        <input class="form-control" id="username" name="username" type="text" autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="email">Email</label>
                          <input class="form-control" id="email" name="email" type="text" autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="password">Password</label>
                          <input class="form-control" id="password" name="password" type="password" autocomplete="off">
                          <div class="show-hide">
                            <span class="show" id="togglePassword" style="cursor: pointer;"></span>
                         </div>
                       </div>
                    </div>
                    <input id="index_var" type="hidden" value="6">
                    <button class="btn btn-secondary" type="submit">Add</button>
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                 </form>
              </div>
           </div>
        </div>
     </div>


     <div class="modal fade modal-bookmark" id="editCsModal" tabindex="-1" role="dialog" aria-labelledby="editCsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
           <div class="modal-content">
              <div class="modal-header">
                 <h5 class="modal-title" id="editCsModalLabel">Edit Customer Service Details</h5>
                 <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="editCsForm" action="{{ route('updateCs') }}" method="POST" class="form-bookmark">
                    @csrf
                    <input type="hidden" name="cs_id" id="editCsId">
                    <div class="row">
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="edit_name">Name *</label>
                          <input class="form-control" name="name" id="edit_name" type="text" required autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="edit_username">Username</label>
                          <input class="form-control" id="edit_username" name="username" type="text" autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="edit_email">Email</label>
                          <input class="form-control" id="edit_email" name="email" type="text" autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="edit_password">Password (Leave this field empty if you don't want to change the password)</label>
                          <input class="form-control" id="edit_password" name="password" type="password" autocomplete="off">
                       </div>
                    </div>
                    <button class="btn btn-secondary" type="submit">Update</button>
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                 </form>
              </div>
           </div>
        </div>
    </div>
    
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            
            // Check the current type of the password input
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text'; // Change the type to text
                togglePassword.textContent = ''; // Change button text
            } else {
                passwordInput.type = 'password'; // Change the type back to password
                togglePassword.textContent = ''; // Change button text
            }
        });

        document.querySelectorAll('.edit-cs-btn').forEach(button => {
    button.addEventListener('click', function () {
        // Get therapist data from the button
        const CsId = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const username = this.getAttribute('data-username');
        const email = this.getAttribute('data-email');

        // Populate the modal fields
        document.getElementById('editCsId').value = CsId;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;

        // Password field remains empty as we typically don't pre-fill passwords for security reasons
    });
});

     </script>
@endsection
