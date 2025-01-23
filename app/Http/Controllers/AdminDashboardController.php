<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\CsInfo;
use App\Models\Package;
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
        $salesPercentage = number_format($salesPercentage, 2);

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


    public function destroyParent($id)
    {
        // Find the child info by ID
        $childInfo = ChildInfo::findOrFail($id);
    
        // Delete related data
        $childInfo->fatherInfo()->delete();
        $childInfo->motherInfo()->delete();
        $childInfo->parentPermission()->delete();
        $childInfo->parentAccount()->delete();
        $childInfo->childSchedule()->delete();
        $childInfo->payment()->delete();
    
        // Delete the child info record
        $childInfo->delete();
    
        // Redirect back with a success message
        return redirect()->route('admin.parents')->with('success', 'Parent and related data deleted successfully.');
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
public function deleteCs($id)
{
    $cs = CsInfo::find($id);

    if ($cs) {
        $cs->delete();
        return redirect()->route('admin.cs.list')->with('success', 'CS record deleted successfully.');
    }

    return redirect()->route('admin.cs.list')->with('error', 'CS record not found.');
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
public function deleteSales($id)
{
    $sales = SalesLeadInfo::find($id);

    if ($sales) {
        $sales->delete();
        return redirect()->route('admin.sales.list')->with('success', 'Sales record deleted successfully.');
    }

    return redirect()->route('admin.sales.list')->with('error', 'Sales record not found.');
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
public function deleteTherapist($id)
{
    $therapist = TherapistInfo::find($id);

    if (!$therapist) {
        return redirect()->route('admin.therapist.list')->with('error', 'Therapist not found.');
    }

    // Check if the therapist's name exists in ChildSchedule->therapist column
    $existsInSchedule = ChildSchedule::where('therapist', $therapist->name)->exists();

    if ($existsInSchedule) {
        return redirect()->route('admin.therapist.list')->with('error', 'This therapist cannot be deleted because they are assigned to a schedule.');
    }

    // Proceed with deletion
    $therapist->delete();

    return redirect()->route('admin.therapist.list')->with('success', 'Therapist deleted successfully.');
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


//package/service
public function indexPackage()
{
    $packages = Package::all();
    return view('admin.package.index', compact('packages'));
}
public function createPackage()
{
    return view('admin.package.create');
}
public function storePackage(Request $request)
{
    $validated = $request->validate([
        'package_name' => 'required|string|max:255',
        'package_step' => 'required|string|max:255',
        'session_quantity' => 'required|integer',
        'type' => 'required|string',
        'quota' => 'required|integer',
        'package_normal_price' => 'required|numeric',
        'package_wkday_price' => 'required|numeric',
        'package_wkend_price' => 'required|numeric',
        'package_long_desc1' => 'nullable|string',
        'package_long_desc2' => 'nullable|string',
        'package_long_desc3' => 'nullable|string',
        'package_short_desc1' => 'nullable|string',
        'package_short_desc2' => 'nullable|string',
        'package_short_desc3' => 'nullable|string',
        'package_short_desc4' => 'nullable|string',
        'package_short_desc5' => 'nullable|string',
        'citizenship' => 'required|in:yes,no',
        'weekly' => 'required|in:yes,no',
        'consultation' => 'required|in:yes,no',
        'file' => 'nullable|file|mimes:jpg,png,pdf',
    ]);

    // Handle file upload
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '.' . $file->extension();
        $path = $file->storeAs('packages', $filename, 'public'); // Save to the "packages" directory
        $validated['filename'] = $filename;
        $validated['path'] = $path;
    } else {
        $validated['filename'] = null;
        $validated['path'] = null;
    }

    Package::create($validated);

    return redirect()->route('admin.package.index')->with('success', 'Package created successfully.');
}
public function editPackage($id)
{
    $package = Package::findOrFail($id);
    return view('admin.package.edit', compact('package'));
}
public function updatePackage(Request $request, $id)
{
    $package = Package::findOrFail($id);

    $validated = $request->validate([
        'package_name' => 'required|string|max:255',
        'package_step' => 'required|string|max:255',
        'session_quantity' => 'required|integer',
        'type' => 'required|string',
        'quota' => 'required|integer',
        'package_normal_price' => 'required|numeric',
        'package_wkday_price' => 'required|numeric',
        'package_wkend_price' => 'required|numeric',
        'package_long_desc1' => 'nullable|string',
        'package_long_desc2' => 'nullable|string',
        'package_long_desc3' => 'nullable|string',
        'package_short_desc1' => 'nullable|string',
        'package_short_desc2' => 'nullable|string',
        'package_short_desc3' => 'nullable|string',
        'package_short_desc4' => 'nullable|string',
        'package_short_desc5' => 'nullable|string',
        'citizenship' => 'required|in:yes,no',
        'weekly' => 'required|in:yes,no',
        'consultation' => 'required|in:yes,no',
        'file' => 'nullable|file|mimes:jpg,png,pdf',
    ]);

    // Handle file upload
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '.' . $file->extension();
        $path = $file->storeAs('packages', $filename, 'public'); // Save to the "packages" directory
        $validated['filename'] = $filename;
        $validated['path'] = $path;
    } else {
        $validated['filename'] = null;
        $validated['path'] = null;
    }

    $package->update($validated);

    return redirect()->route('admin.package.index')->with('success', 'Package updated successfully.');
}
public function destroyPackage($id)
{
    $package = Package::findOrFail($id);
    $package->delete();

    return redirect()->route('admin.package.index')->with('success', 'Package deleted successfully.');
}

}
