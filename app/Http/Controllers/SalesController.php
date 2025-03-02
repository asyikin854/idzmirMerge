<?php

namespace App\Http\Controllers;

use Log;
use Exception;
use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Package;
use App\Models\Payment;
use App\Models\SlotRTS;
use App\Models\ChildInfo;
use App\Models\FatherInfo;
use App\Models\MotherInfo;
use App\Models\NewCustomer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ChildSchedule;
use App\Models\ParentAccount;
use App\Mail\ParentCredentials;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class SalesController extends Controller
{
    public function salesDashboard()
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $totalCustomer = NewCustomer::all()->count();
        $newRegister = NewCustomer::where('progress', 'registered')->count();
        $unregistered = $totalCustomer - $newRegister;

        return view('dashboard/dashboard-sales', compact('salesName', 'salesInfo', 'totalCustomer', 'newRegister', 'unregistered'));
    }
    
    public function newCustomer()
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $customers = NewCustomer::all();
        
        return view ('newCustomer-sales', compact('salesName', 'customers'));
    }

    public function addCustomer(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string', // Assuming it's optional
            'posscode' => 'nullable|string', // Assuming it's optional
            'city' => 'nullable|string', // Assuming it's optional
            'country' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        $addCustomer = NewCustomer::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'address' => $validatedData['address'],
            'posscode' => $validatedData['posscode'],
            'city' => $validatedData['city'],
            'country' => $validatedData['country'],
            'remark' => $validatedData['remark'],
        ]);
        return redirect()->route('newCustomer-sales');
    }

    public function regNewCust($id)
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $customer = NewCustomer::findOrFail($id);
        $packages = Package::where('type', 'screening')->get();

        return view ('regNewCust-sales', compact('customer', 'salesName', 'packages'));
    }

    public function registerCust(Request $request, $id)
    {
        $customer = NewCustomer::findOrFail($id);

        $validatedData = $request->validate([
            'child_name' => 'required|string',
            'child_ic' => 'nullable|string',
            'child_dob' => 'required|date',
            'child_passport' => 'nullable|string', // Assuming it's optional
            'child_nationality' => 'required|string',
            'child_race' => 'nullable|string',
            'child_bp' => 'nullable|string',
            'child_religion' => 'nullable|string',
            'child_sex' => 'required|string',
            'child_address' => 'required|string',
            'child_posscode' => 'required|string',
            'child_city' => 'required|string',
            'child_country' => 'required|string',
            'father_name' => 'nullable|string',
            'father_phone' => 'nullable|string',
            'father_ic' => 'nullable|string|max:12',
            'father_race' => 'nullable|string',
            'father_occ' => 'nullable|string',
            'father_email' => 'nullable|email',
            'father_address' => 'nullable|string',
            'father_posscode' => 'nullable|string',
            'father_city' => 'nullable|string',
            'father_work_address' => 'nullable|string',
            'father_work_posscode' => 'nullable|string',
            'father_work_city' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'mother_phone' => 'nullable|string',
            'mother_ic' => 'nullable|string|max:12',
            'mother_race' => 'nullable|string',
            'mother_occ' => 'nullable|string',
            'mother_email' => 'nullable|email',
            'mother_address' => 'nullable|string',
            'mother_posscode' => 'nullable|string',
            'mother_city' => 'nullable|string',
            'mother_work_address' => 'nullable|string',
            'mother_work_posscode' => 'nullable|string',
            'mother_work_city' => 'nullable|string',
            'package_id' => 'required'
        ]);

        $childInfo = ChildInfo::create([
            'child_name' => $validatedData['child_name'],
            'child_ic' => $validatedData['child_ic'],
            'child_dob' => $validatedData['child_dob'],
            'child_passport' => $validatedData['child_passport'],
            'child_nationality' => $validatedData['child_nationality'],
            'child_race' => $validatedData['child_race'],
            'child_bp' => $validatedData['child_bp'],
            'child_religion' => $validatedData['child_religion'],
            'child_sex' => $validatedData['child_sex'],
            'child_address' => $validatedData['child_address'],
            'child_posscode' => $validatedData['child_posscode'],
            'child_city' => $validatedData['child_city'],
            'child_country' => $validatedData['child_country'],
            'package_id' => $validatedData['package_id']
        ]);
       
        // Create a new FatherInfo record linked to ChildInfo
        if (!empty($validatedData['father_name']) || !empty($validatedData['father_phone']) || !empty($validatedData['father_ic'])) {
            FatherInfo::create([
                'child_id' => $childInfo->id,
                'father_name' => $validatedData['father_name'],
                'father_phone' => $validatedData['father_phone'],
                'father_ic' => $validatedData['father_ic'],
                'father_race' => $validatedData['father_race'],
                'father_occ' => $validatedData['father_occ'],
                'father_email' => $validatedData['father_email'],
                'father_address' => $validatedData['father_address'],
                'father_posscode' => $validatedData['father_posscode'],
                'father_city' => $validatedData['father_city'],
                'father_work_address' => $validatedData['father_work_address'],
                'father_work_posscode' => $validatedData['father_work_posscode'],
                'father_work_city' => $validatedData['father_work_city'],
            ]);
        }
    
        // Check if any mother info is provided before creating MotherInfo
        if (!empty($validatedData['mother_name']) || !empty($validatedData['mother_phone']) || !empty($validatedData['mother_ic'])) {
            MotherInfo::create([
                'child_id' => $childInfo->id,
                'mother_name' => $validatedData['mother_name'],
                'mother_phone' => $validatedData['mother_phone'],
                'mother_ic' => $validatedData['mother_ic'],
                'mother_race' => $validatedData['mother_race'],
                'mother_occ' => $validatedData['mother_occ'],
                'mother_email' => $validatedData['mother_email'],
                'mother_address' => $validatedData['mother_address'],
                'mother_posscode' => $validatedData['mother_posscode'],
                'mother_city' => $validatedData['mother_city'],
                'mother_work_address' => $validatedData['mother_work_address'],
                'mother_work_posscode' => $validatedData['mother_work_posscode'],
                'mother_work_city' => $validatedData['mother_work_city'],
            ]);
        }

        $customer->update([
            'progress' => 'registered'
        ]);

        $child_id = $childInfo->id;
        $package_id = $validatedData['package_id'];

        return redirect()->route('scheduleSlot-sales', ['child_id' => $child_id, 'package_id' => $package_id]);
    }
    public function bookConsultView($child_id, $package_id)
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $childInfo = ChildInfo::find($child_id);
        $package = Package::find($package_id);
    
        $schedules = ChildSchedule::where('type', 'screening')->get();
    
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
    
        return view('bookConsult-sales', compact('events', 'salesName', 'child_id', 'childInfo', 'package'));
    }

    // public function scheduleSlotView($child_id, $package_id)
    // {
    //     $user = Auth::guard('sales')->user();
    //     if (!$user) {
    //         return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
    //     }
    //     $salesInfo = $user;
    //     $salesName = $user->name;
    //     // Find child and package information
    //     $childInfo = ChildInfo::find($child_id);
    //     $package = Package::find($package_id);
    
    //     if (!$childInfo || !$package) {
    //         abort(404);
    //     }
    
    //     // Get the package type (either 'individual' or 'grouping')
    //     $packageType = $package->type;
    //     $isWeekly = $package->is_weekly === 'yes'; // Check if the package is weekly
    
    //     // Fetch all ChildSchedule sessions for the current child where the package type matches
    //     $childSchedules = ChildSchedule::where('type', $packageType)->get();
    
    //     $slotsModel = $packageType === 'screening' ? new Slot() : new SlotRTS();
    
    //     // Fetch slots starting from one day ahead until the end of the current month
    //     $slots = $slotsModel::where('date', '>=', now()->addDay())
    //         ->where('date', '<=', now()->endOfMonth())
    //         ->get();
    
    //     // Check if the package is weekly
    //     if ($isWeekly) {
    //         // Calculate how many slots are needed per week
    //         $weeksInMonth = 4;
    //         $slotsPerWeek = $package->session_quantity / $weeksInMonth;
    
    //         // Filter slots to only show slots that are grouped by week
    //         $slots = $slots->groupBy(function ($slot) {
    //             return Carbon::parse($slot->date)->format('W'); // Group by week number
    //         });
    
    //         // Remove weeks where the current week has passed
    //         $currentWeek = now()->format('W');
    //         $slots = $slots->filter(function ($slotGroup, $weekNumber) use ($currentWeek) {
    //             return $weekNumber >= $currentWeek;
    //         });
    
    //         // Flatten the collection for FullCalendar
    //         $slots = $slots->flatten();
    //     } else {
    //         // Check if the number of available slots is less than the package's session quantity
    //         $availableSlots = $slots->count();
    //         $requiredSlots = $package->session_quantity;
    
    //         if ($availableSlots < $requiredSlots) {
    //             // If there are not enough slots, fetch slots for the next month
    //             $slots = $slotsModel::where('date', '>=', now()->startOfMonth()->addMonth())
    //                 ->where('date', '<=', now()->startOfMonth()->addMonth()->endOfMonth())
    //                 ->get();
    //         }
    //     }
    
    //     // Map the slots for FullCalendar
    //     $slots = $slots->map(function ($slot) use ($childSchedules, $package) {
    //         $bookedSessions = $childSchedules->where('date', $slot->date)
    //             ->where('time', $slot->start_time)
    //             ->count();
    //         $isFull = $bookedSessions >= $package->quota;
    
    //         return [
    //             'id' => $slot->id,
    //             'title' => $isFull ? 'Slot is Full' : 'Available Slot',
    //             'start' => $slot->date . 'T' . $slot->start_time,
    //             'end' => $slot->date . 'T' . $slot->end_time,
    //             'quota' => $package->quota - $bookedSessions,
    //             'isFull' => $isFull
    //         ];
    //     });
    
    //     return view('scheduleSlot-sales', [
    //         'package' => $package,
    //         'childInfo' => $childInfo,
    //         'slots' => $slots,
    //         'sessionQuantity' => $package->session_quantity,
    //         'child_id' => $child_id,
    //         'isWeekly' => $package->weekly === 'yes',
    //         'salesName' => $salesName
    //     ]);
    // }

    public function scheduleSlot(Request $request, $child_id, $package_id)
    {
        $validatedData = $request->validate([
            'consult_date' => "required|date",
            'consult_day' => "required|string",
            'consult_time' => "required|string",
        ]);

        $childInfo = ChildInfo::with('fatherInfo', 'motherInfo', 'parentAccount')->find($child_id); // Eager load the related models
        $package = Package::find($package_id);
        $type = $package->type;
    
        if (!$childInfo || !$package) {
            abort(404);
        }

        // Determine the price based on the consult_day
        if (strtolower($validatedData['consult_day']) === 'friday' || strtolower($validatedData['consult_day']) === 'saturday') {
            $price = $package->package_wkend_price;
        } else {
            $price = $package->package_wkday_price;
        }
        $totalPrice = $price;
    
        // $selectedSlots = json_decode($request->input('selected_slots')); // Array of selected slots
        // $additionalSessions = $request->input('additional_sessions', 0); // Optional additional sessions
        // $totalSessions = $package->session_quantity + $additionalSessions;
        // $additionalPrice = $additionalSessions * 100; // Calculate additional session price (RM 100 per session)
    
        // // Validate selected slots
        // if (!$selectedSlots || !is_array($selectedSlots)) {
        //     return back()->withErrors(['error' => 'No valid slots selected. Please select at least one slot.']);
        // }
        // $hasWeekendSlot = false;
        // $basePrice = $package->package_wkday_price; // Default to weekday price
    
        // // Sort the selected slots by date before inserting
        // usort($selectedSlots, function($a, $b) {
        //     $dateA = Carbon::createFromFormat('m/d/Y', $a->date)->format('Y-m-d');
        //     $dateB = Carbon::createFromFormat('m/d/Y', $b->date)->format('Y-m-d');
        //     return strcmp($dateA, $dateB); // Sort in ascending order
        // });
    
        $sessionId = (string) Str::uuid(); // Generate a unique session ID
        $sessionCounter = 1; // Initialize session counter
    
        // Loop through the sorted selected slots
        // foreach ($selectedSlots as $index => $slotData) {
        //     $slotDate = Carbon::createFromFormat('m/d/Y', $slotData->date)->format('Y-m-d'); // Ensure proper date format
        //     $slotTime = Carbon::createFromFormat('h:i A', $slotData->start_time)->format('H:i'); // Store start time in 'HH:MM' format
        //     $slotDay = $slotData->day;
    
        //     // Check if the slot is available by counting the number of bookings in child_schedules for the same date, time, and day
        //     $existingBookings = ChildSchedule::where('date', $slotDate)
        //                         ->where('time', $slotTime)
        //                         ->where('day', $slotDay)
        //                         ->count();
    
        //     if ($existingBookings >= 10) {
        //         // Skip this slot if the quota is full
        //         continue;
        //     }
    
        //     // Calculate price based on weekday or weekend
        //     if (in_array($slotDay, ['Friday', 'Saturday'])) {
        //         $hasWeekendSlot = true; // Set the flag if a weekend slot is found
        //         break; // No need to check further, we found a weekend slot
        //     }
        // }
        // if ($hasWeekendSlot) {
        //     $basePrice = $package->package_wkend_price; // Set to weekend price
        // }
        $consultDetails = [
            'consult_date' => $validatedData['consult_date'],
            'consult_day' => $validatedData['consult_day'],
            'consult_time' => $validatedData['consult_time'],
        ];

    session([
        'type' => $type,
        'childInfo' => $childInfo,
        'package' => $package,
        'totalPrice' => $totalPrice,
        'fatherInfo' => $childInfo->fatherInfo,
        'motherInfo' => $childInfo->motherInfo,
        'consultDetails' => $consultDetails,
        'child_id' => $child_id,
        'sessionId' => $sessionId,
        'sessionCounter' => $sessionCounter
    ]);


    return redirect()->route('confirmSchedule-sales', ['child_id' => $child_id, 'package_id' => $package_id]);
}

