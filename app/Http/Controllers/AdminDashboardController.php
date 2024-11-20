<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\CsInfo;
use App\Models\Payment;
use App\Models\ChildInfo;
use App\Models\FatherInfo;
use App\Models\MotherInfo;
use Illuminate\Http\Request;
use App\Models\ChildSchedule;
use App\Models\ParentAccount;
use App\Models\SalesLeadInfo;
use App\Models\TherapistInfo;


class AdminDashboardController extends Controller
{
    // List of Students (from child_infos table)
    public function listStudents()
    {
        // Fetch students from the child_infos table
        $students = DB::table('child_infos')->get();
        return view('admin.students.index', compact('students'));
    }

    // Dashboard method (fetch parents and display in dashboard view)
    public function listParents()
    {
        // Fetch parents from the parent_accounts table
        $childInfos = ChildInfo::all(); // Eager load the related model

        // Pass the parents data to the view
        return view('admin.parents.index', compact('childInfos'));
    }

    // List of Parents (from parent_accounts table



    // Show specific parent details
    public function showParent($id)
{
    // Fetch the parent data from the parent_accounts table
    $childInfo = ChildInfo::find($id); // Eager load the related models
    $motherInfo = $childInfo->motherInfo;
    $fatherInfo = $childInfo->fatherInfo;
    $parentAccount = $childInfo->parentAccount;

    // Pass both parent and children data to the view
    return view('admin.parents.show', compact('childInfo', 'motherInfo', 'fatherInfo', 'parentAccount'));
}

public function updateMotherInfo(Request $request, $id)
{
    $motherInfo = MotherInfo::findOrFail($id);
    $motherInfo->update($request->all());
    return redirect()->back()->with('success', 'Mother Information Updated Successfully');
}

public function updateFatherInfo(Request $request, $id)
{
    $fatherInfo = FatherInfo::findOrFail($id);
    $fatherInfo->update($request->all());
    return redirect()->back()->with('success', 'Father Information Updated Successfully');
}

public function updateChildInfo(Request $request, $id)
{
    $childInfo = ChildInfo::findOrFail($id);
    $childInfo->update($request->all());
    return redirect()->back()->with('success', 'Child Information Updated Successfully');
}
public function updateParentAccount(Request $request, $id)
{
    $validated = $request->validate([
        'username' => 'required|string',
        'email' => 'required|email',
        'password' => 'nullable|string',
    ]);

    $parentAccount = ParentAccount::findOrFail($id);
    $parentAccount->username = $request->username;
    $parentAccount->email = $request->email;
    
    if ($request->password) {
        $parentAccount->password = bcrypt($request->password);
    }

    $parentAccount->save();
    return redirect()->back()->with('success', 'Parent Account Updated Successfully');
}


// Method to show the schedule
public function showSchedules()
{
    // Fetch the schedule data from the child_schedules table and join it with child_infos to get child_name
    $schedules = DB::table('child_schedules')
        ->join('child_infos', 'child_schedules.child_id', '=', 'child_infos.id')
        ->select(
            'child_schedules.*', 
            'child_infos.child_name'
        )
        ->paginate(10);  // Keep paginate without get()

    // Pass the data to the view
    return view('admin.schedules.index', compact('schedules'));
}


public function edit($id)
{
    $schedule = DB::table('child_schedules')
        ->join('child_infos', 'child_schedules.child_id', '=', 'child_infos.id')
        ->where('child_schedules.id', $id)
        ->select('child_schedules.*', 'child_infos.child_name')
        ->first();

    return view('admin.schedules.edit', compact('schedule'));
}


public function update(Request $request, $id)
{
    // Validate the incoming data
    $validated = $request->validate([
        'session' => 'required',
        'therapist' => 'required',
        'date' => 'required|date',
        'time' => 'required',
        'price' => 'required|numeric',
        'status' => 'required',
        'remark' => 'nullable'
    ]);

    // Find the schedule and update it
    DB::table('child_schedules')->where('id', $id)->update([
        'session' => $validated['session'],
        'therapist' => $validated['therapist'],
        'date' => $validated['date'],
        'time' => $validated['time'],
        'price' => $validated['price'],
        'status' => $validated['status'],
        'remark' => $validated['remark']
    ]);

    // Redirect back to the schedules list
    return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
}

public function listCs()
{
    $csList = CsInfo::all();
    return view ('admin.cs.csList', compact('csList'));
}

public function addNewCs(Request $request)
{
    $validatedData = $request->validate([
        'username' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string',
        'name' => 'required|string', // Assuming it's optional
    ]);

    $addCs = CsInfo::create([
        'username' => $validatedData['username'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        'name' => $validatedData['name'],
    ]);
    return redirect()->route('admin.cs.list')->with('success', 'New Customer Service successfully added.');
}
public function updateCs(Request $request)
{
    $validated = $request->validate([
        'cs_id' => 'required|exists:cs_infos,id',
        'name' => 'required|string',
        'username' => 'required|string',
        'email' => 'nullable|email',
        'password' => 'nullable|string',
    ]);

    $cs = CsInfo::findOrFail($request->cs_id);
    $cs->name = $request->name;
    $cs->username = $request->username;
    $cs->email = $request->email;
    
    if ($request->password) {
        $cs->password = bcrypt($request->password);
    }

    $cs->save();

    return redirect()->route('admin.cs.list')->with('success', 'Customer Service details updated successfully.');
}


public function listSales()
{
    $sales = SalesLeadInfo::all();
    return view ('admin.sales.salesList', compact('sales'));
}

public function addNewSales(Request $request)
{
    $validatedData = $request->validate([
        'username' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string',
        'name' => 'required|string', // Assuming it's optional
    ]);

    $addSales = SalesLeadInfo::create([
        'username' => $validatedData['username'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        'name' => $validatedData['name'],
    ]);
    return redirect()->route('admin.sales.list')->with('success', 'New Sales Lead successfully added.');
}
public function updateSales(Request $request)
{
    $validated = $request->validate([
        'sales_id' => 'required|exists:sales_lead_infos,id',
        'name' => 'required|string',
        'username' => 'required|string',
        'email' => 'nullable|email',
        'password' => 'nullable|string',
    ]);

    $sales = SalesLeadInfo::findOrFail($request->sales_id);
    $sales->name = $request->name;
    $sales->username = $request->username;
    $sales->email = $request->email;
    
    if ($request->password) {
        $sales->password = bcrypt($request->password);
    }

    $sales->save();

    return redirect()->route('admin.sales.list')->with('success', 'Sales Lead details updated successfully.');
}

public function listTherapist()
{
    $therapists = TherapistInfo::all();
    return view('admin.therapist.therapistList', compact('therapists'));
}
public function addNewTherapist(Request $request)
{
    $validatedData = $request->validate([
        'username' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string',
        'name' => 'required|string', // Assuming it's optional
    ]);

    $addSales = TherapistInfo::create([
        'username' => $validatedData['username'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        'name' => $validatedData['name'],
    ]);
    return redirect()->route('admin.therapist.list')->with('success', 'New Therapist successfully added.');
}
public function updateTherapist(Request $request)
{
    $validated = $request->validate([
        'therapist_id' => 'required|exists:therapist_infos,id',
        'name' => 'required|string',
        'username' => 'required|string',
        'email' => 'nullable|email',
        'password' => 'nullable|string',
    ]);

    $therapist = TherapistInfo::findOrFail($request->therapist_id);
    $therapist->name = $request->name;
    $therapist->username = $request->username;
    $therapist->email = $request->email;
    
    if ($request->password) {
        $therapist->password = bcrypt($request->password);
    }

    $therapist->save();

    return redirect()->route('admin.therapist.list')->with('success', 'Therapist updated successfully.');
}

public function paymentList()
{
    $payments = Payment::select('*')
    ->whereIn('id', function ($query) {
        $query->selectRaw('MAX(id)')
              ->from('payments')
              ->groupBy('child_id');
    })
    ->with('childInfo') // Eager load the related childInfo
    ->latest() // Order by latest payments
    ->get();
    return view ('admin.payment.paymentList', compact('payments'));
}
public function paymentDetails($id)
{
    $childInfo = ChildInfo::findOrFail($id);
    $payments = $childInfo->payment;

    return view('admin.payment.paymentDetails', compact('payments', 'childInfo'));
}
public function getChildSchedulesBySessionId(Request $request)
{
    $sessionId = $request->query('session_id');

    if (!$sessionId) {
        return response()->json([
            'success' => false,
            'message' => 'Session ID is required.',
        ]);
    }

    $childSchedules = ChildSchedule::where('session_id', $sessionId)->get();

    return response()->json([
        'success' => true,
        'childSchedules' => $childSchedules,
    ]);
}

}
