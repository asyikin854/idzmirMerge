@extends('layouts.simple.master-Parent')
@section('title', 'New Program Schedules')

@section('css')
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection
@section('breadcrumb-title')
    <h3>New Consultation Schedule</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Program</li>
    <li class="breadcrumb-item">Change Program</li>
    <li class="breadcrumb-item">New Program Schedule</li>
    <li class="breadcrumb-item active">New Consultation Schedule</li>
@endsection
@section('content')
<div class="container-fluid calendar-basic">
    <div class="card">
        <div class="card-body">
            <div class="row" id="wrap">
                <div class="col-xxl-3 box-col-12">
                    <div class="md-sidebar mb-3">
                        <a class="btn btn-primary md-sidebar-toggle" href="javascript:void(0)">Program Details</a>
                        <div class="md-sidebar-aside job-left-aside custom-scrollbar">
                            <div id="external-events">
                                <h4>Session Schedule Selection</h4>
                                <div id="external-events-list"></div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Program Details <br>{{$package->package_name}} | {{$package->package_step}} <br>
                                                Total Sessions: {{$package->session_quantity}}</th>
                                            <td><ol>
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
                                              </ol></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
            <h5>Selected Consultation appointment</h5>

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
            </table>
        </div>

            <!-- Hidden form inputs -->
            <form action="{{ route('newConsultSchedule.submit', ['child_id' => $child_id, 'package_id' => $package->id]) }}" method="POST" onsubmit="return confirmSubmit()">
                @csrf
                <input type="hidden" name="selected_slots" id="selectedSlots" />
                <input type="hidden" name="totalPrice" id="totalPrice" />
                <input type="hidden" name="basePrice" id="basePrice">
                <input type="hidden" name="additional_sessions" id="additionalSessions" value="0" />
                <button type="submit" class="btn btn-primary mt-3">Confirm</button>
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
    var sessionQuantity = '1';
    var packageWkdayPrice = {{ $package->package_wkday_price }};
    var packageWkendPrice = {{ $package->package_wkend_price }};
    var isWeekly = {{ $package->weekly == 'yes' ? 'true' : 'false' }};
    function confirmSubmit(){
        var isConfirmed = confirm('Once submitted you are unable to change the selected slots. Are you confirm?');

        if (isConfirmed) {
            // Additional alert after the user confirms
            alert('You will be redirected to payment details page. Please *DO NOT* click back button to return to the previous page.');
            return true; // Allow the form to be submitted
        }

        // If the user clicks "Cancel", prevent form submission
        return false;

    }
</script>
<script src="{{ asset('assets/js/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/js/calendar/fullcalendar-schedule.js') }}"></script>
@endsection
