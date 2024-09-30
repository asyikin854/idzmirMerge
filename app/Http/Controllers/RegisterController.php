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
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ChildSchedule;
use App\Models\ParentAccount;
use App\Models\ParentsPermission;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function registerView()
    {
        return view('register-parent');
    }

    public function registerNew(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            // Add validation rules for each input field
            'child_name' => 'required|string',
            'child_ic' => 'required|string',
            'child_dob' => 'required|date',
            'child_passport' => 'nullable|string',
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
        $childInfo = ChildInfo::create($validatedData);

        // Create related FatherInfo, MotherInfo, ParentPermission, and ParentAccount records
        FatherInfo::create(array_merge($validatedData, ['child_id' => $childInfo->id]));
        MotherInfo::create(array_merge($validatedData, ['child_id' => $childInfo->id]));
        ParentsPermission::create(array_merge($validatedData, ['child_id' => $childInfo->id]));

        ParentAccount::create([
            'child_id' => $childInfo->id,
            'password' => bcrypt($validatedData['password']),
            'email' => $validatedData['email'],
            'username' => $validatedData['username'],
        ]);

        return view('register2-parent', compact('childInfo'));
    }

    public function register2($id)
    {
        $childInfo = ChildInfo::with('motherInfo', 'fatherInfo', 'parentPermission')->find($id);
        if (!$childInfo) {
            abort(404);
        }

        return view('register2-parent', compact('childInfo'));
    }

    public function packageView(Request $request, $child_id)
    {
        $childInfo = ChildInfo::find($child_id);
        $packages = $childInfo->child_nationality === 'Malaysian' ?
            Package::where('citizenship', 'yes')->orderBy('package_name', 'asc')->get() :
            Package::where('citizenship', 'no')->orderBy('package_name', 'asc')->get();

        return view('product-parent', compact('packages', 'child_id'));
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
        $childInfo = ChildInfo::find($child_id);
        $package = Package::find($package_id);

        if (!$childInfo || !$package) {
            abort(404);
        }

        $packageType = $package->type;
        $isWeekly = $package->is_weekly === 'yes';

        $slotsModel = $packageType === 'individual' ? new Slot() : new SlotRTS();

        // Fetch slots for current month
        $slots = $slotsModel::where('date', '>=', now()->addDay())
            ->where('date', '<=', now()->endOfMonth())
            ->get();

        if ($isWeekly) {
            // Group slots by week
            $slots = $slots->groupBy(function ($slot) {
                return Carbon::parse($slot->date)->format('W');
            });
        }

        return view('childSchedule', compact('package', 'childInfo', 'slots'));
    }

    public function childSchedule(Request $request, $child_id, $package_id)
{
    $childInfo = ChildInfo::with('fatherInfo', 'motherInfo', 'parentAccount')->find($child_id); // Eager load the related models
    $package = Package::find($package_id);

    if (!$childInfo || !$package) {
        abort(404);
    }

    $selectedSlots = json_decode($request->input('selected_slots'));
    $additionalSessions = $request->input('additional_sessions', 0); // Optional additional sessions
    $totalSessions = $package->session_quantity + $additionalSessions;
    $basePrice = 0; // To track base price calculation
    $additionalPrice = $additionalSessions * 100; // Calculate additional session price (RM 100 per session)

    if (!$selectedSlots || !is_array($selectedSlots)) {
        return back()->withErrors(['error' => 'No valid slots selected. Please select at least one slot.']);
    }

    // Calculate base price
    foreach ($selectedSlots as $slotData) {
        $slotDay = $slotData->day;
        $basePrice = in_array($slotDay, ['Friday', 'Saturday']) ? $package->package_wkend_price : $package->package_wkday_price;
    }

    $sessionId = (string) Str::uuid(); // Generate a unique session ID

    session([
        'childInfo' => $childInfo,
        'package' => $package,
        'fatherInfo' => $childInfo->fatherInfo,
        'motherInfo' => $childInfo->motherInfo,
        'parentAccount' => $childInfo->parentAccount,
        'totalPrice' => $basePrice + $additionalPrice,
        'selectedSlots' => $selectedSlots,
        'additionalSessions' => $additionalSessions,
        'additionalPrice' => $additionalPrice,
        'child_id' => $child_id,
        'basePrice' => $basePrice,
        'sessionId' => $sessionId
    ]);

    return redirect()->route('checkout-parent', ['child_id' => $child_id, 'package_id' => $package_id]);
}

