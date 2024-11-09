@extends('layouts.simple.master-Parent')
@section('title', 'New Program Schedules')

@section('css')
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection
@section('breadcrumb-title')
    <h3>New Program Schedule</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Program</li>
    <li class="breadcrumb-item">Change Program</li>
    <li class="breadcrumb-item active">New Program Schedule</li>
@endsection
@section('content')
<div class="container-fluid calendar-basic">
    <div class="card">
        <div class="card-body">
            <div class="row" id="wrap">
                <div class="col-xxl-3 box-col-12">
                    <div class="md-sidebar mb-3">
                        <a class="btn btn-primary md-sidebar-toggle" href="javascript:void(0)">Current Schedule</a>
                        <div class="md-sidebar-aside job-left-aside custom-scrollbar">
                            <div id="external-events">
                                <h4>Current Program Schedule</h4>
                                <div id="external-events-list"></div>
                                <span> </span>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td colspan="6" align="center">Current Schedule for <b>{{$childInfo->child_name}}, {{$childInfo->package->package_name}} | {{$childInfo->package->package_step}}</b></td>
                                            </tr>
                                            <tr>
                                                <th>Session</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Therapist</th>
                                                <th>Attendance</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($childInfo->childSchedule as $childSchedule)
                                            <tr>
                                                <td>Session {{ $childSchedule->session }}</td>
                                                <td>{{ $childSchedule->date }}</td>
                                                <td>{{ $childSchedule->time }}</td>
                                                <td>{{ $childSchedule->therapist}} </td>
                                                <td>{{ $childSchedule->attendance ?? 'N/A' }} </td>
                                                <td>{{ $childSchedule->status}} </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No schedules found for this student .</td>
                                            </tr>
                                        @endforelse
                                          </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h4>New Program Schedule Selection</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>New Program Details <br>{{$newPackage->package_name}} | {{$newPackage->package_step}} </th>
                            <td><ol>
                                @if ($newPackage->package_short_desc1)
                                  <li>{{ $newPackage->package_short_desc1 }}</li>
                                @endif
                                @if ($newPackage->package_short_desc2)
                                  <li>{{ $newPackage->package_short_desc2 }}</li>
                                @endif
                                @if ($newPackage->package_short_desc3)
                                  <li>{{ $newPackage->package_short_desc3 }}</li>
                                @endif
                                @if ($newPackage->package_short_desc4)
                                  <li>{{ $newPackage->package_short_desc4 }}</li>
                                @endif
                                @if ($newPackage->package_short_desc5)
                                  <li>{{ $newPackage->package_short_desc5 }}</li>
                                @endif
                              </ol></td>
                        </tr>
                    </table>
                </div>
                <div class="col-xxl-9 box-col-12">
                    <div class="calendar-default" id="calendar-container">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="card mt-4">
        <div class="card-body">
            <h5>Selected Slots Summary</h5>

            
            <!-- Display session_quantity from the package -->
            <p><strong>Program Session Quantity:</strong> {{ $newPackage->session_quantity }}</p>
            <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="summary-table-body">
                    <!-- Summary will be dynamically updated here -->
                </tbody>
            </table></div>

            <!-- Display Prices -->
            <div class="mt-3">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Base (RM)</th>
                                <th>Additional Sessions (RM)</th>
                                <th>Total (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span id="basePriceDisplay">0</span></td>
                                <td><span id="additionalPriceDisplay">0</span></td>
                                <td><span id="totalPriceDisplay">0</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add More Sessions Button -->
            <div class="mt-3">
                <label for="additionalSessionsInput">Additional Sessions (RM 100/Sessions):</label>
                <input type="number" id="additionalSessionsInput" value="0" min="0" class="form-control" style="width: 100px;" />
            </div>

            <!-- Hidden form inputs -->
            <form action="{{ route('newScheduleSubmit-parent', ['child_id' => $childInfo->id, 'package_id' => $newPackage->id]) }}" method="POST" onsubmit="return confirmSubmit()">
                @csrf
                <input type="hidden" name="selected_slots" id="selectedSlots" />
                <input type="hidden" name="totalPrice" id="totalPrice" />
                <input type="hidden" name="additional_sessions" id="additionalSessions" value="0" />
                <br>
                <a href="{{route('changeProgram-parent', ['child_id'=> $childInfo->id, 'package_id' => $package->id])}}"><button type="button" class="btn btn-danger mt-3">Cancel</button></a>&nbsp;<button type="submit" class="btn btn-primary mt-3">Confirm</button>
            </form>
        </div>
    </div>

    <!-- Modal for displaying selected event details -->
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsLabel">Slot Details</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="eventDetails"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirmSlotBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var calendarSlots = @json($slots);
    var sessionQuantity = {{ $newPackage->session_quantity }};
    var packageWkdayPrice = {{ $newPackage->package_wkday_price }};
    var packageWkendPrice = {{ $newPackage->package_wkend_price }};
    var isWeekly = {{ $newPackage->weekly == 'yes' ? 'true' : 'false' }};
    function confirmSubmit(){
        var isConfirmed = confirm('Once submitted all the current sessions will be deleted and replace with the new selected sessions. Confirm change program and sessions?');

        if (isConfirmed) {
            // Additional alert after the user confirms
            alert('The new program and sessions will be submit. Invoice will be send to the parent. You will have to assign therapist to the sessions once the payment has been made.');
            return true; // Allow the form to be submitted
        }

        // If the user clicks "Cancel", prevent form submission
        return false;

    }
</script>
<script src="{{ asset('assets/js/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/js/calendar/fullcalendar-schedule.js') }}"></script>
@endsection
