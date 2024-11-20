<?php

namespace App\Http\Controllers;

use Chip\ChipApi;
use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Package;
use App\Models\Payment;
use App\Models\SlotRTS;
use Chip\Model\Product;
use Chip\Model\Purchase;
use App\Models\ChildInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ChildSchedule;
use App\Models\ParentAccount;
use Chip\Model\ClientDetails;
use Chip\Model\PurchaseDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ParentController extends Controller
{
    public function parentDashboard()
    {
        $user = Auth::guard('parent')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
    
        $childInfo = $user->childInfo;
    
        if (!$childInfo) {
            return Redirect::route('login')->with('error', 'No related data found. Please log back into your account.');
        }

        if ($childInfo) {
            $fatherInfo = $childInfo->fatherInfo;
            $motherInfo = $childInfo->motherInfo;
            $parentPermission = $childInfo->parentPermission;
            $parentAccount = $childInfo->parentAccount;
            $currentDateTime = Carbon::now();

            // Filter child schedules where the combined date and time haven't passed
            $childSchedule = $childInfo->childSchedule->filter(function ($schedule) use ($currentDateTime) {
                // Combine the date and time fields into a Carbon instance (for 'Y-m-d' and 'H:i' format)
                $scheduleDateTime = Carbon::createFromFormat('Y-m-d H:i', $schedule->date . ' ' . $schedule->time);
            
                // Filter schedules that are in the future and have status 'approved'
                return $scheduleDateTime->greaterThanOrEqualTo($currentDateTime) && $schedule->status === 'approved';
            })->all();
            
            $package = $childInfo->package;
            $pendingSession = ChildSchedule::where('child_id', $childInfo->id)
            ->whereNull('attendance')->where('status', 'pending')
            ->count();
            $sessionLeft = ChildSchedule::where('child_id', $childInfo->id)
            ->whereNull('attendance')->where('status', 'approved')
            ->count();
            $sessionQuantity = ChildSchedule::where('child_id', $childInfo->id)
            ->count();
            $sessionPresent = ChildSchedule::where('child_id', $childInfo->id)
            ->where('attendance', 'present')
            ->count();
            $reportCount = $childInfo->childSchedule->filter(function ($schedule) {
                return $schedule->sessionReport !== null; 
            })->count();
            
            return view('/dashboard/dashboard-parent')
                ->with('childInfo', $childInfo)
                ->with('fatherInfo', $fatherInfo)
                ->with('motherInfo', $motherInfo)
                ->with('parentPermission', $parentPermission)
                ->with('parentAccount', $parentAccount)
                ->with('childSchedule', $childSchedule)
                ->with('package', $package)
                ->with('sessionLeft', $sessionLeft)
                ->with('sessionQuantity', $sessionQuantity)
                ->with('sessionPresent', $sessionPresent)
                ->with('reportCount', $reportCount)
                ->with('pendingSession', $pendingSession);
        }

         return Redirect::route('login')->with('error', 'The session has expired. Please return to the login page to log back into your account.');
    }
    private function getRelatedData()
    {
        $user = Auth::guard('parent')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
    
        $childInfo = $user->childInfo;
    
        if (!$childInfo) {
            return Redirect::route('login')->with('error', 'No related data found. Please log back into your account.');
        }

        $fatherInfo = $childInfo->fatherInfo;
        $motherInfo = $childInfo->motherInfo;
        $parentPermission = $childInfo->parentPermission;
        $parentAccount = $childInfo->parentAccount;
        $payment = $childInfo->payment;
        $currentDateTime = Carbon::now();
        $latestCreatedAt = $childInfo->childSchedule->max('created_at');

        $childSchedule = $childInfo->childSchedule->filter(function ($schedule) use ($currentDateTime, $latestCreatedAt) {
            // Combine the date and time fields into a Carbon instance (for 'Y-m-d' and 'H:i' format)
            $scheduleDateTime = Carbon::createFromFormat('Y-m-d H:i', $schedule->date . ' ' . $schedule->time);
        
            // Filter schedules that are in the future, have status 'approved', and have the latest created_at date
            return $scheduleDateTime->greaterThanOrEqualTo($currentDateTime)
                && $schedule->status === 'approved'
                && $schedule->created_at->equalTo($latestCreatedAt);
        })->all();
        $childSchedules = $childInfo->childSchedule->first();
        $pendingSchedules = $childInfo->childSchedule->all();
        $package = $childInfo->package;

        // Load the related SessionReport data for the child's schedule

        return [
            'childInfo' => $childInfo,
            'fatherInfo' => $fatherInfo,
            'motherInfo' => $motherInfo,
            'parentPermission' => $parentPermission,
            'parentAccount' => $parentAccount,
            'pendingSchedules' => $pendingSchedules,
            'payment' => $payment,
            'childSchedule' => $childSchedule,
            'childSchedules' => $childSchedules,
            'package' => $package,
        ];
    }
    public function parentScheduleView()
    {
        $data = $this->getRelatedData();
        if ($data instanceof RedirectResponse) {
            return $data;
        }
        $user = Auth::guard('parent')->user();
        $childInfo = $user->childInfo;
    
        $childSchedule = ChildSchedule::where('child_id', $childInfo->id)
            ->where('status', 'approved')
            ->with('childInfo')
            ->get();
    
            $events = $childSchedule->map(function ($schedule) {
                // Extract HH:MM from time
                $formattedTime = substr($schedule->time, 0, 5);
            
                $color = '#007bff'; // Default color (blue)
                if ($schedule->attendance === 'present') {
                    $color = '#28a745'; // Green for present
                } elseif ($schedule->attendance === 'absent') {
                    $color = '#dc3545'; // Red for absent
                }
            
                $details = "Package: " . $schedule->childInfo->package->package_name .
                    "<br>Session: " . $schedule->session .
                    "<br>Date & Time: " . $schedule->date . " / " . $formattedTime .
                    "<br>Therapist: " . $schedule->therapist ;
                
                if (!is_null($schedule->attendance)) {
                    $details .= "<br>Attendance: " . $schedule->attendance;
                }
                
                if (is_null($schedule->attendance) || $schedule->attendance === 'absent') {
                    $details .= '<br><a href="' . route('reschedule.view', ['id' => $schedule->id]) . '" class="btn btn-primary">Req reschedule</a>';
                }
            
                return [
                    'title' => "Session " . $schedule->session . "\n" . $schedule->childInfo->package->package_name . "\n" . $schedule->therapist,
                    'start' => $schedule->date . 'T' . $formattedTime, // Date & time in 'YYYY-MM-DDTHH:MM' format
                    'attendance' => $schedule->attendance,
                    'color' => $color,
                    'details' => $details,
                ];
            });
            
    
        return view('/schedule-parent', compact('events'))->with($data);
    }
    private function convertTimeFormat($time)
    {
        // Parse time string like "9.00 a.m." to "HH:MM:SS"
        try {
            $dateTime = Carbon::createFromFormat('g.i a', $time);
            return $dateTime->format('H:i:s');
        } catch (\Exception $e) {
            return null; // or handle the error as needed
        }
    }
    public function parentProfile()
    {
        $data = $this->getRelatedData();
    
        // Handle the case where getRelatedData returns a redirect
        if ($data instanceof RedirectResponse) {
            return $data;
        }
    
        // Check if $data is valid and pass it to the view, or show an error message
        if (!empty($data)) {
            return view('profile-parent')->with($data);
        }
    
        // If no data is found, pass an error message to the view
        return view('profile-parent')->with('error', 'No related data found.');
    }
    public function rescheduleView($id)
    {
        $user = Auth::guard('parent')->user();
        if ($data instanceof RedirectResponse) {
            return $data;
        }

        $childInfo = $user->childInfo;
        $package = $childInfo->package;
        $packageType = $package->type;
        $parentAccount = $childInfo->parentAccount;
        if (!$childInfo) {
            // Redirect to the login page with a flash message
            return Redirect::route('login')->with('error', 'The session has expired. Please return to the login page to log back into your account.');
        }
        $schedule = ChildSchedule::find($id);

        if (!$schedule) {
            abort(404, 'Schedule not found.');
        }
        $childSchedules = ChildSchedule::where('type', $packageType) // Directly filter by type from ChildSchedule table
         ->get();

        $slotsModel = $packageType === 'individual' ? new Slot() : new SlotRTS();

         $slots = $slotsModel::where('date', '>=', now()->addDay())
         ->where('date', '<=', now()->endOfMonth())
         ->get()
         ->map(function ($slot) use ($childSchedules, $package) {
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
        return view('rescheduleView-parent', [
            'schedule' => $schedule,
            'slots' => $slots,  
            'parentAccount' => $parentAccount
        ]);
    }

    public function reschedule(Request $request, $id)
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
        return redirect()->route('schedule.view')
                         ->with('success', 'Schedule updated successfully.');
    }
    
    public function announcement()
    {
        $data = $this->getRelatedData();
        if ($data instanceof RedirectResponse) {
            return $data;
        }
    
            return view('announcement-parent')->with($data);
    }

    public function paymentList()
    {
        {
            $data = $this->getRelatedData();
            if ($data instanceof RedirectResponse) {
                return $data;
            }
            return view('paymentList-parent')->with($data);
        
    }}
    public function program()
    {
        $data = $this->getRelatedData();
        if ($data instanceof RedirectResponse) {
            return $data;
        }
        return view('program-parent')->with($data);
    }

    public function changeProgram($child_id, $package_id)
    {
        $data = $this->getRelatedData();
        if ($data instanceof RedirectResponse) {
            return $data;
        }
        $childInfo = ChildInfo::find($child_id);
        $package = Package::find($package_id);
    
        // Determine consultation filter based on the current package's consultation value
        $consultationFilter = $package->consultation === 'Yes' ? 'No' : 'Yes';
    
        // Filter packages based on child nationality, consultation filter, and excluding package_step "step 1"
        if ($childInfo->child_nationality === 'Malaysian') {
            // Retrieve packages for Malaysian children with specified consultation, citizenship set to 'yes', and not "step 1"
            $packages = Package::where('citizenship', 'yes')
                ->where('consultation', $consultationFilter)
                ->where('type', '!=', 'screening')
                ->where('package_step', '!=', 'step 1')
                ->orderBy('package_name', 'asc')
                ->get();
        } else {
            // Retrieve packages for non-Malaysian children with specified consultation, citizenship set to 'no', and not "step 1"
            $packages = Package::where('citizenship', 'no')
                ->where('consultation', $consultationFilter)
                ->where('type', '!=', 'screening')
                ->where('package_step', '!=', 'step 1')
                ->orderBy('package_name', 'asc')
                ->get();
        }
    
        return view('changeProgram-parent', compact('packages'))->with($data);
    }

    public function newScheduleView($child_id, $package_id)
    {
        $data = $this->getRelatedData();
        if ($data instanceof RedirectResponse) {
            return $data;
        }
        $childInfo = ChildInfo::find($child_id);
        $newPackage = Package::find($package_id);
        
        if (!$childInfo || !$newPackage) {
            abort(404);
        }
    
        // Get the package type (either 'individual' or 'grouping')
        $packageType = $newPackage->type;
        $isWeekly = $newPackage->weekly === 'yes'; // Check if the package is weekly
    
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
            $slotsPerWeek = $newPackage->session_quantity / $weeksInMonth;
        
            // Get the current week and the first week of the month
            $currentWeek = now()->format('W');
            $firstWeekOfMonth = Carbon::now()->startOfMonth()->format('W');
        
            // If the first week has passed, load slots for the next month
            if ($currentWeek > $firstWeekOfMonth) {
                // Fetch slots for the next month
                $slots = $slotsModel::where('date', '>=', now()->addMonth()->startOfMonth())
                    ->where('date', '<=', now()->addMonth()->endOfMonth())
                    ->get();
            } else {
                // Filter slots to only show slots that are grouped by week for the current month
                $slots = $slots->groupBy(function ($slot) {
                    return Carbon::parse($slot->date)->format('W'); // Group by week number
                });
        
                // Remove weeks where the current week has passed
                $slots = $slots->filter(function ($slotGroup, $weekNumber) use ($currentWeek) {
                    return $weekNumber >= $currentWeek;
                });
        
                // Flatten the collection for FullCalendar
                $slots = $slots->flatten();
            }
        } else {
            // Non-weekly package handling remains the same
            $availableSlots = $slots->count();
            $requiredSlots = $newPackage->session_quantity;
        
            if ($availableSlots < $requiredSlots) {
                $slots = $slotsModel::where('date', '>=', now()->startOfMonth()->addMonth())
                    ->where('date', '<=', now()->startOfMonth()->addMonth()->endOfMonth())
                    ->get();
            }
        }
        // Map the slots for FullCalendar
        $slots = $slots->map(function ($slot) use ($childSchedules, $newPackage) {
            $bookedSessions = $childSchedules->where('date', $slot->date)
                ->where('time', $slot->start_time)
                ->count();
            $isFull = $bookedSessions >= $newPackage->quota;
    
            return [
                'id' => $slot->id,
                'title' => $isFull ? 'Slot is Full' : 'Available Slot',
                'start' => $slot->date . 'T' . $slot->start_time,
                'end' => $slot->date . 'T' . $slot->end_time,
                'quota' => $newPackage->quota - $bookedSessions,
                'isFull' => $isFull
            ];
        });
    
        return view('newSchedule-parent', [
            'newPackage' => $newPackage,
            'childInfo' => $childInfo,
            'slots' => $slots,
            'sessionQuantity' => $newPackage->session_quantity,
            'child_id' => $child_id,
            'isWeekly' => $newPackage->weekly === 'yes'
        ])->with($data);
    }
    
    public function newScheduleSubmit(Request $request, $child_id, $package_id)
    {
        $childInfo = ChildInfo::with('fatherInfo', 'motherInfo', 'parentAccount')->find($child_id); // Eager load the related models
        $package = Package::find($package_id);
        $type = $package->type;
    
        if (!$childInfo || !$package) {
            abort(404);
        }
        $consultation = $package->consultation; // Get the consultation value
    
        $selectedSlots = json_decode($request->input('selected_slots')); // Array of selected slots
        $additionalSessions = $request->input('additional_sessions', 0); // Optional additional sessions
        $totalSessions = $package->session_quantity + $additionalSessions;
        $additionalPrice = $additionalSessions * 100; // Calculate additional session price (RM 100 per session)
    
        // Validate selected slots
        if (!$selectedSlots || !is_array($selectedSlots)) {
            return back()->withErrors(['error' => 'No valid slots selected. Please select at least one slot.']);
        }
        $hasWeekendSlot = false;
        $basePrice = $package->package_wkday_price; // Default to weekday price
    
        // Sort the selected slots by date before inserting
        usort($selectedSlots, function($a, $b) {
            $dateA = Carbon::createFromFormat('m/d/Y', $a->date)->format('Y-m-d');
            $dateB = Carbon::createFromFormat('m/d/Y', $b->date)->format('Y-m-d');
            return strcmp($dateA, $dateB); // Sort in ascending order
        });
    
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
                $hasWeekendSlot = true; // Set the flag if a weekend slot is found
                break; // No need to check further, we found a weekend slot
            }
        }
        if ($hasWeekendSlot) {
            $basePrice = $package->package_wkend_price; // Set to weekend price
        }

    session([
        'type' => $type,
        'childInfo' => $childInfo,
        'package' => $package,
        'fatherInfo' => $childInfo->fatherInfo,
        'motherInfo' => $childInfo->motherInfo,
        'parentAccount' => $childInfo->parentAccount,
        'selectedSlots' => $selectedSlots,
        'basePrice' => $basePrice,
        'additionalSessions' => $additionalSessions,
        'additionalPrice' => $additionalPrice,
        'totalPrice' => $basePrice + $additionalPrice,
        'child_id' => $child_id,
        'sessionId' => $sessionId,
        'sessionCounter' => $sessionCounter
    ]);

    if ($consultation === 'Yes') {
        return redirect()->route('newConsultSchedule-parent', ['child_id' => $child_id, 'package_id' => $package_id]);
    } else {
        return redirect()->route('newProgPayment-parent', ['child_id' => $child_id, 'package_id' => $package_id]);
    }
}

public function newConsultScheduleView($child_id, $package_id)
    {
        // Find child and package information
        $data = $this->getRelatedData();
        if ($data instanceof RedirectResponse) {
            return $data;
        }
        $childInfo = ChildInfo::find($child_id);
        $package = Package::find($package_id);
    
        if (!$childInfo || !$package) {
            abort(404);
        }
    
        // Get the package type (either 'individual' or 'grouping')
        $packageType = 'screening';
        $isWeekly = $package->is_weekly === 'yes'; // Check if the package is weekly
    
        // Fetch all ChildSchedule sessions for the current child where the package type matches
        $childSchedules = ChildSchedule::where('type', $packageType)->get();
    
        // Fetch slots starting from one day ahead until the end of the current month
        $slots = Slot::where('date', '>=', now()->addDay())
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
            $requiredSlots = 1;
    
            if ($availableSlots < $requiredSlots) {
                // If there are not enough slots, fetch slots for the next month
                $slots = Slot::where('date', '>=', now()->startOfMonth()->addMonth())
                    ->where('date', '<=', now()->startOfMonth()->addMonth()->endOfMonth())
                    ->get();
            }
        }
    
        // Map the slots for FullCalendar
        $slots = $slots->map(function ($slot) use ($childSchedules, $package) {
            $bookedSessions = $childSchedules->where('date', $slot->date)
                ->where('time', $slot->start_time)
                ->count();
            $isFull = $bookedSessions >= 2;
    
            return [
                'id' => $slot->id,
                'title' => $isFull ? 'Slot is Full' : 'Available Slot',
                'start' => $slot->date . 'T' . $slot->start_time,
                'end' => $slot->date . 'T' . $slot->end_time,
                'quota' => 2 - $bookedSessions,
                'isFull' => $isFull
            ];
        });
    
        return view('newConsultSchedule-parent', [
            'package' => $package,
            'childInfo' => $childInfo,
            'slots' => $slots,
            'sessionQuantity' => 1,
            'child_id' => $child_id,
            'isWeekly' => $package->weekly === 'yes'
        ])->with($data);
    }

    public function newConsultSchedule(Request $request, $child_id, $package_id)
    {
        $data = $this->getRelatedData();
        $childInfo = ChildInfo::with('fatherInfo', 'motherInfo', 'parentAccount')->find($child_id); // Eager load the related models
        $package = Package::find($package_id);
        $type = 'screening';
    
        if (!$childInfo || !$package) {
            abort(404);
        }
        $consultation = $package->consultation; // Get the consultation value
    
        $consultSlot = json_decode($request->input('selected_slots'), false); // Array of selected slots
    
        // Validate selected slots
        if (!$consultSlot || !is_array($consultSlot)) {
            return back()->withErrors(['error' => 'No valid slots selected. Please select at least one slot.']);
        }


        session([
            'consultSlot' => $consultSlot
        ]);

        return redirect()->route('newProgPayment-parent', ['child_id' => $child_id, 'package_id' => $package_id]);

}

    public function newProgPayment($child_id, $package_id)
    {
        $data = $this->getRelatedData();
        if ($data instanceof RedirectResponse) {
            return $data;
        }
        if (!session()->has('childInfo') || !session()->has('package')) {
            return redirect()->back()->withErrors('Session data not found.');
        }
        // Retrieve data from the session
        $type = session('type');
        $package_id = session('package_id');
        $childInfo = session('childInfo');
        $package = session('package');
        $fatherInfo = session('fatherInfo');
        $motherInfo = session('motherInfo');
        $parentAccount = session('parentAccount');
        $totalPrice = session('totalPrice');
        $selectedSlots = session('selectedSlots');
        $consultSlot = session('consultSlot', []);
        $additionalSessions = session('additionalSessions');
        $additionalPrice = session('additionalPrice');
        $basePrice = session('basePrice');
        $sessionId = session('sessionId');
        $sessionCounter = session('sessionCounter');

        return view('newProgPayment-parent', [
            'type' => $type,
            'package' => $package,
            'childInfo' => $childInfo,
            'fatherInfo' => $fatherInfo,
            'motherInfo' => $motherInfo,
            'parentAccount' => $parentAccount,
            'totalPrice' => $totalPrice,
            'selectedSlots' => $selectedSlots,
            'consultSlot' => $consultSlot,
            'additionalSessions' => $additionalSessions,
            'additionalPrice' => $additionalPrice,
            'child_id' => $child_id,
            'basePrice' => $basePrice,
            'sessionId' => $sessionId,
            'sessionCounter' => $sessionCounter
        ])->with($data);
    }

    public function submitNewProgPayment(Request $request)
{
    $package_id = $request->input('package_id');
    $child_id = $request->input('child_id');
    $totalPrice = $request->input('total_price');
    $parent_id = $request->input('parent_id');
    $session_id = $request->input('session_id');
    $selectedSlots = session('selectedSlots'); // Retrieve slots from session
    $consultSlot = session('consultSlot', []);
    $sessionCounter = session('sessionCounter'); // Retrieve slots from session
    $type = session('type');
    $reference = Str::uuid();
    $parentAccount = ParentAccount::where('child_id', $child_id)->first();
    // Insert selected slots into the ChildSchedule table
    foreach ($selectedSlots as $slotData) {
        $slotDate = Carbon::createFromFormat('m/d/Y', $slotData->date)->format('Y-m-d');
        $slotTime = Carbon::createFromFormat('h:i A', $slotData->start_time)->format('H:i');
        $slotDay = $slotData->day;

        // Check if this schedule already exists
        $exists = ChildSchedule::where('child_id', $child_id)
            ->where('session_id', $session_id)
            ->where('date', $slotDate)
            ->where('time', $slotTime)
            ->where('type', $type) // Check the type to avoid duplicating regular session
            ->exists();

        if (!$exists) {
            ChildSchedule::create([
                'child_id' => $child_id,
                'session_id' => $session_id,
                'day' => $slotDay,
                'date' => $slotDate,
                'time' => $slotTime,
                'price' => $totalPrice,
                'status' => 'pending',
                'session' => $sessionCounter,
                'type' => $type
            ]);
            $sessionCounter++;
        }
    }

    // Insert consultation slots if they do not already exist for this session_id and child_id
    foreach ($consultSlot as $slotData) {
        $slotDate = Carbon::createFromFormat('m/d/Y', $slotData->date)->format('Y-m-d');
        $slotTime = Carbon::createFromFormat('h:i A', $slotData->start_time)->format('H:i');
        $slotDay = $slotData->day;

        // Check if this consultation schedule already exists
        $exists = ChildSchedule::where('child_id', $child_id)
            ->where('session_id', $session_id)
            ->where('date', $slotDate)
            ->where('time', $slotTime)
            ->where('type', 'screening') // Check the type to avoid duplicating consultation session
            ->exists();

        if (!$exists) {
            ChildSchedule::create([
                'child_id' => $child_id,
                'session_id' => $session_id,
                'day' => $slotDay,
                'date' => $slotDate,
                'time' => $slotTime,
                'price' => $totalPrice,
                'status' => 'pending',
                'session' => $sessionCounter,
                'type' => 'screening'
            ]);
        }
    }
    $childInfo = ChildInfo::find($child_id);
    $childInfo->update(['package_id' => $package_id]);
    // Create a new payment record
    $payment = Payment::create([
        'child_id' => $child_id,
        'parent_id' => $parent_id,
        'reference' => $reference,
        'total_amount' => $totalPrice,
        'payment_method' => 'FPX',
        'status' => 'pending', // Initial status is 'pending'
        'session_id' => $session_id
    ]);
    $parentEmail = $parentAccount->email;

    $paymentData = [
        'amount' => $totalPrice * 100, // Amount in cents
        'currency' => 'MYR',
        'email' => $parentEmail, // Replace with the parent's email
        'description' => 'Payment for Child ID: ' . $child_id,
        'reference' => $reference,
        'payment_method' => 'FPX',
    ];

    // Your existing code for creating purchase
    $brandId = config('services.chip.brand_id');
    $apiKey = config('services.chip.api_key');
    $endpoint = config('services.chip.endpoint');
    $chip = new ChipApi($brandId, $apiKey, $endpoint);

    $client = new ClientDetails();
    $client->email = $parentEmail;
    
    $purchase = new Purchase();
    $purchase->client = $client;
    
    $details = new PurchaseDetails();
    $product = new Product();
    $product->name = 'Payment for Child ID: ' . $child_id;
    $product->price = $totalPrice * 100;
    $details->products = [$product];
    
    $purchase->purchase = $details;
    $purchase->brand_id = $brandId;
    $purchase->success_redirect = 'https://system.idzmirkidshub.com/chip/callback/api/redirect.php?success=1';
    $purchase->failure_redirect = 'https://system.idzmirkidshub.com/chip/callback/api/redirect.php?success=0';
    $purchase->success_callback = 'https://system.idzmirkidshub.com/chip/callback/api/callback.php';
	$purchase->payment_method_whitelist = ['fpx'];
	
	$result = $chip->createPurchase($purchase);

        $exploded_url = explode('/', $result->checkout_url, -1);
        $payment->payment_id = end($exploded_url);
        $payment->save();
        if ($result && $result->checkout_url) {
            // Redirect user to checkout
            header("Location: " . $result->checkout_url);
            exit;
               }
}
}
