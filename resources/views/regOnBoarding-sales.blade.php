@extends('layouts.simple.master-sales')
@section('title', 'Edit Customer Registration')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>FA Registration</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Customer</li>
<li class="breadcrumb-item">On Boarding</li>
<li class="breadcrumb-item active">FA Registration</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Full Assessment Registration</h5>
					<span></span>
				</div>
				<div class="card-body">
					<div class="stepwizard">
						<div class="stepwizard-row setup-panel">
							<div class="stepwizard-step">
								<a class="btn btn-primary" href="#step-1">1</a>
								<p>Child Details</p>
							</div>
							<div class="stepwizard-step">
								<a class="btn btn-light" href="#step-2">2</a>
								<p>Treatment History <br>
                                    (optional)</p>
							</div>
							<div class="stepwizard-step">
								<a class="btn btn-light" href="#step-3">3</a>
								<p>Father Details</p>
							</div>
							<div class="stepwizard-step">
								<a class="btn btn-light" href="#step-4">4</a>
								<p>Mother Details</p>
							</div>
							<div class="stepwizard-step">
								<a class="btn btn-light" href="#step-5">5</a>
								<p>Program</p>
							</div>
						</div>
					</div>
					<form id="registrationForm" action="{{route('submitRegOnBoard-sales', $childInfo->id)}}" method="POST">
						@csrf
						@method('PUT') <!-- Add this for the update method -->
						<!-- Step 1: Child Details -->
