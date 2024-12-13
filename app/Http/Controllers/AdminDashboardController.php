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

    public function dashboard()
    {
        $totalStudent = ChildInfo::all()->count();
        $payment = Payment::all();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlySales = Payment::where('status', 'paid')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount');

        $totalSales = Payment::where('status', 'paid')->sum('total_amount');

        $previousMonth = now()->subMonth();
        $previousMonthSales = Payment::where('status', 'paid')
            ->whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->sum('total_amount');

        // Calculate percentage change
        if ($previousMonthSales > 0) {
            $salesPercentage = (($monthlySales - $previousMonthSales) / $previousMonthSales) * 100;
        } else {
            $salesPercentage = $monthlySales > 0 ? 100 : 0; // 100% increase if no sales in the previous month
        }

        return view ('admin.dashboard', compact('totalSales', 'monthlySales', 'totalStudent', 'salesPercentage'));
    }

    public function getMonthlyData()
    {
        $salesData = [];
        $studentData = [];
        $months = [];
        $allTimeMonths = [];
        $allTimeSalesData = [];
    
        // Fetch the last 6 months data for monthly sales
        $currentMonth = Carbon::now();
        $startMonth = Carbon::now()->subMonths(1); // Start from 5 months ago to include current month
    
        for ($i = 0; $i < 2; $i++) {
            $currentMonthLabel = $startMonth->format('F Y'); // Label for the current month
    
            // Fetch total sales for the month
            $sales = Payment::where('status', 'paid')
                ->whereMonth('created_at', $startMonth->month)
                ->whereYear('created_at', $startMonth->year)
                ->sum('total_amount');
    
            // Append data
            $salesData[] = $sales;
            $months[] = $currentMonthLabel;
    
            // Move to the next month
            $startMonth->addMonthNoOverflow(); // Avoid potential overflow issues
        }
    
        // Fetch all-time sales data grouped by month and year
        $allTimeData = Payment::where('status', 'paid')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as total_sales')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    
        foreach ($allTimeData as $data) {
            // Format month and year
            $monthName = Carbon::create($data->year, $data->month)->format('F Y');
            $allTimeMonths[] = $monthName;
    
            // Sales data
            $allTimeSalesData[] = $data->total_sales;
    
            // Student data for the same month and year
            $studentCount = ChildInfo::whereYear('created_at', $data->year)
                ->whereMonth('created_at', $data->month)
                ->count();
            $studentData[] = $studentCount;
        }
    
        return response()->json([
            'salesData' => $salesData,
            'months' => $months,
            'allTimeSalesData' => $allTimeSalesData,
            'studentData' => $studentData,
            'allTimeMonths' => $allTimeMonths,
        ]);
    }
    

    public function getProgramData()
{
    // Group ChildInfo by package_id and count children for each package
    $data = ChildInfo::join('packages', 'child_infos.package_id', '=', 'packages.id')
        ->select('packages.package_name', DB::raw('COUNT(child_infos.id) as child_count'))
        ->groupBy('packages.package_name')
        ->orderBy('packages.package_name', 'asc')
        ->get();

    // Format response
    $packageNames = $data->pluck('package_name');
    $childCounts = $data->pluck('child_count');

    return response()->json([
        'categories' => $packageNames,
        'seriesData' => $childCounts,
    ]);
}

    



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
