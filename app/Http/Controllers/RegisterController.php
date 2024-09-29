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
        $childInfo = ChildInfo::with('fatherInfo', 'motherInfo', 'parentAccount')->find($child_id);
        $package = Package::find($package_id);

        if (!$childInfo || !$package) {
            abort(404);
        }

        $selectedSlots = json_decode($request->input('selected_slots'));
        $additionalSessions = $request->input('additional_sessions', 0);

        if (!$selectedSlots || !is_array($selectedSlots)) {
            return back()->withErrors(['error' => 'No valid slots selected. Please select at least one slot.']);
        }

        // Calculate total price
        $basePrice = $package->package_wkday_price; // Adjust according to your logic for weekends
        $additionalPrice = $additionalSessions * 100; // Example: RM 100 per additional session

        return view('checkout-parent', compact('package', 'childInfo', 'basePrice', 'additionalPrice', 'selectedSlots'));
    }

    public function checkoutParent()
    {
        return view('checkout-parent');
    }

    public function submitPayment(Request $request)
    {
        $child_id = $request->input('child_id');
        $totalPrice = $request->input('total_price');
        $parent_id = $request->input('parent_id');
        $session_id = $request->input('session_id');
        $reference = Str::uuid();

        // Create a new payment record
        $payment = Payment::create([
            'child_id' => $child_id,
            'parent_id' => $parent_id,
            'reference' => $reference,
            'total_amount' => $totalPrice,
            'payment_method' => 'FPX',
            'status' => 'pending',
            'session_id' => $session_id
        ]);

        // Payment request data for Chip
        $paymentData = [
            'amount' => $totalPrice * 100, // Amount in cents
            'currency' => 'MYR',
            'email' => 'testuser@example.com', // Replace with the parent's email
            'description' => 'Payment for Child ID: ' . $child_id,
            'redirect_url' => config('services.chip.baseUrl') . '/chip/callback',
            'reference' => $reference,
            'payment_method' => 'FPX',
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.chip.api_key'),
            ])->post(config('services.chip.endpoint') . 'payments', $paymentData);

            if ($response->successful() && isset($response['payment_url'])) {
                Log::info('Payment initiated successfully: ', $response->json());
                return redirect($response['payment_url']);
            } else {
                $payment->update(['status' => 'failed']);
                return back()->withErrors(['error' => 'Payment failed.']);
            }
        } catch (Exception $e) {
            Log::error('Chip API Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Payment processing error.']);
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

            if ($status == 'successful') {
                return redirect()->route('payment.success');
            } else {
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
