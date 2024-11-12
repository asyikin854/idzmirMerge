<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Package;
use App\Models\SlotRTS;
use App\Models\ChildInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ChildSchedule;
use App\Models\SessionReport;
use App\Models\TherapistInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CsController extends Controller
{
    // CS role method in DisplayController
    protected function getColorForPackage($packageName)
    {
        static $colors = [
            '#1a1a2e', '#28a745', '#ff0000', '#ffc107', '#17a2b8', '#6c757d', '#6610f2', '#e83e8c', '#fd7e14', '#007bff'
        ];
        static $assignedColors = [];

        if (!isset($assignedColors[$packageName])) {
            $assignedColors[$packageName] = array_shift($colors);
        }

        return $assignedColors[$packageName];
    }

    public function csDashboard()
    {    
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;

            $schedules = ChildSchedule::where('status', 'approved')
            ->with('childInfo')
            ->get();

        $events = $schedules->map(function ($schedule) {
            $formattedTime = $schedule->time;
            $color = $this->getColorForPackage($schedule->childInfo->package->package_name);

            if ($schedule->attendance === 'present') {
                $color = '#28a745'; // Green for present
            } elseif ($schedule->attendance === 'absent') {
                $color = '#ff0000'; // Red for absent
            }

            return [
                'title' => "\nSession: " . $schedule->childInfo->package->package_name . "\n" . $schedule->therapist,
                'start' => $schedule->date . 'T' . $formattedTime,
                'details' => "Package: " . $schedule->childInfo->package->package_name . "<br>Date & Time: " . $schedule->date . " / " . $schedule->time .
                            "<br>Student: " . $schedule->childInfo->child_name,
                'color' => $color,
                'attendance' => $schedule->attendance,
            ];
        });

        $totalChild = ChildInfo::all()->count();
        $totalSession = ChildSchedule::all()->count();
        $sessionLeft = ChildSchedule::whereNull('attendance')->count();
        $sessionDone = ChildSchedule::where('attendance', 'present')->count();

        return view('dashboard/dashboard-cs', compact('totalChild', 'totalSession', 'sessionLeft', 'events', 'csName', 'sessionDone'));
    }

    public function csStudentList()
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;

        $childInfos = ChildInfo::all();

        if (!$childInfos) {
            abort(404);
        }
        foreach ($childInfos as $childInfo) {
            $childInfo->age = Carbon::parse($childInfo->child_dob)->age;
        }
        return view ('studentList-cs', compact('childInfos', 'csName'));

    }
    public function csStdDetails($id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $childInfo = ChildInfo::find($id);
        if (!$childInfo) {
            abort(404);
        }
        $childInfo->age = Carbon::parse($childInfo->child_dob)->age;
        return view ('stdDetails-cs', compact('childInfo', 'csName'));
    }
    public function csEditProgramView($child_id, $package_id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $childInfo = ChildInfo::find($child_id);
        $package = Package::find($package_id);
        if ($childInfo->child_nationality === 'Malaysian') {
            // Retrieve packages with citizenship set to 'yes' for Malaysian children, sorted by package_name
            $packages = Package::where('citizenship', 'yes')->orderBy('package_name', 'asc')->get();
        } else {
            // Retrieve packages with citizenship set to 'no' for non-Malaysian children, sorted by package_name
            $packages = Package::where('citizenship', 'no')->orderBy('package_name', 'asc')->get();
        }
        return view ('editProgram-cs', compact('packages', 'child_id', 'csName'));
    }
    public function csEditProgScheduleView($child_id, $package_id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $childInfo = ChildInfo::find($child_id);
        $package = Package::find($package_id);
    
        if (!$childInfo || !$package) {
            abort(404);
        }
    
        // Get the package type (either 'individual' or 'grouping')
        $packageType = $package->type;
        $isWeekly = $package->is_weekly === 'yes'; // Check if the package is weekly
    
        // Fetch all ChildSchedule sessions for the current child where the package type matches
        $childSchedules = ChildSchedule::where('type', $packageType)->get();
    
        $slotsModel = $packageType === 'individual' ? new Slot() : new SlotRTS();
    
        // Fetch slots starting from one day ahead until the end of the current month
        $slots = $slotsModel::where('date', '>=', now()->addDay())
            ->where('date', '<=', now()->endOfMonth())
            ->get();
    
        // Check if the package is weekly
        if ($isWeekly) {
            // Calculate how many slots are needed per week
            $weeksInMonth = 4;
            $slotsPerWeek = $package->session_quantity / $weeksInMonth;
    
            // Filter slots to only show slots that are grouped by week
            $slots = $slots->groupBy(function ($slot) {
                return Carbon::parse($slot->date)->format('W'); // Group by week number
            });
    
            // Remove weeks where the current week has passed
            $currentWeek = now()->format('W');
            $slots = $slots->filter(function ($slotGroup, $weekNumber) use ($currentWeek) {
                return $weekNumber >= $currentWeek;
            });
    
            // Flatten the collection for FullCalendar
            $slots = $slots->flatten();
        } else {
            // Check if the number of available slots is less than the package's session quantity
            $availableSlots = $slots->count();
            $requiredSlots = $package->session_quantity;
    
            if ($availableSlots < $requiredSlots) {
                // If there are not enough slots, fetch slots for the next month
                $slots = $slotsModel::where('date', '>=', now()->startOfMonth()->addMonth())
                    ->where('date', '<=', now()->startOfMonth()->addMonth()->endOfMonth())
                    ->get();
            }
        }
    
        // Map the slots for FullCalendar
        $slots = $slots->map(function ($slot) use ($childSchedules, $package) {
            $bookedSessions = $childSchedules->where('date', $slot->date)
                ->where('time', $slot->start_time)
                ->count();
            $isFull = $bookedSessions >= $package->quota;
    
            return [
                'id' => $slot->id,
                'title' => $isFull ? 'Slot is Full' : 'Available Slot',
                'start' => $slot->date . 'T' . $slot->start_time,
                'end' => $slot->date . 'T' . $slot->end_time,
                'quota' => $package->quota - $bookedSessions,
                'isFull' => $isFull
            ];
        });
    
        return view('editProgSchedule-cs', [
            'csName' => $csName,
            'package' => $package,
            'childInfo' => $childInfo,
            'slots' => $slots,
            'sessionQuantity' => $package->session_quantity,
            'child_id' => $child_id,
            'isWeekly' => $package->weekly === 'yes'
        ]);
    }

    public function csEditProgSchedule(Request $request, $child_id, $package_id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $childInfo = ChildInfo::with('fatherInfo', 'motherInfo', 'parentAccount')->find($child_id); // Eager load the related models
        $package = Package::find($package_id);
        $type = $package->type;
    
        if (!$childInfo || !$package) {
            abort(404);
        }
    
        $selectedSlots = json_decode($request->input('selected_slots')); // Array of selected slots
        $additionalSessions = $request->input('additional_sessions', 0); // Optional additional sessions
        $totalSessions = $package->session_quantity + $additionalSessions;
        $basePrice = 0; // To track base price calculation
        $additionalPrice = $additionalSessions * 100; // Calculate additional session price (RM 100 per session)
    
        // Validate selected slots
        if (!$selectedSlots || !is_array($selectedSlots)) {
            return back()->withErrors(['error' => 'No valid slots selected. Please select at least one slot.']);
        }
    
        // Sort the selected slots by date before inserting
        usort($selectedSlots, function($a, $b) {
            $dateA = Carbon::createFromFormat('m/d/Y', $a->date)->format('Y-m-d');
            $dateB = Carbon::createFromFormat('m/d/Y', $b->date)->format('Y-m-d');
            return strcmp($dateA, $dateB); // Sort in ascending order
        });

        $childInfo->update(['package_id' => $package_id]);

        // Delete all previous schedules for the child
        ChildSchedule::where('child_id', $child_id)->delete();
    
        $sessionId = (string) Str::uuid(); // Generate a unique session ID
        $sessionCounter = 1; // Initialize session counter
    
        // Loop through the sorted selected slots
        foreach ($selectedSlots as $index => $slotData) {
            $slotDate = Carbon::createFromFormat('m/d/Y', $slotData->date)->format('Y-m-d'); // Ensure proper date format
            $slotTime = Carbon::createFromFormat('h:i A', $slotData->start_time)->format('H:i'); // Store start time in 'HH:MM' format
            $slotDay = $slotData->day;
    
            // Check if the slot is available by counting the number of bookings in child_schedules for the same date, time, and day
            $existingBookings = ChildSchedule::where('date', $slotDate)
                                ->where('time', $slotTime)
                                ->where('day', $slotDay)
                                ->count();
    
            if ($existingBookings >= 10) {
                // Skip this slot if the quota is full
                continue;
            }
    
            // Calculate price based on weekday or weekend
            if (in_array($slotDay, ['Friday', 'Saturday'])) {
                $basePrice = $package->package_wkend_price; // Weekend price
            } else {
                $basePrice = $package->package_wkday_price; // Weekday price
            }
    
            // Add the slot to the child's schedule with the total price (base + additional price)
            ChildSchedule::create([
                'child_id' => $child_id,
                'session' => $sessionCounter, // Insert the session number
                'session_id' => $sessionId,
                'day' => $slotDay,
                'date' => $slotDate,
                'time' => $slotTime, // Store only the start_time in 'HH:MM' format
                'price' => $request->input('totalPrice'), // Store the total price
                'type' => $type
            ]);
    
            // Increment session counter for the next session
            $sessionCounter++;
        }
    
        return redirect()->route('stdDetails-cs', ['id' => $child_id])
                         ->with('success', 'Program changed successfully.');
    }

    public function csRescheduleView($id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $schedule = ChildSchedule::find($id);
        $childInfo = $schedule->childInfo;
        $package = $childInfo->package;
        $packageType = $package->type;
        $parentAccount = $childInfo->parentAccount;
        if (!$childInfo) {
            // Redirect to the login page with a flash message
            return Redirect::route('login')->with('error', 'The session has expired. Please return to the login page to log back into your account.');
        }
        

        if (!$schedule) {
            abort(404, 'Schedule not found.');
        }
        $childSchedules = ChildSchedule::where('type', $packageType) // Directly filter by type from ChildSchedule table
         ->get();

        $slotsModel = $packageType === 'individual' ? new Slot() : new SlotRTS();

        $slots = $slotsModel::where('date', '>=', now()->addDay())
        ->where('date', '<=', now()->endOfMonth())
        ->get();

    // Check if the number of available slots is less than the package's session quantity
    $availableSlots = $slots->count();

    if (!$availableSlots) {
        // If there are not enough slots, fetch slots for the next month
        $slots = $slotsModel::where('date', '>=', now()->startOfMonth()->addMonth())
            ->where('date', '<=', now()->startOfMonth()->addMonth()->endOfMonth())
            ->get();
    }

    // Map the slots and check if they are full
    $slots = $slots->map(function ($slot) use ($childSchedules, $package) {
        // Calculate the number of sessions booked for this slot matching the package type
        $bookedSessions = $childSchedules->where('date', $slot->date)
            ->where('time', $slot->start_time)
            ->count();
        // Check if the slot is full (using the package quota)
        $isFull = $bookedSessions >= $package->quota;

                return [
                    'id' => $slot->id,
                    'title' => $isFull ? 'Slot is Full' : 'Available Slot',
                    'start' => $slot->date . 'T' . substr($slot->start_time, 0, 5), // Format time as HH:MM
                    'end' => $slot->date . 'T' . substr($slot->end_time, 0, 5),     // Format time as HH:MM
                    'quota' => $package->quota - $bookedSessions, // Remaining quota
                    'isFull' => $isFull,
                ];
            });
    
        // Pass both the schedule and the available slots to the view
        return view('rescheduleView-cs', [
            'schedule' => $schedule,
            'csName' => $csName,
            'slots' => $slots,  
            'parentAccount' => $parentAccount
        ]);
    }
    public function csReschedule(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'day' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|string',
        ]);
    
        // Find the ChildSchedule record by ID
        $schedule = ChildSchedule::findOrFail($id);
    
        // Update the schedule's date, day, and time
        $schedule->update([
            'day' => $validatedData['day'],   // Corrected from $validatedDate to $validatedData
            'date' => $validatedData['date'],
            'time' => $validatedData['start_time'],
            'status' => 'request',  // Automatically set status to 'request'
        ]);
    
        // Redirect back to the reschedule view with a success message
        return redirect()->route('approveReqView-cs', ['id' => $schedule->id])
                         ->with('success', 'Schedule updated successfully.');
    }

    public function csSession()
    {
        return view('csSession');
    }
    public function csUnassignedList()
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $childInfos = ChildInfo::whereHas('childSchedule', function ($query) {
            $query->where('status', 'pending');
        })->with(['package', 'childSchedule' => function ($query) {
            $query->where('status', 'pending');
        }])->get();

        foreach ($childInfos as $childInfo) {
            $childInfo->age = Carbon::parse($childInfo->child_dob)->age;
            $childInfo->number = $childInfo->childSchedule->count();
        }

        $message = null;
        if ($childInfos->isEmpty()) {
            $message = 'No pending schedules found.';
        }

        return view('unassignedList-cs', [
            'childInfos' => $childInfos,
            'message' => $message,
            'csName' => $csName
        ]);
    }

    public function csAssignTherapist($id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $childInfo = ChildInfo::with(['package', 'childSchedule' => function($query) {
            $query->where('status', 'pending');
        }])->find($id);
    
        if (!$childInfo) {
            abort(404);
        }
        $childInfo->age = Carbon::parse($childInfo->child_dob)->age;
        $therapists = TherapistInfo::all();
        $availableTherapists = [];
    
        foreach ($childInfo->childSchedule as $childSchedule) {
            // Check if schedule type is grouping
            if ($childSchedule->type === 'grouping') {
                // Find any therapist assigned to this date and time for a grouping session
                $assignedTherapist = ChildSchedule::where('type', 'grouping')
                    ->where('date', $childSchedule->date)
                    ->where('time', $childSchedule->time)
                    ->whereNotNull('therapist')
                    ->first();
    
                if ($assignedTherapist) {
                    // If a therapist is already assigned to this grouping session, use that therapist only
                    $availableTherapists[$childSchedule->id] = $therapists->filter(function($therapist) use ($assignedTherapist) {
                        return $therapist->name === $assignedTherapist->therapist;
                    });
                } else {
                    // If no therapist is assigned yet, list all therapists who are free at this date and time
                    $availableTherapists[$childSchedule->id] = $therapists->filter(function($therapist) use ($childSchedule) {
                        return !ChildSchedule::where('therapist', $therapist->name)
                            ->where('date', $childSchedule->date)
                            ->where('time', $childSchedule->time)
                            ->exists();
                    });
                }
            } else {
                // For individual or screening type, ensure only one therapist per schedule at a given time and date
                $availableTherapists[$childSchedule->id] = $therapists->filter(function($therapist) use ($childSchedule) {
                    return !ChildSchedule::where('therapist', $therapist->name)
                        ->where('date', $childSchedule->date)
                        ->where('time', $childSchedule->time)
                        ->exists();
                });
            }
        }
    
        $approvedSchedules = ChildSchedule::where('status', 'approved')
            ->with('childInfo')
            ->get();
    
        $events = $approvedSchedules->map(function ($schedule) {
            $formattedTime = $schedule->time;
            $color = $this->getColorForPackage($schedule->childInfo->package->package_name);
    
            if ($schedule->attendance === 'present') {
                $color = '#28a745'; // Green for present
            } elseif ($schedule->attendance === 'absent') {
                $color = '#ff0000'; // Red for absent
            }
    
            return [
                'title' => "\nSession: " . $schedule->childInfo->package->package_name . "\n" . $schedule->therapist,
                'start' => $schedule->date . 'T' . $formattedTime,
                'details' => "Package: " . $schedule->childInfo->package->package_name . "<br>Date & Time: " . $schedule->date . " / " . $schedule->time .
                            "<br>Student: " . $schedule->childInfo->child_name,
                'color' => $color,
                'attendance' => $schedule->attendance,
            ];
        });
    
        return view('assignTherapist-cs', [
            'childInfo' => $childInfo, 
            'therapists' => $therapists,
            'availableTherapists' => $availableTherapists,
            'events' => $events,
            'csName' => $csName
        ]);
    }
    

    public function assignTherapist(Request $request)
    {
        $data = $request->all();
    
        foreach ($data['schedules'] as $scheduleId) {
            $childSchedule = ChildSchedule::find($scheduleId);
            if ($childSchedule) {
                $childSchedule->therapist = $data['therapist'][$scheduleId];
                $childSchedule->status = $data['status'][$scheduleId];
                $childSchedule->save();
            }
        }
        
        return redirect()->route('unassignedList-cs')->with('success', 'Therapist assigned successfully!');

    }
    public function csAssignedSession()
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $childInfos = ChildInfo::whereHas('childSchedule', function ($query) {
            $query->where('status', 'approved');
        })->with(['package', 'childSchedule' => function ($query) {
            $query->where('status', 'approved');
        }])->get();

        foreach ($childInfos as $childInfo) {
            $childInfo->age = Carbon::parse($childInfo->child_dob)->age;
        }

    
        return view('assignedSession-cs', [
            'childInfos' => $childInfos,
            'csName' => $csName
        ]);
    }
    public function csAssignedDetails($id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $schedules = ChildSchedule::where('child_id', $id)
            ->get();

        $childInfo = ChildInfo::where('id', $id)->first();
    
        return view('assignedDetails-cs', [
            'schedules' => $schedules,
            'childInfo' => $childInfo,
            'csName' => $csName
        ]);
    }
    public function csStdReportList()
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $schedules = ChildSchedule::where('attendance', 'present')
        ->whereHas('sessionReport', function ($query) {
            $query->where('status', 'pending');
        })
        ->with('childInfo')
        ->get();
        return view ('stdReportList-cs', compact('schedules', 'csName'));
    }
    public function csReportApproval($id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $report = SessionReport::where('schedules_id', $id)
        ->with('childSchedule')
        ->first();

        return view ('reportApproval-cs', compact('report', 'csName'));
    }
    public function csReportApproved(Request $request)
    {
        {
            $request->validate([
                'id' => 'required|exists:session_reports,id', // Replace with your actual field name and table
            ]);
    
            // Retrieve the relevant report using the report_id
            $report = SessionReport::find($request->id); // Replace with your actual model
    
            // Update the status
            $report->status = 'approved';
            $report->save();
    
            // Redirect or return response as needed
            return redirect()->route('stdReportList-cs')->with('success', 'Schedule updated successfully.');
        }
    }
    public function csApprovedReportList()
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $schedules = ChildSchedule::where('attendance', 'present')
        ->whereHas('sessionReport', function ($query) {
            $query->where('status', 'approved');
        })
        ->with('childInfo')
        ->get();
        return view ('approvedReportList-cs', compact('schedules', 'csName'));
    }
    public function csApprovedReport($id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $report = SessionReport::where('schedules_id', $id)
        ->with('childSchedule')
        ->first();

        return view ('approvedReport-cs', compact('report', 'csName'));
    }
    public function csApproveRescheduleList()
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $therapist = TherapistInfo::all();
        $childInfos = ChildInfo::whereHas('childSchedule', function ($query) {
            $query->where('status', 'request');
        })->with(['package', 'childSchedule' => function ($query) {
            $query->where('status', 'request');
        }])->get();

        return view('approveRescheduleList-cs', [
            'childInfos' => $childInfos,
            'therapist' => $therapist,
            'csName' => $csName,
        ]);
    }
    public function csApproveReqView($id)
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $schedule = ChildSchedule::findOrFail($id);
        $therapists = TherapistInfo::all();
        $availableTherapists = [];
        $childInfo = $schedule->childInfo;

        // Check for availability of therapists
        foreach ($therapists as $therapist) {
            $isAvailable = !ChildSchedule::where('therapist', $therapist->name)
                ->where('date', $schedule->date)
                ->where('time', $schedule->time)
                ->exists();
            if ($isAvailable) {
                $availableTherapists[] = $therapist;
            }
        }

        return view('approveReqView-cs', compact('schedule', 'availableTherapists', 'csName', 'childInfo'));
    }

    public function csApproveReq(Request $request, $id)
    {
        $validatedData = $request->validate([
            'therapist' => 'nullable|string',
            'status' => 'required|string',
        ]);
    
        $schedule = ChildSchedule::findOrFail($id);
        $childInfo = $schedule->childInfo;

        $schedule->update([
            'therapist' => $validatedData['therapist'],
            'status' => $validatedData['status'],
        ]);
        if ($validatedData['status'] === 'pending') {
            // Redirect to the 'stdDetails-cs' page if status is 'pending'
            return redirect()->route('stdDetails-cs', ['id' => $childInfo->id])->with('success', 'Therapist assigned, status pending.');
        } elseif ($validatedData['status'] === 'approved') {
            // Redirect to the 'approveRescheduleList-cs' page if status is 'approved'
            return redirect()->route('approveRescheduleList-cs')->with('success', 'Therapist assigned and schedule approved successfully!');
        }
    
        // Default fallback if no known status is passed
        return redirect()->back()->with('error', 'Invalid status value provided.');
    }
    public function csApproveReschedule (Request $request)
    {
        $request->validate([
            'therapist' => 'required|string',
            'status' => 'required|string',
        ]);

        // Retrieve the data from the request
        $therapistName = $request->input('therapist');
        $status = $request->input('status');

        // Retrieve the specific ChildSchedule records and update them
        // Here, you need to know the schedule IDs you are updating
        // Assuming the schedule IDs are passed in the request
        foreach ($request->input('schedules') as $scheduleId) {
            $childSchedule = ChildSchedule::find($scheduleId);
            if ($childSchedule) {
                $childSchedule->therapist = $therapistName;
                $childSchedule->status = $status;
                $childSchedule->save();
            }
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Therapist assigned successfully!');
    }

    public function csAllSession()
    {
        $user = Auth::guard('cs')->user();
        $csName = $user->name;
    
        $schedules = ChildSchedule::all();
    
        $events = $schedules->map(function ($schedule) {
            $formattedTime = ($schedule->time);
            $sessionDateTime = ($schedule->date . ' ' . $schedule->time);
            $currentDateTime = new \DateTime();
    
            $color = '#007bff'; // Default color (blue)
                if ($schedule->status === 'approved') {
                    $color = '#28a745'; // Green for present
                } elseif ($schedule->status === 'pending') {
                    $color = '#dc3545'; // Red for absent
                }
    
    
            return [
                'title' => "\nSession: " . $schedule->childInfo->package->package_name . "\n" . $schedule->childInfo->child_name,
                'start' => $schedule->date . 'T' . $formattedTime,
                'details' => "Package: " . $schedule->childInfo->package->package_name . "<br>Session: " . $schedule->session . "<br>Date & Time: " . $schedule->date . " / " . $schedule->time .
                            "<br>Student: " . $schedule->childInfo->child_name . "<br>Therapist: " . $schedule->therapist . "<br>Status: " . $schedule->status,
                'attendance' => $schedule->attendance, // Add attendance info
                'color' => $color,
            ];
        });
    
        return view('allSession-cs', compact('events', 'csName'));
    }
    
}