public function checkoutParent($child_id, $package_id)
{
    if (!session()->has('childInfo') || !session()->has('package')) {
        return redirect()->back()->withErrors('Session data not found.');
    }

    // Retrieve data from the session
    $childInfo = session('childInfo');
    $package = session('package');
    $fatherInfo = session('fatherInfo');
    $motherInfo = session('motherInfo');
    $parentAccount = session('parentAccount');
    $totalPrice = session('totalPrice');
    $selectedSlots = session('selectedSlots');
    $additionalSessions = session('additionalSessions');
    $additionalPrice = session('additionalPrice');
    $basePrice = session('basePrice');
    $sessionId = session('sessionId');

    return view('checkout-parent');
}

public function submitPayment(Request $request)
{
    $child_id = $request->input('child_id');
    $totalPrice = $request->input('total_price');
    $parent_id = $request->input('parent_id');
    $session_id = $request->input('session_id');
    $selectedSlots = session('selectedSlots'); // Retrieve slots from session
    $reference = Str::uuid();

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
        ]);
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

    // Payment request data for Chip
    $paymentData = [
        'amount' => $totalPrice * 100, // Amount in cents
        'currency' => 'MYR',
        'email' => 'testuser@example.com', // Replace with the parent's email
        'description' => 'Payment for Child ID: ' . $child_id,
        'redirect_url' => route('chip.callback'),
        'reference' => $reference,
        'payment_method' => 'FPX',
    ];
    Log::info('Chip Payment Data Prepared', $paymentData);
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.chip.api_key'),
        ])->post(config('services.chip.endpoint') . 'payments', $paymentData);

        if ($response->successful() && isset($response['payment_url'])) {
            Log::info('Payment URL received: ', $response->json());
            return redirect($response['payment_url']); // Redirect to Chip payment page
        } else {
            // Log the failure and update Payment status to 'failed'
            Log::error('Payment URL not received or API failed: ', $response->json());
            $payment->update(['status' => 'failed']); // Update the payment status to 'failed'
            return redirect()->route('payment.failure')->withErrors(['error' => 'Payment failed.']);
        }
    } catch (Exception $e) {
        // Handle exception, log error, and update Payment status to 'failed'
        Log::error('Chip API Error: ' . $e->getMessage());
        $payment->update(['status' => 'failed']); // Update the payment status to 'failed'
        return redirect()->route('payment.failure')->withErrors(['error' => 'Payment processing error.']);
    }
}




public function handleCallback(Request $request)
{
    $reference = $request->input('reference');
    $status = $request->input('status');

    Log::info('Chip Callback Data: ', $request->all());

    $payment = Payment::where('reference', $reference)->first();

    if ($payment) {
        $payment->update([
            'status' => $status,
            'payment_date' => now()
        ]);

        // Update ChildSchedule records based on session_id
        if ($status == 'successful') {
            ChildSchedule::where('session_id', $payment->session_id)->update(['status' => 'confirmed']);
            return redirect()->route('payment.success');
        } else {
            ChildSchedule::where('session_id', $payment->session_id)->update(['status' => 'failed']);
            return redirect()->route('payment.failure');
        }
    }

    return redirect()->route('payment.failure')->withErrors(['error' => 'Payment not found']);
}


    public function paymentSuccess()
    {
        return view('payment.success');
    }

    public function paymentFailure()
    {
        return view('payment.failure');
    }
}