public function confirmScheduleView($child_id, $package_id)
{
    $user = Auth::guard('sales')->user();
        $salesInfo = $user;
        $salesName = $user->name;
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
    $consultDetails = session('consultDetails');
    // $selectedSlots = session('selectedSlots');
    // $additionalSessions = session('additionalSessions');
    // $additionalPrice = session('additionalPrice');
    $basePrice = session('basePrice');
    $sessionId = session('sessionId');
    $sessionCounter = session('sessionCounter');
    
    return view('confirmSchedule-sales', [
        'type' => $type,
        'package' => $package,
        'childInfo' => $childInfo,
        'fatherInfo' => $fatherInfo,
        'motherInfo' => $motherInfo,
        'parentAccount' => $parentAccount,
        'totalPrice' => $totalPrice,
        'consultDetails' => $consultDetails,
        // 'selectedSlots' => $selectedSlots,
        // 'additionalSessions' => $additionalSessions,
        // 'additionalPrice' => $additionalPrice,
        'child_id' => $child_id,
        // 'basePrice' => $basePrice,
        'sessionId' => $sessionId,
        'sessionCounter' => $sessionCounter,
        'salesName' => $salesName

    ]);
}

public function confirmSchedule(Request $request)
{
    $package_id = $request->input('package_id');
    $child_id = $request->input('child_id');
    $totalPrice = $request->input('total_price');
    $session_id = $request->input('session_id');
    $consultDetails = $request->input('consultDetails');
    // $selectedSlots = session('selectedSlots'); // Retrieve slots from session
    $sessionCounter = session('sessionCounter'); // Retrieve slots from session
    $type = session('type');
    $reference = Str::uuid();
    
    
    // Insert selected slots into the ChildSchedule table

        $slotDate = Carbon::createFromFormat('m/d/Y', $consultDetails->consult_date)->format('Y-m-d'); // Format the date correctly
        $slotTime = Carbon::createFromFormat('h:i A', $consultDetails->consult_time)->format('H:i'); // Convert to 'HH:MM' format
        $slotDay = $consultDetails->consult_day;
        // Insert each selected slot into ChildSchedule
        ChildSchedule::create([
            'child_id' => $child_id,
            'session_id' => $session_id,
            'day' => $slotDay,
            'date' => $slotDate,
            'time' => $slotTime, // Store only the start time
            'price' => $totalPrice, // Store the total price
            'status' => 'pending', // Status for the schedule, not related to payment
            'session' => 'consultation', // Store session counter
            'type' => $type
        ]);
        // $sessionCounter++;
    
    $request->validate([
        'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx',
    ]);
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '.' . $file->extension();
        $path = $file->move('receipts', $filename, 'public');
    } else {
        $filename = null;
        $path = null;
    }
    // Create a new payment record
    $payment = Payment::create([
        'child_id' => $child_id,
        'reference' => $reference,
        'total_amount' => $totalPrice,
        'payment_method' => 'qr/transfer',
        'status' => 'paid', // Initial status is 'pending'
        'session_id' => $session_id,
        'filename' => $filename,
        'path' => $path,
    ]); 

    return redirect()->route('registeredCustomer-sales');
    
}
    public function custDetails($id)
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $customer = NewCustomer::find($id);

        return view ('custDetails-sales', compact('salesName', 'customer'));
    }

    public function onBoarding()
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $childInfos = ChildInfo::with(['fatherInfo', 'motherInfo'])
        ->whereHas('package', function ($query) {
            $query->where('package_name', 'Screening & Consultation');
        })
        ->get();
        // Get the current date
        $currentDate = now();
    
        // Loop through each ChildInfo and determine the status
        $childInfos->each(function ($childInfo) use ($currentDate) {
            // Check if there are any ChildSchedule records for the current month or any future month
            $hasActiveSchedule = $childInfo->childSchedule()
                ->where('date', '>=', $currentDate->startOfMonth()->toDateString()) // Check for current or future months
                ->exists();
    
            // Set the status based on the condition
            $childInfo->status = $hasActiveSchedule ? 'active' : 'inactive';
        });
    
        
        return view ('onBoarding-sales', compact('salesName', 'childInfos'));
    }

    public function regOnBoarding($id)
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $childInfo = ChildInfo::findOrFail($id);
        $fatherInfo = $childInfo->fatherInfo;
        $motherInfo = $childInfo->motherInfo;
        $packages = Package::where('package_name', 'Full Asessment')->get();

        return view('regOnBoarding-sales', compact('childInfo', 'fatherInfo', 'motherInfo', 'packages', 'salesName'));
    }

public function submitRegOnBoard(Request $request, $id)
{
    // Validate the form data
    $validatedData = $request->validate([
        // Child Info Validation
        'child_name' => 'required|string|max:255',
        'child_nationality' => 'required|string|max:255',
        'child_ic' => 'nullable|string|max:12',
        'child_passport' => 'nullable|string|max:255',
        'child_dob' => 'required|date',
        'child_race' => 'nullable|string|max:255',
        'child_bp' => 'nullable|string|max:255',
        'child_religion' => 'nullable|string|max:255',
        'child_sex' => 'required|string|in:Male,Female',
        'child_address' => 'required|string|max:255',
        'child_posscode' => 'required|numeric|digits:5',
        'child_city' => 'required|string|max:255',
        'child_country' => 'required|string|max:255',
        'house_income' => 'nullable',

        'pediatricians' => 'nullable|string',
        'recommend' => 'nullable|string',
        'deadline' => 'nullable|string',
        'diagnosis' => 'nullable|string',
        'occ_therapy' => 'nullable|string',
        'sp_therapy' => 'nullable|string',
        'others' => 'nullable|string',

        // Father Info Validation
        'father_name' => 'nullable|string|max:255',
        'father_phone' => 'nullable|numeric',
        'father_ic' => 'nullable|string|max:12',
        'father_race' => 'nullable|string|max:255',
        'father_occ' => 'nullable|string|max:255',
        'father_email' => 'nullable|email|max:255',
        'father_address' => 'nullable|string|max:255',
        'father_posscode' => 'nullable|numeric|digits:5',
        'father_city' => 'nullable|string|max:255',
        'father_work_address' => 'nullable|string|max:255',
        'father_work_posscode' => 'nullable|numeric|digits:5',
        'father_work_city' => 'nullable|string|max:255',

        // Mother Info Validation (conditional based on checkbox)
        'mother_name' => 'nullable|string|max:255',
        'mother_phone' => 'nullable|numeric',
        'mother_ic' => 'nullable|string|max:12',
        'mother_race' => 'nullable|string|max:255',
        'mother_occ' => 'nullable|string|max:255',
        'mother_email' => 'nullable|email|max:255',
        'mother_address' => 'nullable|string|max:255',
        'mother_posscode' => 'nullable|numeric|digits:5',
        'mother_city' => 'nullable|string|max:255',
        'mother_work_address' => 'nullable|string|max:255',
        'mother_work_posscode' => 'nullable|numeric|digits:5',
        'mother_work_city' => 'nullable|string|max:255',

        // Program Info Validation
        'package_id' => 'required|exists:packages,id',
    ]);

    // Find the customer by ID
    $childInfo = ChildInfo::findOrFail($id);

    if ($childInfo) {
        $childInfo->update([
            'child_name' => $validatedData['child_name'],
            'child_nationality' => $validatedData['child_nationality'],
            'child_ic' => $validatedData['child_ic'],
            'child_passport' => $validatedData['child_passport'],
            'child_dob' => $validatedData['child_dob'],
            'child_race' => $validatedData['child_race'],
            'child_bp' => $validatedData['child_bp'],
            'child_religion' => $validatedData['child_religion'],
            'child_sex' => $validatedData['child_sex'],
            'child_address' => $validatedData['child_address'],
            'child_posscode' => $validatedData['child_posscode'],
            'child_city' => $validatedData['child_city'],
            'child_country' => $validatedData['child_country'],
            'pediatricians' => $validatedData['pediatricians'],
            'recommend' => $validatedData['recommend'],
            'deadline' => $validatedData['deadline'],
            'diagnosis' => $validatedData['diagnosis'],
            'occ_therapy' => $validatedData['occ_therapy'],
            'sp_therapy' => $validatedData['sp_therapy'],
            'others' => $validatedData['others'],
            'house_income' => $validatedData['house_income'],
        ]);
    }

    // Update Parent Info (Father & Mother)
    if (!$request->has('father')) {
        $childInfo->fatherInfo()->updateOrCreate([], [
            'father_name' => $validatedData['father_name'],
            'father_phone' => $validatedData['father_phone'],
            'father_ic' => $validatedData['father_ic'],
            'father_race' => $validatedData['father_race'],
            'father_occ' => $validatedData['father_occ'],
            'father_email' => $validatedData['father_email'],
            'father_address' => $validatedData['father_address'],
            'father_posscode' => $validatedData['father_posscode'],
            'father_city' => $validatedData['father_city'],
            'father_work_address' => $validatedData['father_work_address'],
            'father_work_posscode' => $validatedData['father_work_posscode'],
            'father_work_city' => $validatedData['father_work_city'],
        ]);
    } else {
        $childInfo->fatherInfo()->delete();
    }

    if (!$request->has('mother')) {
        $childInfo->motherInfo()->updateOrCreate([], [
            'mother_name' => $validatedData['mother_name'],
            'mother_phone' => $validatedData['mother_phone'],
            'mother_ic' => $validatedData['mother_ic'],
            'mother_race' => $validatedData['mother_race'],
            'mother_occ' => $validatedData['mother_occ'],
            'mother_email' => $validatedData['mother_email'],
            'mother_address' => $validatedData['mother_address'],
            'mother_posscode' => $validatedData['mother_posscode'],
            'mother_city' => $validatedData['mother_city'],
            'mother_work_address' => $validatedData['mother_work_address'],
            'mother_work_posscode' => $validatedData['mother_work_posscode'],
            'mother_work_city' => $validatedData['mother_work_city'],
        ]);
    } else {
        $childInfo->motherInfo()->delete();
    }

    // Update Package Info
    $childInfo->package_id = $validatedData['package_id'];
    $childInfo->save();

    // **Create Parent Account Automatically**
    // $parentAccount = ParentAccount::firstOrNew(['child_id' => $childInfo->id]);

    // // **Generate Username & Password**
    // $username = strtolower(Str::slug($validatedData['child_name'])) . random_int(1000, 9999);
    // $temporaryPassword = Str::random(10);
    // $hashedPassword = Hash::make($temporaryPassword);

    // // Store in ParentAccount Model
    // $parentAccount->username = $username;
    // $parentAccount->password = $hashedPassword;
    // $parentAccount->save();

    // // **Send Credentials to Parent's Email**
    // $recipientEmail = $validatedData['father_email'] ?? $validatedData['mother_email'] ?? null;

    // if ($recipientEmail) {
    //     Mail::to($recipientEmail)->send(new ParentCredentials($username, $temporaryPassword));
    // }

    // Redirect with success message
    return redirect()->route('slotBooking-sales', ['child_id' => $childInfo->id])->with('success', 'Customer information updated successfully! Parent account created.');
}

public function slotBookingView($child_id)
{
    $user = Auth::guard('sales')->user();
    if (!$user) {
        return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
    }
    $salesInfo = $user;
    $salesName = $user->name;
    $childInfo = ChildInfo::find($child_id);
    $package = $childInfo->package;

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

    return view('slotBooking-sales', [
        'package' => $package,
        'childInfo' => $childInfo,
        'slots' => $slots,
        'sessionQuantity' => $package->session_quantity,
        'child_id' => $child_id,
        'isWeekly' => $package->weekly === 'yes',
        'salesName' => $salesName
    ]);
}

    public function slotBooking(Request $request, $child_id)
    {
        $validatedData = $request->validate([
            'consult_date' => "required|date",
            'consult_day' => "required|string",
            'consult_time' => "required|string",
        ]);

        $childInfo = ChildInfo::with('fatherInfo', 'motherInfo', 'parentAccount')->find($child_id); // Eager load the related models
        $package = $childInfo->package;
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

        $consultDetails = [
            'consult_date' => $validatedData['consult_date'],
            'consult_day' => $validatedData['consult_day'],
            'consult_time' => $validatedData['consult_time'],
        ];

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
        'sessionCounter' => $sessionCounter,
        'consultDetails' => $consultDetails
    ]);

    return redirect()->route('bookingSummary-sales', ['child_id' => $child_id]);
}

public function bookingSummary($child_id)
{

    $user = Auth::guard('sales')->user();
    if (!$user) {
        return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
    }
    $salesInfo = $user;
    $salesName = $user->name;
    // Retrieve session data
    $type = session('type');
    $package_id = session('package_id');
    $childInfo = session('childInfo');
    $package = session('package');
    $fatherInfo = session('fatherInfo');
    $motherInfo = session('motherInfo');
    $parentAccount = session('parentAccount');
    $totalPrice = session('totalPrice');
    $selectedSlots = session('selectedSlots');
    $consultDetails = session('consultDetails');
    $additionalSessions = session('additionalSessions');
    $additionalPrice = session('additionalPrice');
    $basePrice = session('basePrice');
    $sessionId = session('sessionId');
    $sessionCounter = session('sessionCounter');
    
    return view('bookingSummary-sales', [
        'type' => $type,
        'package' => $package,
        'childInfo' => $childInfo,
        'fatherInfo' => $fatherInfo,
        'motherInfo' => $motherInfo,
        'parentAccount' => $parentAccount,
        'totalPrice' => $totalPrice,
        'selectedSlots' => $selectedSlots,
        'consultDetails' => $consultDetails,
        'additionalSessions' => $additionalSessions,
        'additionalPrice' => $additionalPrice,
        'child_id' => $child_id,
        'basePrice' => $basePrice,
        'sessionId' => $sessionId,
        'salesName' => $salesName,
        'sessionCounter' => $sessionCounter
    ]);
}
public function confirmBookSlot(Request $request, $child_id)
{
    DB::beginTransaction(); // Start a database transaction

    try {
        $type = session('type');
        $package_id = session('package_id');
        $childInfo = session('childInfo');
        $package = session('package');
        $fatherInfo = session('fatherInfo');
        $motherInfo = session('motherInfo');
        $parentAccount = session('parentAccount');
        $totalPrice = session('totalPrice');
        $selectedSlots = session('selectedSlots');
        $consultDetails = session('consultDetails');
        $additionalSessions = session('additionalSessions');
        $additionalPrice = session('additionalPrice');
        $basePrice = session('basePrice');
        $sessionId = session('sessionId');
        $sessionCounter = session('sessionCounter');
        $reference = Str::uuid();

        // Retrieve child info
        $childInfo = ChildInfo::find($child_id);
        if (!$childInfo) {
            throw new \Exception('Child information not found.');
        }

        // Retrieve or create ParentAccount
        $parentAccount = ParentAccount::firstOrNew(['child_id' => $childInfo->id]);

        // Generate Username & Password
        $parent_username = strtolower(Str::slug($childInfo->child_name)) . random_int(1000, 9999);
        $parent_password = Str::random(10);
        $hashedPassword = Hash::make($parent_password);

        // Store in ParentAccount Model
        $parentAccount->username = $parent_username;
        $parentAccount->password = $hashedPassword;
        $parentAccount->save();

        // Send credentials to parent's email
        $recipientEmails = [];
        if ($childInfo->fatherInfo && $childInfo->fatherInfo->father_email) {
            $recipientEmails[] = $childInfo->fatherInfo->father_email;
        }
        if ($childInfo->motherInfo && $childInfo->motherInfo->mother_email) {
            $recipientEmails[] = $childInfo->motherInfo->mother_email;
        }

        try {
            if (!empty($recipientEmails)) {
                Mail::to($recipientEmails)->send(new ParentCredentials($parent_username, $parent_password));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
            // Optionally, you can log the recipient emails as well
            \Log::error('Recipient emails: ', $recipientEmails);
        }
        // Insert selected slots into the ChildSchedule table
        foreach ($selectedSlots as $slotData) {
            $slotDate = Carbon::createFromFormat('m/d/Y', $slotData->date)->format('Y-m-d');
            $slotTime = Carbon::createFromFormat('h:i A', $slotData->start_time)->format('H:i');
            $slotDay = $slotData->day;

            // Check if this schedule already exists
            $exists = ChildSchedule::where('child_id', $child_id)
                ->where('session_id', $sessionId)
                ->where('date', $slotDate)
                ->where('time', $slotTime)
                ->where('type', $type) // Check the type to avoid duplicating regular session
                ->exists();

            if (!$exists) {
                ChildSchedule::create([
                    'child_id' => $child_id,
                    'session_id' => $sessionId,
                    'day' => $slotDay,
                    'date' => $slotDate,
                    'time' => $slotTime,
                    'price' => $totalPrice,
                    'status' => 'pending',
                    'session' => $sessionCounter,
                    'type' => $type,
                ]);
                $sessionCounter++;
            }
        }

        // Insert consultation slot if it does not already exist
        if ($consultDetails) {
            $consultDate = Carbon::parse($consultDetails['consult_date'])->format('Y-m-d');
            $consultTime = Carbon::parse($consultDetails['consult_time'])->format('H:i');
            $consultDay = $consultDetails['consult_day'];

            // Check if this consultation schedule already exists
            $exists = ChildSchedule::where('child_id', $child_id)
                ->where('session_id', $sessionId)
                ->where('date', $consultDate)
                ->where('time', $consultTime)
                ->where('type', 'screening') // Check the type to avoid duplicating consultation session
                ->exists();

            if (!$exists) {
                ChildSchedule::create([
                    'child_id' => $child_id,
                    'session_id' => $sessionId,
                    'day' => $consultDay,
                    'date' => $consultDate,
                    'time' => $consultTime,
                    'price' => $totalPrice,
                    'status' => 'pending',
                    'session' => $sessionCounter,
                    'type' => 'screening',
                ]);
            }
        }
        DB::commit(); // Commit the transaction

        // Return success response
    return redirect()->route('registeredCustomer-sales')->with('success', 'Customer Account Created and Invoice has been sent');

    } catch (Exception $e) {
        DB::rollBack(); // Roll back the transaction on error

        // Return error response
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while confirming the booking. Please try again.',
            'error' => $e->getMessage(),
        ], 500);
    }

}

    public function registeredCustomer()
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $childInfos = ChildInfo::with(['fatherInfo', 'motherInfo'])->get();
        // Get the current date
        $currentDate = now();
    
        // Loop through each ChildInfo and determine the status
        $childInfos->each(function ($childInfo) use ($currentDate) {
            // Check if there are any ChildSchedule records for the current month or any future month
            $hasActiveSchedule = $childInfo->childSchedule()
                ->where('date', '>=', $currentDate->startOfMonth()->toDateString()) // Check for current or future months
                ->exists();
    
            // Set the status based on the condition
            $childInfo->status = $hasActiveSchedule ? 'active' : 'inactive';
        });
    
        
        return view ('registeredCustomer-sales', compact('salesName', 'childInfos'));
    }
    public function custDetails2($id)
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $childInfo = ChildInfo::with(['fatherInfo', 'motherInfo'])->findOrFail($id);
        $childInfo->age = Carbon::parse($childInfo->child_dob)->age;

        return view ('custDetails2-sales', compact('salesName', 'childInfo'));
    }

    public function consultationSessions()
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
    
        $schedules = ChildSchedule::where('type', 'screening')->get();
    
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
    
        return view('consultationSessions-sales', compact('events', 'salesName'));
    }

    public function allSession()
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
    
        $schedules = ChildSchedule::where('type', '!=', 'screening')->get();
    
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
    
        return view('allSession-sales', compact('events', 'salesName'));
    }

    public function paymentStatus()
    {
        $user = Auth::guard('sales')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $salesInfo = $user;
        $salesName = $user->name;
        $payment = Payment::all();
        
        return view ('paymentStatus-sales', compact('salesName', 'payment'));
    }
}
