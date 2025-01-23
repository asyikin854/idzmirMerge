@extends('layouts.simple.master-admin')
@section('title', 'Therapist List')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Therapists</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Lists Therapists</li>
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
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                 @endif
                    <div class="card-header pb-0 card-no-border">
                        <h3>Therapist List</h3>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Add New Therapist +</button><p></p>

                        <div class="table-responsive">
                            <table class="display" id="basic-6">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @forelse($therapists as $therapist)
                                    <tr>
                                        <td>{{ $loop->iteration}} </td>
                                        <td>{{$therapist->name}}</td>
                                        <td>{{$therapist->email}}</td>
                                        <td>{{$therapist->username}}</td>
                                        <td>    <button class="btn btn-info edit-therapist-btn" 
                                            data-id="{{ $therapist->id }}"
                                            data-name="{{ $therapist->name }}"
                                            data-username="{{ $therapist->username }}"
                                            data-email="{{ $therapist->email }}"
                                            type="button" data-bs-toggle="modal" data-bs-target="#editTherapistModal">
                                            Edit Credentials
                                        </button></td>
                                        <td><form action="{{ route('admin.therapist.destroy', $therapist->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this therapist?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form></td>
                                    </tr>
                                  @empty
                                    <tr>
                                      <td colspan="6">There are no therapist</td>
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
                 <h5 class="modal-title" id="exampleModalLabel">Add New Therapist</h5>
                 <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="{{route('addNewTherapist')}}" method="POST" class="form-bookmark">
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


     <div class="modal fade modal-bookmark" id="editTherapistModal" tabindex="-1" role="dialog" aria-labelledby="editTherapistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
           <div class="modal-content">
              <div class="modal-header">
                 <h5 class="modal-title" id="editTherapistModalLabel">Edit Therapist</h5>
                 <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="editTherapistForm" action="{{ route('updateTherapist') }}" method="POST" class="form-bookmark">
                    @csrf
                    <input type="hidden" name="therapist_id" id="editTherapistId">
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

        document.querySelectorAll('.edit-therapist-btn').forEach(button => {
    button.addEventListener('click', function () {
        // Get therapist data from the button
        const therapistId = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const username = this.getAttribute('data-username');
        const email = this.getAttribute('data-email');

        // Populate the modal fields
        document.getElementById('editTherapistId').value = therapistId;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;

        // Password field remains empty as we typically don't pre-fill passwords for security reasons
    });
});

     </script>
@endsection
