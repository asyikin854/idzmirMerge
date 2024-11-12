@extends('layouts.authentication.master')
@section('title', 'Sign-up-wizard')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-12 p-0">
         <div>
            <div class="theme-form">
               <div class="wizard-4" id="wizard">
                  
                  <ul>
                     <li><a class="logo text-start ps-0" href="{{ route('index') }}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/logoidzmir.png')}}" style="width: 150px" alt="looginpage"><span style="color: #5c5c5c; font-weight:500; font-size:25px">Agreements</span></a></li>
                     <li>
                        <a href="#step-1">
                           <h4>1</h4>
                           <h5>Rules and Regulations</h5>
                           <small>IdzmirKidsHub rules and regulations</small>
                        </a>
                     </li>
                     <li>
                        <a href="#step-2">
                           <h4>2</h4>
                           <h5>Parent's Consent</h5>
                           <small>Customer consent form</small>
                        </a>
                     </li>
                     <li>
                        <a href="#step-3">
                           <h4>3</h4>
                           <h5>Parent's Declaration</h5>
                           <small>Declaration of consent personal data</small>
                        </a>
                     </li>
                     <li>                          </li>
                  </ul>
                  
                     @csrf
                  <div id="step-1">
                     <div class="wizard-title" style="width: 90%">
                        <h2>Rules and Regulations</h2>
                        <h5 class="text-muted mb-4">Please read the rules and regulations before proceed</h5>
                     </div>
                     <div class="login-main" style="width: 90%">
                        <div class="theme-form">
                           <div class="form-group mb-3">
                           <ol type="1">
                              <li>Payment</li>
                              <ol type="a">
                                  <li>Full payment must be paid before first session and appointment booking.</li>
                                  <li>Monthly Fees must be paid at 23rd to 7th day of the month for the next month appointment.</li>
                                  <li>Payment for all sessions must be made in full at least 24 hours prior to the session commencing.</li>
                                  <li>Please make sure you have received the temporary receipt or official receipt once payment is made.</li>
                                  <li>All fees are not refundable, not transferable and cannot be deferred more than 60 days.</li>
                              </ol>
                              <li>Entering Therapy Session</li>
                              <ol type="a">
                                  <li>IKH have the right to stop child’s session if parents/guardian do not follow IKH’s policy.</li>
                                  <li>Parents must check child’s hygiene before enter therapy session. (e.g., bathing, clean bowel and bladder, nail trimming and neat hair).</li>
                                  <li>Student must wear appropriate and suitable clothes. (e.g., Idzmir Kids Hub Jersey, sport wear)</li>
                                  <li>Parents are advice to be in IKH area during therapy session except for student who are in toilet training program.</li>
                                  <li>Appointment for therapist consultation must be make a week before according to the availability of therapist’s schedule.</li>
                              </ol>
                              <li>Absent and Replacement Classes</li>
                              <ol type="a">
                                  <li>No replacement is allowed if absent without inform at least 3(three) hours before therapy  session. </li>
                                  <li>Replacement for one-to-one session can be made only 2(two) sessions only. No replacement and refundable if did not attend therapy sessions more than 2(two) times in a month.</li>
                                  <li>Only one replacement class for group therapy session.</li>
                                  <li>Parents are required to arrange the replacement in same week before class in advance to avoid complication.</li>
                              </ol>
                              <li>Public Holiday & Term Breaks</li>
                              <ol type="a">
                                  <li>Therapy will not be counted if it falls on public holidays or center are not operating.</li>
                                  <li>All holidays and event are listed in our  calendar.</li>
                                  <li>Memo will be issued or notice will be published in IKH in case of any  changes.</li>
                              </ol>
                              <li>Discontinue of therapy</li>
                              <ol type="a">
                                  <li>If students want to withdraw or stop attending therapy, parents must first inform our Customer Care 1(one) month in advance.</li>
                                  <li>If students stop therapy more than 2(two) months, his/her report isn’t valid and students need to do re-assessment.</li>
                              </ol>
                              <li>Occupational Therapist</li>
                              We reserve the right to make appropriate changes in course instructors if deemed necessary.
                              <li>Copyright Syllabus</li>
                              All right reserved; no part of this publication may be reproduced, stored in a retrieval system, or transmitted in any form or by any means, electronic, mechanical, photocopying, 
                              recording or otherwise without the prior written permission of the IKH.
                              <li>Personal Data</li>
                              <ol type="a">
                                  <li>If there any amendment in student’s personal data, parents must inform branch as soon as possible to update in the system</li>
                              </ol>
                              <li>The therapy will be delivered based on your child’s progress and development.</li>
                              <li>The deposited paid is valid for 2 months only. The deposit will be forfeited if full amount of the course fee is not paid within 2 months.</li>
                              <li>If students completed their programs with residual classes, those classes are deemed to be obsolete and will not be entitled for a refund under no circumstances.</li>
                              <li>Students are not allowed to attend therapy without student card/appointment card or if they are late for more than 15 minutes.</li>
                              <li>Scheduling Appointment: Parents need to set an appointment on 15th to 25th of every month for the following month. </li>
                          </ol><br>
                           </div>
                          <div class="form-group mb-3">
                           I, <label for="mother">Mother</label>
                           <input type="radio" name="parent" id="mother" value="mother">
                           
                           <label for="father">Father</label>
                           <input type="radio" name="parent" id="father" value="father"> 

                           <input disabled type="text" name="parent_name" id="name" class="form-control">
                          </div>

                          <div class="form-group mb-3">
                           , parent/guardian of 
                           <input disabled type="text" name="child_name" id="child_name" value="{{ $childInfo->child_name }}" class="form-control"><br>
                           have read and agreed to the rules and regulations stated as above.
                          </div>

                          <div class="form-group mb-3">
                           Parent's Signature
                           <input disabled type="text" name="parent_sign" id="parent_sign" value="{{$parentPermission->parent_sign}}" class="form-control sign">
                          </div>

                          Date: <input disabled type="date" name="sign_date" id="sign_date" class="form-control"><br>
                          Time: <input disabled type="time" name="sign_time" id="time" class="form-control">
                        </div>
                     </div>
                  </div>
                  <div id="step-2">
                     <div class="wizard-title" style="width: 90%">
                        <h2>Parent's Consent</h2>
                        <h5 class="text-muted mb-4">Customer consent form</h5>
                     </div>
                     <div class="login-main" style="width: 90%">
                        <div class="theme-form">
                           Name
                           <input type="text" name="child_name" id="child_name" value="{{ $childInfo->child_name }}" class="form-control"><br>
                           MyKid
                           <input type="text" name="mykid" id="mykid" value="{{ $childInfo->child_ic}}" class="form-control"><br>
                              I, 
                     <input type="text" name="consent_name" id="name2" class="form-control"><br>
                     Address <input type="text" name="consent_address" id="address" class="form-control"><br>
                     Relationship to child/student <input type="text" name="consent_relation" id="relation" class="form-control"><br>
                     <br>
                     <h5>CONSENT FOR OCCUPATIONAL THERAPY/ SPEECH THERAPY/PHYSIOTHERAPY/ASSESSMENT /INTERVENTION</h5>
                     Authorize the above to: <br>
                     <ul class="tick-list">
                           <li>Assess, treat the child/student and make recommendations. This assessment may include observation of the child,
                              formal and informal testing, follow up visits, and ongoing intervention. </li>
                           <li>Interact with the child/student for the purpose of providing appropriate training for school or preschool personnel (optional). </li>
                     </ul>
                     I understand that the results of the assessment and the recommendations will be discussed with me. 
                     <br><br>
                     <h5>CONSENT FOR RELEASE OF INFORMATION</h5>
                     Authorize IDZMIR KIDS HUB to release information to health professionals involved with the child.
                     <br><br>
                     Parent/Guardian Consent and Indemnity Agreement: 
                     <br><br>
                     I consent to and assume all risks and hazards of and incidental to the participation of the above-named boy or girl in the activities of the Idzmir Kids Hub, 
                     and agree to indemnify the said organization and its officers, servants, or agents nominated or appointed by or on its behalf against all loss from any claim 
                     hereafter made against it, them or any of them by or on behalf of said boy or girl and arising directly or indirectly from such participation. 
                     <br>
                     <table class="table table">
                        <thead>
                            <tr>
                                <th>Parent / Guardian</th>
                                <th>Signature</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th><input type="text" name="consent_name2" id="name3" class="form-control"></th>
                                <th><input type="text" name="consent_sign" id="consent_sign" value="{{ $parentPermission->parent_sign }}" class="form-control sign"></th>
                                <th><input type="date" name="consent_date" id="consent_date" class="form-control"></th>
                            </tr>
                        </tbody>
                    </table>
                        </div>
                     </div>
                  </div>
                  <div id="step-3">
                     <div class="wizard-title" style="width: 90%">
                        <h2>Parent's Declaration</h2>
                        <h5 class="text-muted mb-4">Declaration of consent Personal Data</h5>
                     </div>
                     <div class="login-main" style="width: 90%">
                        <div class="theme-form">
                           <h5>DECLARATION OF CONSENT PERSONAL DATA</h5>
        I undersigned, agree that Richcube Imperium Sdn.Bhd. (RISB) shall have the right to collect, process and/or use Personal Data relating to the APPLICANT. 
        <br><br>
        For purpose of this declaration, Personal Data should have the same meaning as provided under the Personal Data Protection Act 2010 (including its supplements and /or amendments and includes but is not limited to APPLICANT’S name, age, date of birth, identity card number, discharge book number, international passport number, contract details, racial or ethnic origin, physical or mental health or medical condition, occupation, preference and interests.
        <br><br>
        For purpose of this declaration, all references to the APPLICANT shall refer to the applicant (where the applicant has attained the age of 18 years old and (in the case of applicants who are below 18 years of age, it should refer to their parent or lawful guardian as the case may be. 
        <br><br>
        The APPLICANT agrees that RISB shall have the following right: 
        <br>
        <ol type="a">
            <li>To share the Personal Data among RISB group of companies.</li>
            <li>To use the personal data for performing its administrative and management functions. </li>
            <li>To conduct any disciplinary investigations or proceedings and/or investigation of any accident or incidents.</li>
            <li>To facilitate and enhance RISB operations by incorporating the Personal Data into RISB record and database. </li>
            <li>To comply any regulatory requirements applicable to RISB nature of business under laws, rules, regulations, by laws, and/or compliance of any policies guidelines.</li>
            <li>To pursue any legitimate interest of RISB group companies which includes recovery of any sums due (if applicable). </li>
            <li>To disclose for medical, tax, legal, accounting and/or other regulatory purposes and; </li>
            <li>To disclose for any other purposes that is incidental or ancillary to the above purpose and/connected to the operation, administration, development, or enhancement of RISB nature of business. </li>
        </ol>
        <h5>PENGISYTIHARAN KEBENARAN UNTUK MEMPROSES DATA PERIBADI</h5>
        Adalah saya, seperti nama dibawah, bersetuju bahawa Richcube Imperium Sdn. Bhd. (RISB) mempunyai hak untuk mengumpul, memproses dan/atau mengguna data peribadi berkaitan dengan PEMOHON. 
        <br><br>
        Bagi tujuan pengisytiharan ini, Data Peribadi membawa maksud yang sama seperti di dalam Akta Pelindungan Data Peribadi 2010 (termasuk tambahan dan/atau pembetulannya) dan termasuk tetapi tidak terhad kepada butiran pemohon seperti nama, umur, tarikh lahir, nombor kad pengenalan, nombor passport antarabangsa, butiran-butiran hubungan, bangsa, atau asal etnik, kesihatan fizikal atau mental atau keadaan perubatan, jawatan, keutamaan dan kepentingan. 
        <br><br>
        Bagi tujuan pengisytaharan ini, semua rujukan terhadap PEMOHON akan merujuk kepada pemohonan berkenaan (yang telah mencapai umur 18 tahun) dan dalam hal pemohon yang berumur kurang dari 18 tahun, ia akan merujuk kepada keluarga atau penjaga yang sah pemohon mengikut mana yang berkenaan.  
        <br><br>
        PEMOHON bersetuju RISB mempunyai hak berikut; 
        <br>
        <ol type="a">
            <li>Untuk berkongsi Data Peribadi di antara kumpulan syarikat. </li>
            <li>Untuk mengguna Data Peribadi bagi menjalankan fungsi-fungsi pentadbiran dan pengurusannya.</li>
            <li>Untuk mengendali sebarang siasatan disiplin atau prosiding dan/ atau siasatan bagi sebarang kemalangan dan kejadian. </li>
            <li>Untuk memudahkan dan meningkatkan operasi RISB dengan menggabungkan Data Peribadi ke dalam rekod dan pangkalan data RISB. </li>
            <li>Untuk mematuhi keperluan peraturan yang terpakai kepada perniagaan RISB dibawah apa-apa keperluan perundangan, kaedah, peraturan, undang-undang kecil dan/atau pematuhan apa-apa dasar, dan garis panduan.</li>
            <li>Untuk meneruskan apa-apa kepentingan yang sah bagi kumpulan syarikat RISB termasuk mandapatkan jumlah yang terhutang (jika berkenaan); </li>
            <li>Untuk menzahir bagi tujuan perubatan, cukai, perundangan, kewangan, dan/atau tujuan regulatori; dan </li>
            <li>Untuk menzahir bagi tujuan-tujuan lain yang bersampingan atau berkaitan kepada tujuan di atas dan/atau berhubung dengan operasi pentadbiran, pembangunan atau peningktana RISB.</li>
        </ol>
        <table class="table table">
         <tr>
             <th>Applicant's Name</th>
             <td><input type="text" name="name" id="name4" class="form-control"></td>
         </tr>
         <tr>
             <th>Identity Card No</th>
             <td><input type="number" name="ic" id="ic" class="form-control"></td>
         </tr>
         <tr>
             <th>Mobile No</th>
             <td><input type="number" name="phone_no" id="phone_no" class="form-control"></td>
         </tr>
         <tr>
             <th>Email Address</th>
             <td><input type="email" name="email" id="email" class="form-control"></td>
         </tr>
         <tr>
             <th>Date</th>
             <td><input type="date" name="date" id="date" class="form-control"></td>
         </tr>
         <tr>
             <th>Signature</th>
             <td><input type="text" name="sign" id="sign" value="{{ $parentPermission->parent_sign}}" class="form-control sign"></td>
         </tr>
     </table>
     <br>
     <a href="{{route('product-parent', ['child_id' => $childInfo->id])}}"><button type="button" class="btn btn-primary">Submit</button></a>
                        </div>
                     </div>
                  </div>
                 
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-wizard/form-wizard-five.js')}}"></script>
<script src="{{ asset('assets/js/tooltip-init.js')}}"></script>
<script src="{{ asset('assets/js/theme-customizer/customizer.js')}}"></script>
<script>

const fatherInfo = {
       name: "{{ $fatherInfo->father_name ?? '' }}",
       ic: "{{ $fatherInfo->father_ic ?? '' }}",
       email: "{{ $fatherInfo->father_email ?? '' }}", 
       phone_no: "{{ $fatherInfo->father_phone ?? '' }}", 
       address: "{{ $fatherInfo->father_address ?? '' }}",
       relation: "Father",
   };
   
   const motherInfo = {
       name: "{{ $motherInfo->mother_name ?? '' }}",
       ic: "{{ $motherInfo->mother_ic ?? '' }}",
       email: "{{ $motherInfo->mother_email ?? '' }}", 
       phone_no: "{{ $motherInfo->mother_phone ?? '' }}", 
       address: "{{ $motherInfo->mother_address ?? '' }}",
       relation: "Mother",
   };
   
   // Function to populate input fields based on selected parent
   function populateParentInfo(parentInfo) {
       // Check if parentInfo is not null and has required properties
       document.getElementById('name').value = parentInfo.name || '';
       document.getElementById('name2').value = parentInfo.name || '';
       document.getElementById('name3').value = parentInfo.name || '';
       document.getElementById('name4').value = parentInfo.name || '';
       document.getElementById('ic').value = parentInfo.ic || '';
       document.getElementById('email').value = parentInfo.email || '';
       document.getElementById('phone_no').value = parentInfo.phone_no || '';
       document.getElementById('address').value = parentInfo.address || '';
       document.getElementById('relation').value = parentInfo.relation || '';
   }
   
   // Event listeners for radio button change
   document.getElementById('mother').addEventListener('change', function() {
       if (this.checked) {
           populateParentInfo(motherInfo); // Populate with mother's info
       }
   });
   
   document.getElementById('father').addEventListener('change', function() {
       if (this.checked) {
           populateParentInfo(fatherInfo); // Populate with father's info
       }
   });
   
   window.onload = function() {
      // Function to pad single digit numbers with a leading zero
      function pad(n) {
          return n < 10 ? '0' + n : n;
      }
   
      // Get the current date and time
      var now = new Date();
   
      // Format the date as YYYY-MM-DD
      var year = now.getFullYear();
      var month = pad(now.getMonth() + 1); // Months are zero-indexed
      var day = pad(now.getDate());
      var formattedDate = year + '-' + month + '-' + day;
   
      // Format the time as HH:MM
      var hours = pad(now.getHours());
      var minutes = pad(now.getMinutes());
      var formattedTime = hours + ':' + minutes;
   
      // Set the values of the input fields
      document.getElementById('date').value = formattedDate;
      document.getElementById('sign_date').value = formattedDate;
      document.getElementById('consent_date').value = formattedDate;
      document.getElementById('time').value = formattedTime;
   };
   </script>
      
@endsection