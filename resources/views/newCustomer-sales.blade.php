@extends('layouts.simple.master-sales')
@section('title', 'New Customer')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>New Customers</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Customer</li>
    <li class="breadcrumb-item active">New Customer</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <!-- Complex headers (rowspan and colspan) Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h3>Customer list</h3>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Add New Customer +</button><p></p>
                        <div class="table-responsive">
                            <table class="display" id="basic-6">
                                
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Country</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @forelse($customers as $customer)
                                    <tr>
                                        <td>{{ $loop->iteration}} </td>
                                        <td><a href="{{route('custDetails-sales', $customer->id)}}">{{$customer->name}}</a></td>
                                        <td>{{$customer->phone}}</td>
                                        <td>{{$customer->email}}</td>
                                        <td>{{$customer->country}}</td>
                                        <td>{{$customer->status}}</td>
                                        <td>{{$customer->progress}}</td>
                                        <td>{{$customer->Remark}}</td>
                                        <td><a href="{{route('regNewCust-sales', $customer->id)}}"><button class="btn btn-warning">Proceed</button></a></td>
                                    </tr>
                                  @empty
                                    <tr>
                                      <td colspan="8">There are no new customer</td>
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
                 <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
                 <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                 <form action="{{route('addCustomer')}}" method="POST" class="form-bookmark">
                    @csrf

                    <div class="row">
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="name">Name *</label>
                          <input class="form-control" name="name" id="name" type="text" required autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="phone">Phone NO *</label>
                          <input class="form-control" id="phone" name="phone" type="text" required autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-12">
                          <label for="email">Email</label>
                          <input class="form-control" id="email" name="email" type="text" autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-12">
                        <label for="address">Address</label>
                        <input class="form-control" id="address" name="address" type="text" autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-6">
                        <label for="posscode">Posscode</label>
                        <input class="form-control" id="posscode" name="posscode" type="text" autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-6">
                        <label for="city">City</label>
                        <input class="form-control" id="city" name="city" type="text" autocomplete="off">
                       </div>
                       <div class="mb-3 mt-0 col-md-6">
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
                       <div class="mb-3 col-md-12 my-0">
                        <label for="remark">remark</label>
                          <textarea class="form-control" name="remark" autocomplete="off">  </textarea>
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
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection
