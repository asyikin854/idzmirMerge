@extends('layouts.simple.master-parent')
@section('title', 'Schedule')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Schedule</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Schedules</li>
@endsection

@section('content')
    <div class="container-fluid calendar-basic">
        <div class="card">
            <div class="card-body">
                <div class="row" id="wrap">
                    <div class="col-xxl-3 box-col-12">
                        <h4>Processing Sessions</h4>
                        <div class="table-responsive">
                            <table class="table table-border">
                                <thead>
                                    <tr>
                                      <th>Session</th>
                                      <th>Date</th>
                                      <th>Time</th>
                                      <th>Day</th>
                                      <th>Therapist</th>
                                      <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($progressSchedule as $schedules)
                                        <tr>
                                            <td>Session {{ $schedules->session }}</td>
                                            <td>{{ $schedules->date }}</td>
                                            <td>{{ $schedules->time }}</td>
                                            <td>{{ $schedules->day }}</td>
                                            <td>{{ $schedules->therapist}} </td>
                                            <td>{{ $schedules->status}} </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">There are no in progress sessions</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div><br>
                    <div class="col-xxl-3 box-col-12">
                        <div class="md-sidebar mb-3"><a class="btn btn-primary md-sidebar-toggle"
                                href="javascript:void(0)">calendar filter</a>
                            <div class="md-sidebar-aside job-left-aside custom-scrollbar">
                                <div id="external-events">
                                    <h4>Child Monthly Schedule</h4>
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
                    <br><br>
                    <div class="col-xxl-3 box-col-12" style="margin-top: 20px">
                        <h4>Sessions History</h4>
                        <div class="table-responsive">
                            <table class="table table-border">
                                <thead>
                                    <tr>
                                      <th>Session</th>
                                      <th>Date</th>
                                      <th>Time</th>
                                      <th>Day</th>
                                      <th>Therapist</th>
                                      <th>Attendance</th>
                                      <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($scheduleHistory as $schedule)
                                        <tr>
                                            <td>Session {{ $schedule->session }}</td>
                                            <td>{{ $schedule->date }}</td>
                                            <td>{{ $schedule->time }}</td>
                                            <td>{{ $schedule->day }}</td>
                                            <td>{{ $schedule->therapist}} </td>
                                            <td>{{ $schedule->attendance}} </td>
                                            <td>{{ $schedule->status}} </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">There are no session history</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="eventDetails">
                        <!-- Event details will be populated here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    var calendarEvents = @json($events);
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/js/calendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/js/calendar/fullcalendar-custom.js') }}"></script>
    
@endsection
