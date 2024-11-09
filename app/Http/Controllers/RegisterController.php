<?php

namespace App\Http\Controllers;

use Log;
use Exception;
use Chip\ChipApi;
use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Package;
use App\Models\Payment;
use App\Models\SlotRTS;
use Chip\Model\Product;
use Chip\Model\Purchase;
use App\Models\ChildInfo;
use App\Models\FatherInfo;
use App\Models\MotherInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ChildSchedule;
use App\Models\ParentAccount;
use Chip\Model\ClientDetails;
use Chip\Model\PurchaseDetails;
use App\Models\ParentsPermission;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function registerView()
    {
        return view('register-parent');
    }
    public function checkChildExists(Request $request)
    {
        $ic = $request->input('ic');
        $passport = $request->input('passport');
        
        // Initialize the query to the ChildInfo model
        $query = ChildInfo::query();
    
        // Add condition to check IC if provided
        if (!empty($ic)) {
            $query->where('child_ic', $ic);
        }
    
        // Add condition to check passport if provided
        if (!empty($passport)) {
            $query->orWhere('child_passport', $passport);
        }
    
        // Execute the query and check if any record exists
        $exists = $query->exists();
        
        // Return JSON response with the result
        return response()->json(['exists' => $exists]);
    }
    

    public function registerNew(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            // Add validation rules for each input field
            'child_name' => 'required|string',
            'child_ic' => 'nullable|string',
            'child_dob' => 'required|date',
            'child_passport' => 'nullable|string', // Assuming it's optional
            'child_nationality' => 'required|string',
            'child_race' => 'required|string',
            'child_bp' => 'required|string',
            'child_religion' => 'required|string',
            'child_sex' => 'required|string',
            'child_address' => 'required|string',
            'child_posscode' => 'required|string',
            'child_city' => 'required|string',
            'child_country' => 'required|string',
            'pediatricians' => 'nullable|string',
            'recommend' => 'nullable|string',
            'deadline' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'occ_therapy' => 'nullable|string',
            'sp_therapy' => 'nullable|string',
            'others' => 'nullable|string',
            'father_name' => 'required|string',
            'father_phone' => 'required|string',
            'father_ic' => 'required|string|max:12',
            'father_race' => 'required|string',
            'father_occ' => 'required|string',
            'father_email' => 'required|email',
            'father_address' => 'required|string',
            'father_posscode' => 'required|string',
            'father_city' => 'required|string',
            'father_work_address' => 'required|string',
            'father_work_posscode' => 'required|string',
            'father_work_city' => 'required|string',
            'mother_name' => 'required|string',
            'mother_phone' => 'required|string',
            'mother_ic' => 'required|string|max:12',
            'mother_race' => 'required|string',
            'mother_occ' => 'required|string',
            'mother_email' => 'required|email',
            'mother_address' => 'required|string',
            'mother_posscode' => 'required|string',
            'mother_city' => 'required|string',
            'mother_work_address' => 'required|string',
            'mother_work_posscode' => 'required|string',
            'mother_work_city' => 'required|string',
            'house_income' => 'required|string',
            'parent_sign' => 'required|string',
            'sign_date' => 'required',
            'sign_name' => 'required|string',
            'sign_time' => 'required|string',
            'agree_disagree' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',

        ]);
        // Create a new ChildInfo record
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
            'pediatricians' => $validatedData['pediatricians'],
            'recommend' => $validatedData['recommend'],
            'deadline' => $validatedData['deadline'],
            'diagnosis' => $validatedData['diagnosis'],
            'occ_therapy' => $validatedData['occ_therapy'],
            'sp_therapy' => $validatedData['sp_therapy'],
            'others' => $validatedData['others'],
            'house_income' => $validatedData['house_income'],
        ]);
       
        // Create a new FatherInfo record linked to ChildInfo
        $fatherInfo = FatherInfo::create([
             // Set child_id with the ID of the associated ChildInfo
        // Assign fatherInfo fields
        'child_id' => $childInfo->id, // Link to ChildInfo
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

        // Create a new MotherInfo record linked to ChildInfo
        $motherInfo = MotherInfo::create([
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
            // Add other fields from MotherInfo table
        ]);

        // Create a new ParentPermission record linked to ChildInfo
        $parentPermission = ParentsPermission::create([
            'child_id' => $childInfo->id,
            'parent_sign' => $validatedData['parent_sign'],
            'sign_date' => $validatedData['sign_date'],
            'sign_name' => $validatedData['sign_name'],
            'sign_time' => $validatedData['sign_time'],
            'agree_disagree' => $validatedData['agree_disagree'],
        ]);
        
        $parentAccount = ParentAccount::create([
            'child_id' => $childInfo->id,
            'password' => bcrypt($validatedData['password']),
            'email' => $validatedData['email'],
            'username' => $validatedData['username'],
        ]);
        // Optionally, you can add additional logic or redirection here

        // Redirect the user after successful form submission
        return view('register2-parent', ['childInfo' => $childInfo, 'motherInfo' => $childInfo->motherInfo, 
        'fatherInfo' => $childInfo->fatherInfo, 'parentPermission' => $childInfo->parentPermission]);
    }
    public function register2($id)
    {
        $childInfo = ChildInfo::with('motherInfo','fatherInfo', 'parentPermission')->find($id);

        if (!$childInfo) {
            // Handle case where pemohon does not exist
            abort(404); // Or you can return a view indicating that the pemohon was not found
        }

        // Pass the pemohon and motherInfo details to the view
        return view('register2-parent', ['childInfo' => $childInfo, 'motherInfo' => $childInfo->motherInfo, 
        'fatherInfo' => $childInfo->fatherInfo, 'parentPermission' => $childInfo->parentPermission]);
    }

    public function packageView(Request $request, $child_id)
    {
        $childInfo = ChildInfo::find($child_id);
        $packages = $childInfo->child_nationality === 'Malaysian' ?
            Package::where('citizenship', 'yes')->where('consultation', 'Yes')->orderBy('package_step', 'asc')->get() :
            Package::where('citizenship', 'no')->where('consultation', 'Yes')->orderBy('package_step', 'asc')->get();

        return view('product-parent', compact('packages', 'childInfo'));
    }

    public function packageProceed(Request $request, $child_id, $package_id)
    {
        $childInfo = ChildInfo::find($child_id);
        if (!$childInfo) {
            abort(404);
        }

        $childInfo->package_id = $request->input('package_id');
        $childInfo->save();

        return redirect()->route('childSchedule.view', compact('child_id', 'package_id'));
    }

    public function childScheduleView($child_id, $package_id)
    {
        // Find child and package information
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
    
        return view('childSchedule', [
            'package' => $package,
            'childInfo' => $childInfo,
            'slots' => $slots,
            'sessionQuantity' => $package->session_quantity,
            'child_id' => $child_id,
            'isWeekly' => $package->weekly === 'yes'
        ]);
    }
    
        public function childSchedule(Request $request, $child_id, $package_id)
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
            return redirect()->route('consultSchedule-parent', ['child_id' => $child_id, 'package_id' => $package_id]);
        } else {
            return redirect()->route('checkout-parent', ['child_id' => $child_id, 'package_id' => $package_id]);
        }
    }

    public function consultScheduleView($child_id, $package_id)
        {
            // Find child and package information
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
        
            return view('consultSchedule-parent', [
                'package' => $package,
                'childInfo' => $childInfo,
                'slots' => $slots,
                'sessionQuantity' => 1,
                'child_id' => $child_id,
                'isWeekly' => $package->weekly === 'yes'
            ]);
        }

        public function consultSchedule(Request $request, $child_id, $package_id)
        {
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

            return redirect()->route('checkout-parent', ['child_id' => $child_id, 'package_id' => $package_id]);

    }


