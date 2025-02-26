@extends('layouts.simple.master-cs')
@section('title', 'Report Approval')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Approved Session Skill Report</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Report Approval</li>
    <li class="breadcrumb-item active">Approved Session Skill Report</li>
@endsection     

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Report Form</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" style="border: 1px solid #000000">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>IC Number</th>
                                    <th>No of Session Attended</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Student Name">{{$report->std_name}}</td>
                                    <td data-label="IC Number">{{$report->std_ic}}</td>
                                    <td data-label="No of Session Attended">{{$report->session_attended}}</td>
                                    <td data-label="Date">{{$report->date}} </td>
                                </tr>
                            </tbody>
                        </table><br>
                        <table class="table table-border">
                            <tr>
                                <th>Therapist</th>
                                <td>{{$report->therapist}}</td>
                                <th>Treatment Provided</th>
                                <td>{{$report->treatment_provided}}</td>
                            </tr>
                        </table>
                    <br>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bil</th>
                                    <th style="width: 350px">item</th>
                                    <th style="text-align: left">Yes</th>
                                    <th style="text-align: left">No</th>
                                    <th>Progress Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td rowspan="9">1.0</td>
                                    <th colspan="3">S-Subjective Assesment</th>
                                    <td data-label="Progress Notes" rowspan="9"><textarea disabled name="remark1" 
                                        cols="30" rows="9" class="form-control">{{$report->remark1}}</textarea></td>
                                </tr><tr>
                                    <td data-label="Item">Enter : by his/ her self</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques1_1" value="1" {{ $report->ques1_1 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques1_1" value="0" {{ $report->ques1_1 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">With prompting</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques1_2" value="1" {{ $report->ques1_2 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques1_2" value="0" {{ $report->ques1_2 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr>
                                    <td data-label="Item">Difficulties separate with parents</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques1_3" value="1" {{ $report->ques1_3 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques1_3" value="0" {{ $report->ques1_3 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr>
                                    <td data-label="Item">With crying and refuse</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques1_4" value="1" {{ $report->ques1_4 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques1_4" value="0" {{ $report->ques1_4 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr>
                                    <td data-label="Item">Greeting with prompt</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques1_5" value="1" {{ $report->ques1_5 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques1_5" value="0" {{ $report->ques1_5 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr>
                                    <td data-label="Item">Greeting by him/herself</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques1_6" value="1" {{ $report->ques1_6 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques1_6" value="0" {{ $report->ques1_6 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr>
                                    <td data-label="Item">Mute</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques1_7" value="1" {{ $report->ques1_7 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques1_7" value="0" {{ $report->ques1_7 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr>
                                    <td data-label="Item">Refuse to enter</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques1_8" value="1" {{ $report->ques1_8 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques1_8" value="0" {{ $report->ques1_8 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr><td colspan="5">&nbsp;</td></tr>
                    
                                <tr>
                                    <td rowspan="26">2.0</td>
                                    <th colspan="3">O-Objective Assesment</th>
                                    <td data-label="Progress Notes" rowspan="26"><textarea name="remark2" disabled 
                                        cols="30" rows="26" class="form-control">{{$report->remark2}} </textarea></td>
                                </tr>
                                <tr>
                                    <th colspan="3">2.1 Moto & Praxis Skills</th>
                                </tr><tr>
                                    <td data-label="Item">Range of Motion (upper/lower extrem)</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_1" value="1" {{ $report->ques2_1 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_1" value="0" {{ $report->ques2_1 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Muscle Tone</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_2" value="1" {{ $report->ques2_2 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_2" value="0" {{ $report->ques2_2 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Muscle Strength</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_3" value="1" {{ $report->ques2_3 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_3" value="0" {{ $report->ques2_3 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Muscle Endurance</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_4" value="1" {{ $report->ques2_4 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_4" value="0" {{ $report->ques2_4 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Joint Mobility</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_5" value="1" {{ $report->ques2_5 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_5" value="0" {{ $report->ques2_5 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Trunk control & balance</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_6" value="1" {{ $report->ques2_6 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_6" value="0" {{ $report->ques2_6 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <th colspan="3">Gross Motor Function</th>
                                </tr><tr>
                                    <td data-label="Item">a. Standing</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_7a" value="1" {{ $report->ques2_7a === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_7a" value="0" {{ $report->ques2_7a === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">b. Crawling</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_7b" value="1" {{ $report->ques2_7b === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_7b" value="0" {{ $report->ques2_7b === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">c. Walking</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_7c" value="1" {{ $report->ques2_7c === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_7c" value="0" {{ $report->ques2_7c === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">d. Jumping</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_7d" value="1" {{ $report->ques2_7d === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_7d" value="0" {{ $report->ques2_7d === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">e. Broad Jump</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_7e" value="1" {{ $report->ques2_7e === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_7e" value="0" {{ $report->ques2_7e === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">f. Kick Ball</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_7f" value="1" {{ $report->ques2_7f === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_7f" value="0" {{ $report->ques2_7f === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">g. Throw Ball</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_7g" value="1" {{ $report->ques2_7g === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_7g" value="0" {{ $report->ques2_7g === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <th colspan="3">Fine Motor Function</th>    
                                </tr><tr>
                                    <td data-label="Item">a. Grasp & release</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_8a" value="1" {{ $report->ques2_8a === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_8a" value="0" {{ $report->ques2_8a === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">b. Reaching</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_8b" value="1" {{ $report->ques2_8b === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_8b" value="0" {{ $report->ques2_8b === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">c. Put block in a cup</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_8c" value="1" {{ $report->ques2_8c === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_8c" value="0" {{ $report->ques2_8c === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">d. Scribbles</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_8d" value="1" {{ $report->ques2_8d === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_8d" value="0" {{ $report->ques2_8d === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">e. Tower of cubes</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_8e" value="1" {{ $report->ques2_8e === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_8e" value="0" {{ $report->ques2_8e === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">f. Mature pencil grasping</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_8f" value="1" {{ $report->ques2_8f === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_8f" value="0" {{ $report->ques2_8f === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">g. Immature pencil grasping</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_8g" value="1" {{ $report->ques2_8g === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_8g" value="0" {{ $report->ques2_8g === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">h. Imitate vertical line</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_8h" value="1" {{ $report->ques2_8h === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_8h" value="0" {{ $report->ques2_8h === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">i. Copying</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques2_8i" value="1" {{ $report->ques2_8i === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques2_8i" value="0" {{ $report->ques2_8i === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr><td colspan="5">&nbsp;</td></tr>
                    
                                <tr>
                                    <td rowspan="8">3.0</td>
                                    <th colspan="3">Sensory Regulation Skills</th>
                                    <td data-label="Progress Notes" rowspan="8"><textarea name="remark3" disabled 
                                        cols="30" rows="8" class="form-control">{{$report->remark3}}</textarea></td>
                                </tr>
                                <tr>
                                    <td data-label="Item">Tacticle</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques3_1" value="1" {{ $report->ques3_1 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques3_1" value="0" {{ $report->ques3_1 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Auditary</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques3_2" value="1" {{ $report->ques3_2 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques3_2" value="0" {{ $report->ques3_2 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Visual</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques3_3" value="1" {{ $report->ques3_3 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques3_3" value="0" {{ $report->ques3_3 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Olfactory</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques3_4" value="1" {{ $report->ques3_4 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques3_4" value="0" {{ $report->ques3_4 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Gustatory</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques3_5" value="1" {{ $report->ques3_5 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques3_5" value="0" {{ $report->ques3_5 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Vestibular</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques3_6" value="1" {{ $report->ques3_6 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques3_6" value="0" {{ $report->ques3_6 === 0 ? 'checked' : '' }}></td>
                                </tr><tr> 
                                    <td data-label="Item">Proprioception</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques3_7" value="1" {{ $report->ques3_7 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques3_7" value="0" {{ $report->ques3_7 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr><td colspan="5">&nbsp;</td></tr>
                                
                                <tr>
                                    <td rowspan="11">4.0</td>
                                    <th colspan="3">4.1 Cognitive Regulation Skill</th>
                                    <td data-label="Progress Notes" rowspan="11"><textarea name="remark4" disabled 
                                        cols="30" rows="11" class="form-control">{{$report->remark4}}</textarea></td>
                                </tr>
                                <tr>
                                    <th colspan="3">Basic Concept</th>
                                </tr>
                                <tr>
                                    <td data-label="Item">a. Alphabet</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques4_1a" value="1" {{ $report->ques4_1a === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques4_1a" value="0" {{ $report->ques4_1a === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">b. Numbers</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques4_1b" value="1" {{ $report->ques4_1b === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques4_1b" value="0" {{ $report->ques4_1b === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">c. Shapes</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques4_1c" value="1" {{ $report->ques4_1c === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques4_1c" value="0" {{ $report->ques4_1c === 0 ? 'checked' : '' }}></td>
                                </tr><tr> 
                                    <td data-label="Item">d. Colors</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques4_1d" value="1" {{ $report->ques4_1d === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques4_1d" value="0" {{ $report->ques4_1d === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Memory function</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques4_2" value="1" {{ $report->ques4_2 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques4_2" value="0" {{ $report->ques4_2 === 0 ? 'checked' : '' }}></td>
                                </tr><tr> 
                                    <td data-label="Item">Attention</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques4_3" value="1" {{ $report->ques4_3 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques4_3" value="0" {{ $report->ques4_3 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Concentration</td>
                                    <td data-label="Concentration" colspan="2">{{$report->ques4_4}}</td>
                                </tr><tr>
                                    <td data-label="Item">Problem solving</td>
                                    <td data-label="Problem Solving" colspan="2">{{$report->ques4_5}}</td>
                                </tr><tr>
                                    <td data-label="Item">Writing skill</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques4_6" value="1" {{ $report->ques4_6 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques4_6" value="0" {{ $report->ques4_6 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr><td colspan="5">&nbsp;</td></tr>
                    
                                <tr>
                                    <td rowspan="17">5.0</td>
                                    <th colspan="3">5.1 Occupational Performance</th>
                                    <td data-label="Progress Notes" rowspan="17"><textarea name="remark5" disabled 
                                        cols="30" rows="17" class="form-control">{{$report->remark5}}</textarea></td>
                                </tr><tr>
                                    <th colspan="3">Activity Daily Living (ADL)</th>
                                </tr>
                                <tr>
                                    <td data-label="Item">Independent/no helper</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_1" value="1" {{ $report->ques5_1 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_1" value="0" {{ $report->ques5_1 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">With supervision</td>
                                    <td data-label="With supervision" colspan="2">{{$report->ques5_2}}</td>
                                </tr><tr>
                                    <td data-label="Item">Maximum assistance</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_3" value="1" {{ $report->ques5_3 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_3" value="0" {{ $report->ques5_3 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Toilet trained</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_4" value="1" {{ $report->ques5_4 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_4" value="0" {{ $report->ques5_4 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <th colspan="3">Instrumental Activity Daily Living (IADL)</th>    
                                </tr><tr>
                                    <td data-label="Item">Money Management</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_5" value="1" {{ $report->ques5_5 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_5" value="0" {{ $report->ques5_5 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Time concept</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_6" value="1" {{ $report->ques5_6 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_6" value="0" {{ $report->ques5_6 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <th colspan="3">Simple house hold activities</th>   
                                </tr><tr>
                                    <td data-label="Item">a. Folding clothes</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_7a" value="1" {{ $report->ques5_7a === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_7a" value="0" {{ $report->ques5_7a === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">b. Hanging clothes</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_7b" value="1" {{ $report->ques5_7b === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_7b" value="0" {{ $report->ques5_7b === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">c. Sweep floor</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_7c" value="1" {{ $report->ques5_7c === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_7c" value="0" {{ $report->ques5_7c === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <th colspan="3">Simple domestic activities</th>    
                                </tr><tr>
                                    <td data-label="Item">a. Making drinks</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_8a" value="1" {{ $report->ques5_8a === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_8a" value="0" {{ $report->ques5_8a === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">b. Prepare simple food</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_8b" value="1" {{ $report->ques5_8b === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_8b" value="0" {{ $report->ques5_8b === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">c. Use phone</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques5_8c" value="1" {{ $report->ques5_8c === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques5_8c" value="0" {{ $report->ques5_8c === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr><td colspan="5">&nbsp;</td></tr>
                                
                                <tr>
                                    <td rowspan="9">6.0</td>
                                    <th colspan="3">6.1 Emotional Regulation Skills</th>
                                    <td data-label="Progress Notes" rowspan="9"><textarea name="remark6" disabled 
                                        cols="30" rows="9" class="form-control">{{$report->remark6}}</textarea></td>
                                </tr><tr>
                                    <th colspan="3">Behaviour</th>
                                </tr>
                                <tr>
                                    <td data-label="Item">a. Tempered Tantrum</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques6_1a" value="1" {{ $report->ques6_1a === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques6_1a" value="0" {{ $report->ques6_1a === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">b. Manipulative</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques6_1b" value="1" {{ $report->ques6_1b === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques6_1b" value="0" {{ $report->ques6_1b === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">c. Easily distracted</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques6_1c" value="1" {{ $report->ques6_1c === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques6_1c" value="0" {{ $report->ques6_1c === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">d. Passive</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques6_1d" value="1" {{ $report->ques6_1d === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques6_1d" value="0" {{ $report->ques6_1d === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">e. Cooperative</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques6_1e" value="1" {{ $report->ques6_1e === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques6_1e" value="0" {{ $report->ques6_1e === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">f. Isolation</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques6_1f" value="1" {{ $report->ques6_1f === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques6_1f" value="0" {{ $report->ques6_1f === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">g. Reluctant</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques6_1g" value="1" {{ $report->ques6_1g === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques6_1g" value="0" {{ $report->ques6_1g === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr><td colspan="5">&nbsp;</td></tr>
                                
                                <tr>
                                    <td rowspan="16">7.0</td>
                                    <th colspan="3">7.1 Communication & Social Skills</th>
                                    <td data-label="Progress Notes" rowspan="16"><textarea name="remark7" disabled 
                                        cols="30" rows="16" class="form-control">{{$report->remark7}}</textarea></td>
                                </tr><tr>
                                    <th colspan="3">Following Instruction</th>
                                </tr>
                                <tr>
                                    <td data-label="Item">a. Repetitive prompting</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques7_1a" value="1" {{ $report->ques7_1a === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques7_1a" value="0" {{ $report->ques7_1a === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">b. Verbal prompting</td>
                                    <td data-label="b. Verbal Prompting" colspan="2">{{$report->ques7_1b}}</td>
                                </tr><tr>
                                    <td data-label="Item">c. Physical prompting</td>
                                    <td data-label="c. Physical prompting" colspan="2">{{$report->ques7_1c}}</td>
                                </tr><tr>
                                    <th colspan="3">Eye contact</th>    
                                </tr><tr>
                                    <td data-label="Item">a. Person</td>
                                    <td data-label="a. Person" colspan="2">{{$report->ques7_2a}}</td>
                                </tr><tr>
                                    <td data-label="Item">b. Object</td>
                                    <td data-label="b. Object" colspan="2">{{$report->ques7_2b}}</td>
                                </tr><tr>
                                    <td data-label="Item">Initiate / answer quesi_n</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques7_3" value="1" {{ $report->ques7_3 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques7_3" value="0" {{ $report->ques7_3 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Verbal Respond</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques7_4" value="1" {{ $report->ques7_4 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques7_4" value="0" {{ $report->ques7_4 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Voice clarity</td>
                                    <td data-label="Voice clarity" colspan="2">{{$report->ques7_5}}</td>
                                </tr><tr>
                                    <td data-label="Item">Facial Expression</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques7_6" value="1" {{ $report->ques7_6 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques7_6" value="0" {{ $report->ques7_6 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Body language</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques7_7" value="1" {{ $report->ques7_7 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques7_7" value="0" {{ $report->ques7_7 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Taking turn</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques7_8" value="1" {{ $report->ques7_8 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques7_8" value="0" {{ $report->ques7_8 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Sharing</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques7_9" value="1" {{ $report->ques7_9 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques7_9" value="0" {{ $report->ques7_9 === 0 ? 'checked' : '' }}></td>
                                </tr><tr>
                                    <td data-label="Item">Stay in grouping</td>
                                    <td data-label="Yes"><input type="radio" disabled name="ques7_10" value="1" {{ $report->ques7_10 === 1 ? 'checked' : '' }}></td>
                                    <td data-label="No"><input type="radio" disabled name="ques7_10" value="0" {{ $report->ques7_10 === 0 ? 'checked' : '' }}></td>
                                </tr>
                                <tr><td colspan="5">&nbsp;</td></tr>
                    
                                <tr>
                                    <td>8.0</td>
                                    <td colspan="3">Academic Performance</td>
                                    <td data-label="Item">{{$report->ques8_0}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table>
                            <tr>
                                <th style="width:30%">ANALYSIS PROBLEM, SHORT TERM, LONG TERM GOAL</th>
                                <td><textarea class="form-control" disabled name="analysis" id="analysis" cols="30" rows="10">{{$report->analysis}}</textarea></td>
                            </tr>
                            <tr>
                                <th style="width:30%">Tx. done, Tx PLAN</th>
                                <td><textarea class="form-control" disabled name="plan" id="plan" cols="30" rows="10">{{$report->plan}}</textarea></td>
                            </tr>
                        </table>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
@endsection