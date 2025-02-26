@extends('layouts.authentication.master')
@section('title', 'Schedules')

@section('css')
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
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
                                <h4>Assesment Schedule Selection</h4>
                                <span>Please select the slots for your assesment schedule</span>
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
            <h5>Selected Sessions Summary</h5>
            
            <!-- Display session_quantity from the package -->
            <p><strong>Package Session Quantity:</strong> {{ $package->session_quantity }}</p>
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
            {{-- @if ($package->consultation != 'Yes')
                    <div class="mt-3">
                        <label for="additionalSessionsInput">Additional Sessions (RM 100/Sessions):</label>
                        <input type="number" id="additionalSessionsInput" value="0" min="0" class="form-control" style="width: 100px;" />
                    </div>
                @endif --}}

            <!-- Hidden form inputs -->
            <form action="{{ route('childSchedule.submit', ['child_id' => $child_id, 'package_id' => $package->id]) }}" method="POST" onsubmit="return confirmSubmit()">
                @csrf
                <input type="hidden" name="selected_slots" id="selectedSlots" />
                <input type="hidden" name="totalPrice" id="totalPrice" />
                <input type="hidden" name="basePrice" id="basePrice">
                <input type="hidden" name="additional_sessions" id="additionalSessions" value="0" />
                <br><hr><br>
                @if ($package->consultation === 'Yes')
                <h5>Consultation Appointment</h5>
                <div class="table-responsive">
                    <table class="table table-border">
                        <tr>
                            <th>Slot</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Time</th>
                        </tr><tr>
                            <td>Consultation Appointment</td>
                            <td><input type="date" name="consult_date" id="consultationDate" class="form-control" required></td>
                            <td><input type="text" name="consult_day" id="day" class="form-control" readonly></td>
                            <td><input type="time" name="consult_time" id="time" class="form-control" required></td>
                        </tr>
                    </table>
                </div>
                @endif
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
    var sessionQuantity = {{ $package->session_quantity }};
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
