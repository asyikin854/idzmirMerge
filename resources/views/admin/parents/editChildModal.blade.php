<div class="modal fade" id="editChildModal" tabindex="-1" role="dialog" aria-labelledby="editChildModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('update.childInfo', $childInfo->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editChildModalLabel">Edit Child Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="child_name">Full name *</label>
                        <input class="form-control" name="child_name" id="child_name" type="text" value="{{$childInfo->child_name}}" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="child_nationality">Nationality *</label>
                        @php
                        $selectedNationality = $childInfo->child_nationality ?? '--Select Country--';
                        $arr = [
                            'Malaysian', 'Afghan', 'Albanian', 'Algerian', 'American', 'Andorran', 'Angolan', 'Antiguans', 'Argentinean', 'Armenian', 'Australian', 'Austrian', 
                            'Azerbaijani', 'Bahamian', 'Bahraini', 'Bangladeshi', 'Barbadian', 'Barbudans', 'Batswana', 'Belarusian', 'Belgian', 'Belizean', 'Beninese', 
                            'Bhutanese', 'Bolivian', 'Bosnian', 'Brazilian', 'British', 'Bruneian', 'Bulgarian', 'Burkinabe', 'Burmese', 'Burundian', 'Cambodian', 
                            'Cameroonian', 'Canadian', 'Cape Verdean', 'Central African', 'Chadian', 'Chilean', 'Chinese', 'Colombian', 'Comoran', 'Congolese', 'Costa Rican', 
                            'Croatian', 'Cuban', 'Cypriot', 'Czech', 'Danish', 'Djibouti', 'Dominican', 'Dutch', 'East Timorese', 'Ecuadorean', 'Egyptian', 'Emirian', 
                            'Equatorial Guinean', 'Eritrean', 'Estonian', 'Ethiopian', 'Fijian', 'Filipino', 'Finnish', 'French', 'Gabonese', 'Gambian', 'Georgian', 'German', 
                            'Ghanaian', 'Greek', 'Grenadian', 'Guatemalan', 'Guinea-Bissauan', 'Guinean', 'Guyanese', 'Haitian', 'Herzegovinian', 'Honduran', 'Hungarian', 'Icelander', 'Indian', 'Indonesian', 'Iranian', 'Iraqi', 'Irish', 'Israeli', 'Italian', 'Ivorian', 'Jamaican', 'Japanese', 'Jordanian', 'Kazakhstani', 'Kenyan', 'Kittian and Nevisian', 'Kuwaiti', 'Kyrgyz', 'Laotian', 'Latvian', 'Lebanese', 'Liberian', 'Libyan', 'Liechtensteiner', 'Lithuanian', 'Luxembourger', 'Macedonian', 'Malagasy', 'Malawian', 'Maldivan', 'Malian', 'Maltese', 'Marshallese', 'Mauritanian', 'Mauritian', 'Mexican', 'Micronesian', 'Moldovan', 'Monacan', 'Mongolian', 'Moroccan', 'Mosotho', 'Motswana', 'Mozambican', 'Namibian', 'Nauruan', 'Nepalese', 'Netherlander', 'New Zealander', 'Ni-Vanuatu', 'Nicaraguan', 'Nigerian', 'Nigerien', 'North Korean', 'Northern Irish', 'Norwegian', 'Omani', 'Pakistani', 'Palauan', 'Panamanian', 'Papua New Guinean', 'Paraguayan', 'Peruvian', 'Polish', 'Portuguese', 'Qatari', 'Romanian', 'Russian', 'Rwandan', 'Saint Lucian', 'Salvadoran', 'Samoan', 'San Marinese', 'Sao Tomean', 'Saudi', 'Scottish', 'Senegalese', 'Serbian', 'Seychellois', 'Sierra Leonean', 'Singaporean', 'Slovakian', 'Slovenian', 'Solomon Islander', 'Somali', 'South African', 'South Korean', 'Spanish', 'Sri Lankan', 'Sudanese', 'Surinamer', 'Swazi', 'Swedish', 'Swiss', 'Syrian', 'Taiwanese', 'Tajik', 'Tanzanian', 'Thai', 'Togolese', 'Tongan', 'Trinidadian or Tobagonian', 'Tunisian', 'Turkish', 'Tuvaluan', 'Ugandan', 'Ukrainian', 'Uruguayan', 'Uzbekistani', 'Venezuelan', 'Vietnamese', 'Welsh', 'Yemenite', 'Zambian', 'Zimbabwean'
                        ];
                        
                        $name = 'child_nationality';
                        $id = 'child_nationality';
                        $class = 'form-select';
                        
                        $html = "<select name='$name' id='$id' class='$class'>";
                        $html .= "<option value=''>--- Select Nationality ---</option>";
                        foreach ($arr as $nationality) {
                            $isSelected = ($nationality == $selectedNationality) ? "selected='selected'" : "";
                            $html .= "<option value='$nationality' $isSelected>$nationality</option>";
                        }
                        $html .= "</select>";
                        
                        echo $html;
                        @endphp
                    </div>
                    @if ($childInfo->child_ic)
                    <div class="form-group mb-3">
                        <label for="child_ic">IC Number</label>
                           <input class="form-control" name="child_ic" id="child_ic" type="text" value="{{$childInfo->child_ic}}" maxlength="12">
                        </div>
                    @else        
                    <div class="form-group mb-3">
                        <label for="child_passport">Passport</label>
                       <input class="form-control" name="child_passport" id="child_passport" placeholder="Passport" value="{{$childInfo->child_passport}}" type="text">
                    </div>
                    @endif
                     <div class="form-group mb-3">
                        <label for="child_dob">Date of Birth *</label>
                        <input class="form-control" name="child_dob" id="child_dob" type="date" value="{{$childInfo->child_dob}}" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="child_race">Race *</label>
                        <input class="form-control" name="child_race" id="child_race" type="text" value="{{$childInfo->child_race}}" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="child_bp">Birth Place *</label>
                        <input class="form-control" name="child_bp" id="child_bp" type="text" value="{{$childInfo->child_bp}}" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="child_religion">Religion *</label>
                        <select name="child_religion" id="child_religion" class="form-select" required>
                            <option value="Islam" {{ old('child_religion', $childInfo->child_religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Hindu" {{ old('child_religion', $childInfo->child_religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('child_religion', $childInfo->child_religion) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Christian" {{ old('child_religion', $childInfo->child_religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                            <option value="Atheist" {{ old('child_religion', $childInfo->child_religion) == 'Atheist' ? 'selected' : '' }}>Atheist</option>
                            <option value="Other" {{ old('child_religion', $childInfo->child_religion) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                     <div class="form-group mb-3">
                        <label for="child_sex">Sex *</label>
                        <select name="child_sex" id="child_sex" class="form-select" required>
                           <option value="Male" {{ old('child_sex', $childInfo->child_sex) == 'Male' ? 'selected' : '' }}>Male</option>
                           <option value="Female" {{ old('child_sex', $childInfo->child_sex) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                     </div>
                     <div class="form-group mb-3">
                        <label for="child_address">Address *</label>
                        <input class="form-control" name="child_address" id="child_address" type="text" value="{{$childInfo->child_address}}" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="child_posscode">Postcode *</label>
                        <input class="form-control" name="child_posscode" id="child_posscode" type="number" value="{{$childInfo->child_posscode}}" maxlength="5" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="child_city">City *</label>
                        <input class="form-control" name="child_city" id="child_city" type="text" value="{{$childInfo->child_city}}" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="child_country">Country *</label>
                        @php
                            $countries = [
                                'Malaysia', 'Afghanistan', 'Åland Islands', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Congo, The Democratic Republic of The', 'Cook Islands', 'Costa Rica', 'Cote D\'ivoire', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guernsey', 'Guinea', 'Guinea-bissau', 'Guyana', 'Haiti', 'Heard Island and Mcdonald Islands', 'Holy See (Vatican City State)', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran, Islamic Republic of', 'Iraq', 'Ireland', 'Isle of Man', 'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, Democratic People\'s Republic of', 'Korea, Republic of', 'Kuwait', 'Kyrgyzstan', 'Lao People\'s Democratic Republic', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libyan Arab Jamahiriya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macao', 'Macedonia, The Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia, Federated States of', 'Moldova, Republic of', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestinian Territory, Occupied', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russian Federation', 'Rwanda', 'Saint Helena', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Pierre and Miquelon', 'Saint Vincent and The Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and The South Sandwich Islands', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen', 'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic', 'Taiwan', 'Tajikistan', 'Tanzania, United Republic of', 'Thailand', 'Timor-leste', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Venezuela', 'Viet Nam', 'Virgin Islands, British', 'Virgin Islands, U.S.', 'Wallis and Futuna', 'Western Sahara', 'Yemen', 'Zambia', 'Zimbabwe'
                            ];
                        @endphp
                        <select id="child_country" name="child_country" class="form-select" required>
                            <option value="">--- Select Country ---</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}" 
                                    {{ old('child_country', $childInfo->child_country) == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                     <div class="form-group mb-3">
                        <label for="pediatricians">Pediatricians</label>
                        <input class="form-control" name="pediatricians" id="pediatricians"  type="text">
                     </div>
                     <div class="form-group mb-3">
                        <label for="recommend">Recommended by Hospital/Clinic (If any)</label>
                        <input class="form-control" name="recommend" id="recommend" value="{{$childInfo->recommend}}" type="text">
                     </div>
                     <div class="form-group mb-3">
                        <label for="deadline">Deadline Diagnose by Doctor</label>
                        <input class="form-control" name="deadline" id="deadline" value="{{$childInfo->deadline}}" type="text">
                     </div>
                     <div class="form-group mb-3">
                        <label for="diagnosis">Diagnosis/Condition</label>
                        <input class="form-control" name="diagnosis" id="diagnosis" value="{{$childInfo->diagnosis}}" type="text">
                     </div>
                     <div class="form-group mb-3">
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
                                 <td><input type="text" name="occ_therapy" id="occ_therapy" class="form-control" value="{{$childInfo->occ_therapy}}"></td>
                                 <td><input type="text" name="sp_therapy" id="sp_therapy" class="form-control" value="{{$childInfo->sp_therapy}}"></td>
                                 <td><input type="text" name="others" id="others" class="form-control" value="{{$childInfo->others}}"></td>
                              </tbody>
                           </table>
                        </div>
                     </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
