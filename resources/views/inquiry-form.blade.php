@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid p-0">
 
   <div class="row d-flex justify-content-center align-items-center" style="height: 100%;">
         <div class="col-md-6">
            <div class="card">
              <center>
              <div><a class="logo" href="#"><img class="img-fluid for-light" style="width:150px;" src="{{asset('assets/images/logo/logoidzmir.png')}}" alt="looginpage">
                <img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage" ></a></div></center>
             <div class="login-main">
              @if(session('success'))
   <div class="alert alert-success alert-dismissible fade show" role="alert">
       {{ session('success') }}
       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>
@endif
              <div class="card-header">
               <h5>Inquiry Form</h5><span>Send us your details so our team can contact you.</span>
              </div>
              <div class="card-body">
               <form class="theme-form" action="{{route('inquiry-submit')}}" method="POST">
                @csrf
                 <div class="mb-3 row">
                  <label class="col-sm-3 col-form-label" for="name">Name *</label>
                  <div class="col-sm-9">
                    <input class="form-control" name="name" id="name" type="text" placeholder="Full name" required>
                  </div>
                 </div>
                 <div class="mb-3 row">
                  <label class="col-sm-3 col-form-label" for="phone">Phone NO. *</label>
                  <div class="col-sm-9">
                    <input class="form-control" name="phone" id="phone" type="number" placeholder="01XXXXXXXX" requireds>
                  </div>
                 </div>
                 <div class="mb-3 row">
                  <label class="col-sm-3 col-form-label" for="email">Email</label>
                  <div class="col-sm-9">
                    <input class="form-control" id="email" name="email" type="email" placeholder="">
                  </div>
                 </div>
                 <div class="mb-3 row">
                  <label class="col-sm-3 col-form-label" for="address">Address</label>
                  <div class="col-sm-9">
                    <input class="form-control" id="address" name="address" type="text" placeholder="">
                  </div>
                 </div>
                 <div class="mb-3 row">
                  <label class="col-sm-3 col-form-label" for="posscode">Posscode</label>
                  <div class="col-sm-9">
                    <input class="form-control" id="posscode" name="posscode" type="number" placeholder="">
                  </div>
                 </div>
                 <div class="mb-3 row">
                  <label class="col-sm-3 col-form-label" for="city">City</label>
                  <div class="col-sm-9">
                    <input class="form-control" id="city" name="city" type="text" placeholder="">
                  </div>
                 </div>
                 <div class="mb-3 row">
                  <label class="col-sm-3 col-form-label" for="country">Country</label>
                  <div class="col-sm-9">
                     @php
                        $selectedCountry = ''; // Default value
                        $countries = [
                            'Malaysia', 'Afghanistan', 'Åland Islands', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Congo, The Democratic Republic of The', 'Cook Islands', 'Costa Rica', 'Cote D\'ivoire', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guernsey', 'Guinea', 'Guinea-bissau', 'Guyana', 'Haiti', 'Heard Island and Mcdonald Islands', 'Holy See (Vatican City State)', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran, Islamic Republic of', 'Iraq', 'Ireland', 'Isle of Man', 'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, Democratic People\'s Republic of', 'Korea, Republic of', 'Kuwait', 'Kyrgyzstan', 'Lao People\'s Democratic Republic', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libyan Arab Jamahiriya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macao', 'Macedonia, The Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia, Federated States of', 'Moldova, Republic of', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestinian Territory, Occupied', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russian Federation', 'Rwanda', 'Saint Helena', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Pierre and Miquelon', 'Saint Vincent and The Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and The South Sandwich Islands', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen', 'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic', 'Taiwan', 'Tajikistan', 'Tanzania, United Republic of', 'Thailand', 'Timor-leste', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Venezuela', 'Viet Nam', 'Virgin Islands, British', 'Virgin Islands, U.S.', 'Wallis and Futuna', 'Western Sahara', 'Yemen', 'Zambia', 'Zimbabwe'
                        ];
                    @endphp
                    
                    <select id="country" name="country" class="form-select">
                        <option value="">--- Select Country ---</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ $selectedCountry == $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                  </div>
                 </div>
                 <div class="mb-3 row">
                  <label class="col-sm-3 col-form-label" for="remark">Remark</label>
                  <div class="col-sm-9">
                     <textarea class="form-control" name="remark" autocomplete="off">  </textarea>
                  </div>
                 </div>
                 <div class="d-flex justify-content-center">
                  <button type="submit" class="btn btn-success">Submit</button>
               </div>
               </form> 
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