public function checkoutParent($child_id, $package_id)
{
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

    return view('checkout-parent', [
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
    ]);
}

public function submitPayment(Request $request)
{
    $package_id = $request->input('package_id');
    $child_id = $request->input('child_id');
    $totalPrice = $request->input('total_price');
    $parent_id = $request->input('parent_id');
    $session_id = $request->input('session_id');
    $selectedSlots = session('selectedSlots'); // Retrieve slots from session
    $sessionCounter = session('sessionCounter'); // Retrieve slots from session
    $reference = Str::uuid();
    $parentAccount = ParentAccount::where('child_id', $child_id)->first();
    
    // Insert selected slots into the ChildSchedule table
    foreach ($selectedSlots as $slotData) {
        $slotDate = Carbon::createFromFormat('m/d/Y', $slotData->date)->format('Y-m-d'); // Format the date correctly
        $slotTime = Carbon::createFromFormat('h:i A', $slotData->start_time)->format('H:i'); // Convert to 'HH:MM' format
        $slotDay = $slotData->day;
        // Insert each selected slot into ChildSchedule
        ChildSchedule::create([
            'child_id' => $child_id,
            'session_id' => $session_id,
            'day' => $slotDay,
            'date' => $slotDate,
            'time' => $slotTime, // Store only the start time
            'price' => $totalPrice, // Store the total price
            'status' => 'pending', // Status for the schedule, not related to payment
            'session' => $sessionCounter // Store session counter
        ]);
        $sessionCounter++;
    }

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

    // Payment request data for Chip
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
    // Send the request and decode response

}

// public function handleCallback(Request $request)
// {
//     $reference = $request->input('reference');
//     $status = $request->input('status');

//     Log::info('Chip Callback Data: ', $request->all());

//     $payment = Payment::where('reference', $reference)->first();

//     if ($payment) {
//         $child_id = $payment->child_id;
//         $package_id = $payment->package_id;
//         $payment->update([
//             'status' => $status,
//             'payment_date' => now()
//         ]);

//         if ($status == 'successful') {
//             ChildSchedule::where('session_id', $payment->session_id)->update(['status' => 'confirmed']);
//             return redirect()->route('payment.success');
//         } else {
//             ChildSchedule::where('session_id', $payment->session_id)->update(['status' => 'failed']);
//             return redirect()->route('payment.failure', ['child_id' => $child_id, 'package_id' => $package_id]);
//         }
//     }

//     return redirect()->route('payment.failure', ['child_id' => $child_id, 'package_id' => $package_id])->withErrors(['error' => 'Payment not found']);
// }


    public function paymentSuccess()
    {
        return view('payment-success');
    }

    public function paymentFailure($child_id, $package_id)
    {
        return view('payment-failure', ['child_id' => $child_id, 'package_id' => $package_id]);
    }
}
