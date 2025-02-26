@extends('layouts.simple.master-sales')
@section('title', 'Consultation Session')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">

@endsection

@section('breadcrumb-title')
    <h3>Sessions</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">All Sessions</li>
@endsection

@section('content')
    <div class="container-fluid calendar-basic">
        <div class="card">
            <div class="card-body">
                <div class="row" id="wrap">
                    <div class="col-xxl-3 box-col-12">
                        <div class="md-sidebar mb-3"><a class="btn btn-primary md-sidebar-toggle"
                                href="javascript:void(0)">calendar filter</a>
                            <div class="md-sidebar-aside job-left-aside custom-scrollbar">
                                <div id="external-events">
                                    <h4>Monthly Sessions</h4>
                                    <div id="external-events-list">
                                     
                                    </div>
                                    <p>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-9 box-col-12">
                        <div class="calendar-default" id="calendar-container">
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <br><br><hr><br>
                    <div class="col-xxl-3 box-col-12">
                        <form action="{{route('scheduleSlot.submit', ['child_id' => $child_id, 'package_id' => $package->id])}}" method="POST">
                            @csrf
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
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsModalLabel">Session Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="eventDetailsBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    var calendarEvents = @json($events);
    document.getElementById('consultationDate').addEventListener('change', function() {
        const dateInput = this.value; // Get the selected date
        const dayInput = document.getElementById('day'); // Get the day input field

        if (dateInput) {
            const date = new Date(dateInput); // Convert to Date object
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayOfWeek = days[date.getDay()]; // Get the day of the week
            dayInput.value = dayOfWeek; // Update the day input field
        } else {
            dayInput.value = ''; // Clear if no date is selected
        }
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/js/calendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/js/calendar/calendar-therapist.js') }}"></script>
@endsection
