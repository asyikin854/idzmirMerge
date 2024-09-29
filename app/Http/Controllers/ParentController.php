<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Package;
use App\Models\SlotRTS;
use Illuminate\Http\Request;
use App\Models\ChildSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ParentController extends Controller
{
    public function parentDashboard()
    {
        $user = Auth::guard('parent')->user();
        $childInfo = $user->childInfo;

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
            $sessionLeft = ChildSchedule::where('child_id', $childInfo->id)
            ->whereNull('attendance')
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
                ->with('reportCount', $reportCount);
        }

         return Redirect::route('login')->with('error', 'The session has expired. Please return to the login page to log back into your account.');
    }
    private function getRelatedData()
    {
        $user = Auth::guard('parent')->user();
        $childInfo = $user->childInfo;

        if (!$childInfo) {
            // Redirect to the login page with a flash message
            return Redirect::route('login')->with('error', 'The session has expired. Please return to the login page to log back into your account.');
        }

        $fatherInfo = $childInfo->fatherInfo;
        $motherInfo = $childInfo->motherInfo;
        $parentPermission = $childInfo->parentPermission;
        $parentAccount = $childInfo->parentAccount;
        $payment = $childInfo->payment;
        $childSchedule = $childInfo->childSchedule;
        $childSchedules = $childInfo->childSchedule->first();
        $package = $childInfo->package;

        // Load the related SessionReport data for the child's schedule

        return [
            'childInfo' => $childInfo,
            'fatherInfo' => $fatherInfo,
            'motherInfo' => $motherInfo,
            'parentPermission' => $parentPermission,
            'parentAccount' => $parentAccount,
            'payment' => $payment,
            'childSchedule' => $childSchedule,
            'childSchedules' => $childSchedules,
            'package' => $package,
        ];
    }
    public function parentScheduleView()
    {
        $data = $this->getRelatedData();
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
        {
            $data = $this->getRelatedData();
            if ($data) {
                return view('profile-parent')->with($data);
            }
            return view('profile-parent')->with('error', 'No related data found');
        }

    }
    public function rescheduleView($id)
    {
        $user = Auth::guard('parent')->user();
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
                    'quota' => 10 - $bookedSessions, // Remaining quota
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
            if ($data) {
                return view('announcement-parent')->with($data);
            }
            return view('announcement-parent')->with('error', 'No related data found');
    }

    public function paymentList()
    {
        {
            $data = $this->getRelatedData();
            if ($data) {
                return view('paymentList-parent')->with($data);
            }
            return view('paymentList-parent')->with('error', 'No related data found');
        
    }}
}