<div class="setup-content" id="step-1">
    <div class="col-xs-12">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="child_name">Full name *</label>
                <input class="form-control" name="child_name" id="child_name" type="text" placeholder="Johan" value="{{ $childInfo->child_name ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="child_nationality">Nationality *</label>&nbsp;<label style="color:red;">Please select your nationality</label>
                @php
                    $selectedNationality = $childInfo->child_nationality ?? '--Select Country--';
                    $arr = ['Malaysian', 'Afghan', 'Albanian', 'Algerian', 'American', 'Andorran', 'Angolan', 'Antiguans', 'Argentinean', 'Armenian', 'Australian', 'Austrian', 'Azerbaijani', 'Bahamian', 'Bahraini', 'Bangladeshi', 'Barbadian', 'Barbudans', 'Batswana', 'Belarusian', 'Belgian', 'Belizean', 'Beninese', 'Bhutanese', 'Bolivian', 'Bosnian', 'Brazilian', 'British', 'Bruneian', 'Bulgarian', 'Burkinabe', 'Burmese', 'Burundian', 'Cambodian', 'Cameroonian', 'Canadian', 'Cape Verdean', 'Central African', 'Chadian', 'Chilean', 'Chinese', 'Colombian', 'Comoran', 'Congolese', 'Costa Rican', 'Croatian', 'Cuban', 'Cypriot', 'Czech', 'Danish', 'Djibouti', 'Dominican', 'Dutch', 'East Timorese', 'Ecuadorean', 'Egyptian', 'Emirian', 'Equatorial Guinean', 'Eritrean', 'Estonian', 'Ethiopian', 'Fijian', 'Filipino', 'Finnish', 'French', 'Gabonese', 'Gambian', 'Georgian', 'German', 'Ghanaian', 'Greek', 'Grenadian', 'Guatemalan', 'Guinea-Bissauan', 'Guinean', 'Guyanese', 'Haitian', 'Herzegovinian', 'Honduran', 'Hungarian', 'Icelander', 'Indian', 'Indonesian', 'Iranian', 'Iraqi', 'Irish', 'Israeli', 'Italian', 'Ivorian', 'Jamaican', 'Japanese', 'Jordanian', 'Kazakhstani', 'Kenyan', 'Kittian and Nevisian', 'Kuwaiti', 'Kyrgyz', 'Laotian', 'Latvian', 'Lebanese', 'Liberian', 'Libyan', 'Liechtensteiner', 'Lithuanian', 'Luxembourger', 'Macedonian', 'Malagasy', 'Malawian', 'Maldivan', 'Malian', 'Maltese', 'Marshallese', 'Mauritanian', 'Mauritian', 'Mexican', 'Micronesian', 'Moldovan', 'Monacan', 'Mongolian', 'Moroccan', 'Mosotho', 'Motswana', 'Mozambican', 'Namibian', 'Nauruan', 'Nepalese', 'Netherlander', 'New Zealander', 'Ni-Vanuatu', 'Nicaraguan', 'Nigerian', 'Nigerien', 'North Korean', 'Northern Irish', 'Norwegian', 'Omani', 'Pakistani', 'Palauan', 'Panamanian', 'Papua New Guinean', 'Paraguayan', 'Peruvian', 'Polish', 'Portuguese', 'Qatari', 'Romanian', 'Russian', 'Rwandan', 'Saint Lucian', 'Salvadoran', 'Samoan', 'San Marinese', 'Sao Tomean', 'Saudi', 'Scottish', 'Senegalese', 'Serbian', 'Seychellois', 'Sierra Leonean', 'Singaporean', 'Slovakian', 'Slovenian', 'Solomon Islander', 'Somali', 'South African', 'South Korean', 'Spanish', 'Sri Lankan', 'Sudanese', 'Surinamer', 'Swazi', 'Swedish', 'Swiss', 'Syrian', 'Taiwanese', 'Tajik', 'Tanzanian', 'Thai', 'Togolese', 'Tongan', 'Trinidadian or Tobagonian', 'Tunisian', 'Turkish', 'Tuvaluan', 'Ugandan', 'Ukrainian', 'Uruguayan', 'Uzbekistani', 'Venezuelan', 'Vietnamese', 'Welsh', 'Yemenite', 'Zambian', 'Zimbabwean'];
                    $name = 'child_nationality';
                    $id = 'child_nationality';
                    $class = 'form-select';

                    $html = "<select name='$name' id='$id' class='$class' required>";
                    $html .= "<option value=''>--- Select Nationality ---</option>";
                    foreach ($arr as $nationality) {
                        $isSelected = ($nationality == $selectedNationality) ? "selected='selected'" : "";
                        $html .= "<option value='$nationality' $isSelected>$nationality</option>";
                    }
                    $html .= "</select>";
                    echo $html;
                @endphp
            </div>
            <div class="mb-3">
                <label id="child_ic_label" style="display:none; color:red;">Please enter the IC number</label>
                <input class="form-control" name="child_ic" id="child_ic" type="text" placeholder="IC number" maxlength="12" value="{{ $childInfo->child_ic ?? '' }}">
            </div>
            <div class="mb-3">
                <label id="child_passport_label" style="display:none; color:red;">Please enter the Passport number</label>
                <input class="form-control" name="child_passport" id="child_passport" placeholder="Passport" type="text" value="{{ $childInfo->child_passport ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="child_dob">Date of Birth *</label>
                <input class="form-control" name="child_dob" id="child_dob" type="date" value="{{ $childInfo->child_dob ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="child_race">Race</label>
                <input class="form-control" name="child_race" id="child_race" type="text" value="{{ $childInfo->child_race ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="child_bp">Birth Place</label>
                <input class="form-control" name="child_bp" id="child_bp" type="text" value="{{ $childInfo->child_bp ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="child_religion">Religion</label>
                <select name="child_religion" id="child_religion" class="form-select">
                    <option value="Islam" {{ ($childInfo->child_religion ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                    <option value="Hindu" {{ ($childInfo->child_religion ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                    <option value="Buddha" {{ ($childInfo->child_religion ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                    <option value="Christian" {{ ($childInfo->child_religion ?? '') == 'Christian' ? 'selected' : '' }}>Christian</option>
                    <option value="Atheist" {{ ($childInfo->child_religion ?? '') == 'Atheist' ? 'selected' : '' }}>Atheist</option>
                    <option value="Other" {{ ($childInfo->child_religion ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="child_sex">Sex *</label>
                <select name="child_sex" id="child_sex" class="form-select" required>
                    <option value="Male" {{ ($childInfo->child_sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ ($childInfo->child_sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="child_address">Address *</label>
                <input class="form-control" name="child_address" id="child_address" type="text" value="{{ $childInfo->child_address ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="child_posscode">Postcode *</label>
                <input class="form-control" name="child_posscode" id="child_posscode" type="number" maxlength="5" value="{{ $childInfo->child_posscode ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="child_city">City *</label>
                <input class="form-control" name="child_city" id="child_city" type="text" value="{{ $childInfo->child_city ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="child_country">Country *</label>
                @php
										$selectedCountry = ''; // Default value
										$countries = [
											'Malaysia', 'Afghanistan', 'Åland Islands', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Congo, The Democratic Republic of The', 'Cook Islands', 'Costa Rica', 'Cote D\'ivoire', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guernsey', 'Guinea', 'Guinea-bissau', 'Guyana', 'Haiti', 'Heard Island and Mcdonald Islands', 'Holy See (Vatican City State)', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran, Islamic Republic of', 'Iraq', 'Ireland', 'Isle of Man', 'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, Democratic People\'s Republic of', 'Korea, Republic of', 'Kuwait', 'Kyrgyzstan', 'Lao People\'s Democratic Republic', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libyan Arab Jamahiriya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macao', 'Macedonia, The Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia, Federated States of', 'Moldova, Republic of', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestinian Territory, Occupied', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russian Federation', 'Rwanda', 'Saint Helena', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Pierre and Miquelon', 'Saint Vincent and The Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and The South Sandwich Islands', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen', 'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic', 'Taiwan', 'Tajikistan', 'Tanzania, United Republic of', 'Thailand', 'Timor-leste', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Venezuela', 'Viet Nam', 'Virgin Islands, British', 'Virgin Islands, U.S.', 'Wallis and Futuna', 'Western Sahara', 'Yemen', 'Zambia', 'Zimbabwe'
										];
									@endphp
                <select id="child_country" name="child_country" class="form-select" required>
                    <option value="">--- Select Country ---</option>
                    @foreach($countries as $country)
                        <option value="{{ $country }}" {{ ($childInfo->child_country ?? '') == $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="house_income">House Income </label>
                <input class="form-control" name="house_income" id="house_income" type="text" value="{{ $childInfo->house_income ?? '' }}">
            </div>
            <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
        </div>
    </div>
</div>

{{-- Intervention / Treatment History --}}
<div class="setup-content" id="step-2">
    <div class="col-xs-12">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="pediatricians">Pediatricians</label>
                <input class="form-control" name="pediatricians" id="pediatricians" value="{{ $childInfo->pediatricians ?? '' }}" type="text">
             </div>
             <div class="mb-3">
                <label for="recommend">Recommended by Hospital/Clinic (If any)</label>
                <input class="form-control" name="recommend" id="recommend" value="{{ $childInfo->recommend ?? '' }}" type="text">
             </div>
             <div class="mb-3">
                <label for="deadline">Deadline Diagnose by Doctor</label>
                <input class="form-control" name="deadline" id="deadline" value="{{ $childInfo->deadline ?? '' }}" type="text">
             </div>
             <div class="mb-3">
                <label for="diagnosis">Diagnosis/Condition</label>
                <input class="form-control" name="diagnosis" id="diagnosis" value="{{ $childInfo->diagnosis ?? '' }}" type="text">
             </div>
             <div class="mb-3">
                <div class="table-responsive">
                   <table class="table table-bordered">
                      <thead>
                         <tr>
                            <th>Unit</th>
                            <th>Occupational Therapy</th>
                            <th>Speech Therapy</th>
                            <th>Others</th>
                         </tr>
                      </thead>
                      <tbody>
                         <th>Place</th>
                         <td><input type="text" name="occ_therapy" id="occ_therapy" value="{{ $childInfo->occ_therapy ?? '' }}" class="form-control"></td>
                         <td><input type="text" name="sp_therapy" id="sp_therapy" value="{{ $childInfo->sp_therapy ?? '' }}" class="form-control"></td>
                         <td><input type="text" name="others" id="others" value="{{ $childInfo->others ?? '' }}" class="form-control"></td>
                      </tbody>
                   </table>
                </div>
             </div>
            <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
        </div>
    </div>
</div>

                        <!-- Father Details Step -->
<div class="setup-content" id="step-3">
    <div class="col-xs-12">
        <div class="col-md-12">
            <div class="mb-3">
                <input type="checkbox" name="father" id="father_checkbox" {{ $fatherInfo ? '' : 'checked' }}>There are no father information.
            </div>
            <div id="father_input" style="{{ $fatherInfo ? 'display: block;' : 'display: none;' }}">
                <div class="mb-3">
                    <label for="father_name">Father's Name *</label>
                    <input class="form-control" name="father_name" id="father_name" type="text" value="{{ $fatherInfo->father_name ?? '' }}" {{ $fatherInfo ? 'required' : '' }}>
                </div>
                <div class="mb-3">
                    <label for="father_phone">Telephone NO *</label>
                    <input class="form-control" name="father_phone" id="father_phone" type="number" value="{{ $fatherInfo->father_phone ?? '' }}" {{ $fatherInfo ? 'required' : '' }}>
                </div>
                <div class="mb-3">
                    <label for="father_ic">I/C Number / Passport *</label>
                    <input class="form-control" maxlength="12" name="father_ic" id="father_ic" type="text" value="{{ $fatherInfo->father_ic ?? '' }}" {{ $fatherInfo ? 'required' : '' }}>
                </div>
                <div class="mb-3">
                    <label for="father_race">Race</label>
                    <input class="form-control" name="father_race" id="father_race" type="text" value="{{ $fatherInfo->father_race ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="father_occ">Occupation</label>
                    <input class="form-control" name="father_occ" id="father_occ" type="text" value="{{ $fatherInfo->father_occ ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="father_email">Email</label>
                    <input class="form-control" name="father_email" id="father_email" type="email" value="{{ $fatherInfo->father_email ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="father_address">Address</label>
                    <input class="form-control" name="father_address" id="father_address" type="text" value="{{ $fatherInfo->father_address ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="father_posscode">Postcode</label>
                    <input class="form-control" name="father_posscode" id="father_posscode" type="number" maxlength="5" value="{{ $fatherInfo->father_posscode ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="father_city">City</label>
                    <input class="form-control" name="father_city" id="father_city" type="text" value="{{ $fatherInfo->father_city ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="father_work_address">Work Address</label>
                    <input class="form-control" name="father_work_address" id="father_work_address" type="text" value="{{ $fatherInfo->father_work_address ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="father_work_posscode">Postcode</label>
                    <input class="form-control" name="father_work_posscode" id="father_work_posscode" type="number" maxlength="5" value="{{ $fatherInfo->father_work_posscode ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="father_work_city">City</label>
                    <input class="form-control" name="father_work_city" id="father_work_city" type="text" value="{{ $fatherInfo->father_work_city ?? '' }}">
                </div>
            </div>
            <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
        </div>
    </div>
</div>

<!-- Mother Details Step -->
<div class="setup-content" id="step-4">
    <div class="col-xs-12">
        <div class="col-md-12">
            <div class="mb-3">
                <input type="checkbox" name="mother" id="mother_checkbox" {{ $motherInfo ? '' : 'checked' }}>There are no mother information.
            </div>
            <div id="mother_input" style="{{ $motherInfo ? 'display: block;' : 'display: none;' }}">
                <div class="mb-3">
                    <label for="mother_name">Mother's Name *</label>
                    <input class="form-control" name="mother_name" id="mother_name" type="text" value="{{ $motherInfo->mother_name ?? '' }}" {{ $motherInfo ? 'required' : '' }}>
                </div>
                <div class="mb-3">
                    <label for="mother_phone">Telephone NO *</label>
                    <input class="form-control" name="mother_phone" id="mother_phone" type="number" value="{{ $motherInfo->mother_phone ?? '' }}" {{ $motherInfo ? 'required' : '' }}>
                </div>
                <div class="mb-3">
                    <label for="mother_ic">I/C Number / Passport *</label>
                    <input class="form-control" maxlength="12" name="mother_ic" id="mother_ic" type="text" value="{{ $motherInfo->mother_ic ?? '' }}" {{ $motherInfo ? 'required' : '' }}>
                </div>
                <div class="mb-3">
                    <label for="mother_race">Race</label>
                    <input class="form-control" name="mother_race" id="mother_race" type="text" value="{{ $motherInfo->mother_race ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="mother_occ">Occupation</label>
                    <input class="form-control" name="mother_occ" id="mother_occ" type="text" value="{{ $motherInfo->mother_occ ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="mother_email">Email</label>
                    <input class="form-control" name="mother_email" id="mother_email" type="email" value="{{ $motherInfo->mother_email ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="mother_address">Address</label>
                    <input class="form-control" name="mother_address" id="mother_address" type="text" value="{{ $motherInfo->mother_address ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="mother_posscode">Postcode</label>
                    <input class="form-control" name="mother_posscode" id="mother_posscode" type="number" maxlength="5" value="{{ $motherInfo->mother_posscode ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="mother_city">City</label>
                    <input class="form-control" name="mother_city" id="mother_city" type="text" value="{{ $motherInfo->mother_city ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="mother_work_address">Work Address</label>
                    <input class="form-control" name="mother_work_address" id="mother_work_address" type="text" value="{{ $motherInfo->mother_work_address ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="mother_work_posscode">Postcode</label>
                    <input class="form-control" name="mother_work_posscode" id="mother_work_posscode" type="number" maxlength="5" value="{{ $motherInfo->mother_work_posscode ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="mother_work_city">City</label>
                    <input class="form-control" name="mother_work_city" id="mother_work_city" type="text" value="{{ $motherInfo->mother_work_city ?? '' }}">
                </div>
            </div>
            <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
        </div>
    </div>
</div>
<div class="setup-content" id="step-5">
    <div class="col-xs-12">
        <div class="col-md-12">
            <div class="mb-6">
                <div id="nationality1">
                @if ($childInfo->child_nationality === 'Malaysian')
                @foreach ($packages->where('citizenship', 'yes') as $package)
                    <h5>{{ $package->package_step }} | {{ $package->package_name }}</h5>
                    <ul style="list-style-type: circle; margin-left:5px">
                        @if ($package->package_long_desc1)
                            <li>{{ $package->package_long_desc1 }}</li>
                        @endif
                        @if ($package->package_long_desc2)
                            <li>{{ $package->package_long_desc2 }}</li>
                        @endif
                        @if ($package->package_long_desc3)
                            <li>{{ $package->package_long_desc3 }}</li>
                        @endif
                    </ul><br>
                    <ol>
                        @if ($package->package_short_desc1)
                            <li>{{ $package->package_short_desc1 }}</li>
                        @endif
                        @if ($package->package_short_desc2)
                            <li>{{ $package->package_short_desc2 }}</li>
                        @endif
                        @if ($package->package_short_desc3)
                            <li>{{ $package->package_short_desc3 }}</li>
                        @endif
                        @if ($package->package_short_desc4)
                            <li>{{ $package->package_short_desc4 }}</li>
                        @endif
                        @if ($package->package_short_desc5)
                            <li>{{ $package->package_short_desc5 }}</li>
                        @endif
                    </ol>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Normal Price</th>
                                <td>RM {{ $package->package_normal_price }}</td>
                            </tr>
                            <tr>
                                <th>Discounted Price Weekday</th>
                                <td>RM {{ $package->package_wkday_price }}</td>
                            </tr>
                            <tr>
                                <th>Discounted Price Weekend</th>
                                <td>RM {{ $package->package_wkend_price }}</td>
                            </tr>
                        </table>
                    </div>
                    <input type="hidden" name="package_id" id="malaysian-id" value="{{$package->id}}">
                @endforeach
                </div>
            @else                
            <div id="nationality2">
            @foreach ($packages->where('citizenship', 'no') as $package)
                    <h5>{{ $package->package_step }} | {{ $package->package_name }}</h5>
                    <ul style="list-style-type: circle; margin-left:5px">
                        @if ($package->package_long_desc1)
                            <li>{{ $package->package_long_desc1 }}</li>
                        @endif
                        @if ($package->package_long_desc2)
                            <li>{{ $package->package_long_desc2 }}</li>
                        @endif
                        @if ($package->package_long_desc3)
                            <li>{{ $package->package_long_desc3 }}</li>
                        @endif
                    </ul><br>
                    <ol>
                        @if ($package->package_short_desc1)
                            <li>{{ $package->package_short_desc1 }}</li>
                        @endif
                        @if ($package->package_short_desc2)
                            <li>{{ $package->package_short_desc2 }}</li>
                        @endif
                        @if ($package->package_short_desc3)
                            <li>{{ $package->package_short_desc3 }}</li>
                        @endif
                        @if ($package->package_short_desc4)
                            <li>{{ $package->package_short_desc4 }}</li>
                        @endif
                        @if ($package->package_short_desc5)
                            <li>{{ $package->package_short_desc5 }}</li>
                        @endif
                    </ol>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Normal Price</th>
                                <td>RM {{ $package->package_normal_price }}</td>
                            </tr>
                            <tr>
                                <th>Discounted Price Weekday</th>
                                <td>RM {{ $package->package_wkday_price }}</td>
                            </tr>
                            <tr>
                                <th>Discounted Price Weekend</th>
                                <td>RM {{ $package->package_wkend_price }}</td>
                            </tr>
                        </table>
                    </div>
                    <input type="hidden" name="package_id" id="non-malaysian-id" value="{{$package->id}}">
                @endforeach
            </div>
            </div>   
             @endif
            <button class="btn btn-success pull-right" type="submit">Proceed</button>
        </div>
    </div>

    </div>
</form>
</div>


				</div>
			</div>
		</div>
	</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/form-wizard/form-wizard-two.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/smartwizard/5.2.0/js/jquery.smartWizard.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function toggleFields(checkboxId, inputDivId, specificFields) {
        const checkbox = document.getElementById(checkboxId);
        const inputDiv = document.getElementById(inputDivId);

        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                inputDiv.style.display = 'none';
                specificFields.forEach(function(field) {
                    field.required = false;
                    field.value = '';
                });
            } else {
                inputDiv.style.display = 'block';
                specificFields.forEach(function(field) {
                    field.required = true;
                });
            }
        });
    }

    // Initialize toggle for mother fields
    const specificMotherFields = [
        document.getElementById('mother_name'),
        document.getElementById('mother_ic'),
        document.getElementById('mother_phone')
    ];
    toggleFields('mother_checkbox', 'mother_input', specificMotherFields);

    // Initialize toggle for father fields
    const specificFatherFields = [
        document.getElementById('father_name'),
        document.getElementById('father_ic'),
        document.getElementById('father_phone')
    ];
    toggleFields('father_checkbox', 'father_input', specificFatherFields);
});
</script>
@endsection