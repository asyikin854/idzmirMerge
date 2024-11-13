@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid p-0">
   <div class="row m-0">
      <div class="col-12 p-0">
         <div class="login-card">
            <div>
               <div><a class="logo" href="#"><img class="img-fluid for-light" style="width:150px" src="{{asset('assets/images/logo/logoidzmir.png')}}" alt="looginpage">
                  <img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage" ></a></div>
               <div class="login-main">
                  <form action="{{route('login.post')}}" method="POST" class="theme-form">
                     @csrf
                     <h4>Sign in to account</h4>
                     <p>Enter your Username & password to login</p>

                     <!-- Error message display -->
                     @if ($errors->any())
                        <div class="alert alert-danger">
                           <ul>
                              @foreach ($errors->all() as $error)
                                 <li>{{ $error }}</li>
                              @endforeach
                           </ul>
                        </div>
                     @endif

                     <div class="form-group">
                        <label class="col-form-label">Username</label>
                        <input class="form-control" type="text" name="username" value="{{ old('username') }}" required placeholder="Enter your username">
                        <!-- Display validation error for username -->
                        @error('username')
                           <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>

                     <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" id="password" name="password" required placeholder="*********">
                        <!-- Display validation error for password -->
                        @error('password')
                           <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="show-hide">
                           <span class="show" id="togglePassword" style="cursor: pointer;"></span>
                        </div>
                     </div>

                     <div class="form-group mb-0">
                        <br>
                        
                        <button class="btn btn-primary" type="submit" style="background-color: #1a1a2e">Sign in</button>
                     </div>

                     <p class="mt-4 mb-0">For new customer please register<a class="ms-2" href="{{  route('register-parent') }}">Register</a></p>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
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
</script>
@endsection
