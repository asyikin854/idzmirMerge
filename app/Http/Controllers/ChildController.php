<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class ChildController extends Controller
{
    // Method to show "Ready To School" schedules with month filter
    public function showReadyToSchoolSchedules(Request $request)
{
    // Get the selected month from the form, or default to the current month
    $selectedMonth = $request->input('month', now()->format('Y-m'));  // Format as 'YYYY-MM'

    // Fetch children enrolled in package_id 6 or 7 and filter by the selected month
    $children = DB::table('child_infos')
        ->join('packages', 'child_infos.package_id', '=', 'packages.id')
        ->join('child_schedules', 'child_infos.id', '=', 'child_schedules.child_id')
        ->select(
            'child_infos.id as child_id',
            'child_infos.child_name',
            'packages.package_name',
            'child_schedules.session',
            'child_schedules.day',
            'child_schedules.time',
            'child_schedules.price',
            'child_schedules.therapist',
            'child_schedules.attendance',
            'child_schedules.remark',
            'child_schedules.status',
            'child_schedules.date'
        )
        ->whereIn('child_infos.package_id', [6, 7])
        ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
        ->get();

    // Fetch actual active session (attendance marked as present in the selected month)
    $actualActiveSession = DB::table('child_schedules')
        ->where('attendance', 'present')
        ->whereMonth('date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('date', Carbon::parse($selectedMonth)->format('Y'))
        ->count();

    // Fetch total number of children who take package_id = 6 and have status 'Active' in the selected month (based on date in child_schedules)
    $totalChildrenPackage6Active = DB::table('child_infos')
        ->join('child_schedules', 'child_infos.id', '=', 'child_schedules.child_id')
        ->where('child_infos.package_id', 6)
        ->where('child_infos.status', 'Active')
        ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
        ->distinct('child_infos.id')  // Ensuring that each child is counted only once
        ->count();

    // Fetch total number of children who take package_id = 7 and have status 'Active' in the selected month (based on date in child_schedules)
    $totalChildrenPackage7Active = DB::table('child_infos')
        ->join('child_schedules', 'child_infos.id', '=', 'child_schedules.child_id')
        ->where('child_infos.package_id', 7)
        ->where('child_infos.status', 'Active')
        ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
        ->distinct('child_infos.id')  // Ensuring that each child is counted only once
        ->count();

    // Total number of children in package_id = 6 (no status filter)
    $totalChildrenPackage6 = DB::table('child_infos')
        ->where('package_id', 6)
        ->count();

    // Total number of children in package_id = 7 (no status filter)
    $totalChildrenPackage7 = DB::table('child_infos')
        ->where('package_id', 7)
        ->count();

    $totalChildrenPackage = $totalChildrenPackage7 + $totalChildrenPackage6;

    // Calculate target active session and variance
    $targetActiveSession = 60 * 4;  // Example: 60 children per session * 4 sessions
    $varianceActiveSession = $actualActiveSession - $targetActiveSession;
    $PerformanceCapacityUtilization = $actualActiveSession / $targetActiveSession;

    // Calculate PerformanceActiveStudent
    $PerformanceActiveStudent = ($totalChildrenPackage6Active + $totalChildrenPackage7Active) / $totalChildrenPackage * 100;

    // ActiveProgramRTS4 and ActiveProgram8
    $ActiveProgramRTS4 = ($totalChildrenPackage6Active * 4) * 2.5;
    $ActiveProgram8 = ($totalChildrenPackage7Active * 8) * 2.5;

    // Total Hours
    $TotalHours = $ActiveProgramRTS4 + $ActiveProgram8;
    $TotalActiveChild67 = $totalChildrenPackage6Active + $totalChildrenPackage7Active;

    // PerformanceActiveProgramByCapacityPerHour
    $PerformanceActiveProgramByCapacityPerHour = ($TotalHours / ($targetActiveSession * 2.5)) * 100;

    // New Calculation: Total Inactive Students based on attendance 'absent'
    $totalInactiveStudentByAttendance = DB::table('child_schedules')
        ->join('child_infos', 'child_schedules.child_id', '=', 'child_infos.id')
        ->whereIn('child_infos.package_id', [6, 7])
        ->where('child_schedules.attendance', 'absent')
        ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
        ->distinct('child_infos.id') // Ensure distinct children are counted only once
        ->count();

    // New Calculation: Total Inactive Students based on 'Inactive' status in child_infos
    $totalInactiveStudentByStatus = DB::table('child_infos')
        ->whereIn('package_id', [6, 7])
        ->where('status', 'Inactive')
        ->distinct('id')  // Ensuring that each child is counted only once
        ->count();

        // New Calculation: PerformanceLostRiskBySession
    $PerformanceLostRiskBySession = $actualActiveSession > 0 
    ? ($totalInactiveStudentByAttendance / $actualActiveSession) * 100 
    : 0;  // If actualActiveSession is 0, set PerformanceLostRiskBySession to 0

    $PerformanceLostRiskByStudent = $TotalActiveChild67 > 0 
        ? ($totalInactiveStudentByStatus / $TotalActiveChild67) * 100 
        : 0;  // If there are no active children, set PerformanceLostRiskByStudent to 0

    return view('admin.child.rts', compact(
        'children',
        'actualActiveSession',
        'targetActiveSession',
        'varianceActiveSession',
        'PerformanceCapacityUtilization',
        'totalChildrenPackage6',
        'totalChildrenPackage7',
        'totalChildrenPackage6Active',
        'totalChildrenPackage7Active',
        'PerformanceActiveStudent',
        'ActiveProgramRTS4',
        'ActiveProgram8',
        'TotalHours',
        'PerformanceActiveProgramByCapacityPerHour',
        'totalInactiveStudentByAttendance', // Pass the total inactive students by attendance to the view
        'totalInactiveStudentByStatus', // Pass the total inactive students by status to the view
        'selectedMonth' ,
        'TotalActiveChild67',
        'PerformanceLostRiskBySession',
        'PerformanceLostRiskByStudent' // Pass the selected month to the view
    ));
}

public function showFullAssessmentSchedules(Request $request)
{
    // Get the selected month from the form, or default to the current month
    $selectedMonth = $request->input('month', now()->format('Y-m'));  // Format as 'YYYY-MM'

    // Fetch children enrolled in package_id 2 or 12 and filter by the selected month
    $children = DB::table('child_infos')
        ->join('packages', 'child_infos.package_id', '=', 'packages.id')
        ->join('child_schedules', 'child_infos.id', '=', 'child_schedules.child_id')
        ->select(
            'child_infos.id as child_id',
            'child_infos.child_name',
            'packages.package_name',
            'child_schedules.session',
            'child_schedules.day',
            'child_schedules.time',
            'child_schedules.price',
            'child_schedules.therapist',
            'child_schedules.attendance',
            'child_schedules.remark',
            'child_schedules.status',
            'child_schedules.date'
        )
        ->whereIn('child_infos.package_id', [2, 12])  // Replace package 6 and 7 with 2 and 12
        ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
        ->get();

    // Fetch actual active session (attendance marked as present in the selected month)
    $actualActiveSession = DB::table('child_schedules')
        ->where('attendance', 'present')
        ->whereMonth('date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('date', Carbon::parse($selectedMonth)->format('Y'))
        ->count();

    // Fetch total number of children who take package_id = 2 and have status 'Active' in the selected month (based on date in child_schedules)
    $totalChildrenPackage2Active = DB::table('child_infos')
        ->join('child_schedules', 'child_infos.id', '=', 'child_schedules.child_id')
        ->where('child_infos.package_id', 2)
        ->where('child_infos.status', 'Active')
        ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
        ->distinct('child_infos.id')  // Ensuring that each child is counted only once
        ->count();

    // Fetch total number of children who take package_id = 12 and have status 'Active' in the selected month (based on date in child_schedules)
    $totalChildrenPackage12Active = DB::table('child_infos')
        ->join('child_schedules', 'child_infos.id', '=', 'child_schedules.child_id')
        ->where('child_infos.package_id', 12)
        ->where('child_infos.status', 'Active')
        ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
        ->distinct('child_infos.id')  // Ensuring that each child is counted only once
        ->count();

    // Total number of children in package_id = 2 (no status filter)
    $totalChildrenPackage2 = DB::table('child_infos')
        ->where('package_id', 2)
        ->count();

    // Total number of children in package_id = 12 (no status filter)
    $totalChildrenPackage12 = DB::table('child_infos')
        ->where('package_id', 12)
        ->count();

    $totalChildrenPackage = $totalChildrenPackage12 + $totalChildrenPackage2;

    // Calculate target active session and variance
    $targetActiveSession = 60 * 4;  // Example: 60 children per session * 4 sessions
    $varianceActiveSession = $actualActiveSession - $targetActiveSession;
    $PerformanceCapacityUtilization = $actualActiveSession / $targetActiveSession;

    // Calculate PerformanceActiveStudent
    $PerformanceActiveStudent = ($totalChildrenPackage2Active + $totalChildrenPackage12Active) / $totalChildrenPackage * 100;

    // ActiveProgramRTS4 and ActiveProgram8
    $ActiveProgramRTS4 = ($totalChildrenPackage2Active * 4) * 2.5;
    $ActiveProgram8 = ($totalChildrenPackage12Active * 8) * 2.5;

    // Total Hours
    $TotalHours = $ActiveProgramRTS4 + $ActiveProgram8;
    $TotalActiveChild212 = $totalChildrenPackage2Active + $totalChildrenPackage12Active;

    // PerformanceActiveProgramByCapacityPerHour
    $PerformanceActiveProgramByCapacityPerHour = ($TotalHours / ($targetActiveSession * 2.5)) * 100;

    // New Calculation: Total Inactive Students based on attendance 'absent'
    $totalInactiveStudentByAttendance = DB::table('child_schedules')
        ->join('child_infos', 'child_schedules.child_id', '=', 'child_infos.id')
        ->whereIn('child_infos.package_id', [2, 12])
        ->where('child_schedules.attendance', 'absent')
        ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
        ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
        ->distinct('child_infos.id') // Ensure distinct children are counted only once
        ->count();

    // New Calculation: Total Inactive Students based on 'Inactive' status in child_infos
    $totalInactiveStudentByStatus = DB::table('child_infos')
        ->whereIn('package_id', [2, 12])
        ->where('status', 'Inactive')
        ->distinct('id')  // Ensuring that each child is counted only once
        ->count();

    // New Calculation: PerformanceLostRiskBySession
    $PerformanceLostRiskBySession = $actualActiveSession > 0 
        ? ($totalInactiveStudentByAttendance / $actualActiveSession) * 100 
        : 0;  // If actualActiveSession is 0, set PerformanceLostRiskBySession to 0

    $PerformanceLostRiskByStudent = $TotalActiveChild212 > 0 
        ? ($totalInactiveStudentByStatus / $TotalActiveChild212) * 100 
        : 0;  // If there are no active children, set PerformanceLostRiskByStudent to 0

    return view('admin.child.fa', compact(
        'children',
        'actualActiveSession',
        'targetActiveSession',
        'varianceActiveSession',
        'PerformanceCapacityUtilization',
        'totalChildrenPackage2',
        'totalChildrenPackage12',
        'totalChildrenPackage2Active',
        'totalChildrenPackage12Active',
        'PerformanceActiveStudent',
        'ActiveProgramRTS4',
        'ActiveProgram8',
        'TotalHours',
        'PerformanceActiveProgramByCapacityPerHour',
        'totalInactiveStudentByAttendance', // Pass the total inactive students by attendance to the view
        'totalInactiveStudentByStatus', // Pass the total inactive students by status to the view
        'selectedMonth',
        'TotalActiveChild212',
        'PerformanceLostRiskBySession',
        'PerformanceLostRiskByStudent' // Pass the selected month to the view
    ));
}


    // Method to show "Intervention With Consistency" schedules with month filter
    public function showInterventionWithConsistencySchedules(Request $request)
    {
        // Get the selected month from the form, or default to the current month
        $selectedMonth = $request->input('month', now()->format('Y-m'));  // Format as 'YYYY-MM'

        // Fetch children enrolled in package_id 3, 8, 10, 11 and filter by the selected month
        $children = DB::table('child_infos')
            ->join('packages', 'child_infos.package_id', '=', 'packages.id')
            ->join('child_schedules', 'child_infos.id', '=', 'child_schedules.child_id')
            ->select(
                'child_infos.id as child_id',
                'child_infos.child_name',
                'packages.package_name',
                'child_schedules.session',
                'child_schedules.day',
                'child_schedules.time',
                'child_schedules.price',
                'child_schedules.therapist',
                'child_schedules.attendance',
                'child_schedules.remark',
                'child_schedules.status',
                'child_schedules.date'
            )
            ->whereIn('child_infos.package_id', [3, 8, 10, 11])
            ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
            ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
            ->get();

        // Fetch actual active session (attendance marked as present in the selected month)
        $actualActiveSession = DB::table('child_schedules')
            ->where('attendance', 'present')
            ->whereMonth('date', Carbon::parse($selectedMonth)->format('m'))
            ->whereYear('date', Carbon::parse($selectedMonth)->format('Y'))
            ->count();

        // Fetch total number of children who take package_id = 3, 8, 10, 11 and have status 'Active' in the selected month
        $totalChildrenPackageActive = DB::table('child_infos')
            ->join('child_schedules', 'child_infos.id', '=', 'child_schedules.child_id')
            ->whereIn('child_infos.package_id', [3, 8, 10, 11])
            ->where('child_infos.status', 'Active')
            ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
            ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
            ->distinct('child_infos.id')  // Ensuring that each child is counted only once
            ->count();

        // Total number of children in package_id = 3, 8, 10, 11 (no status filter)
        $totalChildrenPackage = DB::table('child_infos')
            ->whereIn('package_id', [3, 8, 10, 11])
            ->count();

        // Calculate target active session and variance
        $targetActiveSession = 60 * 4;  // Example: 60 children per session * 4 sessions
        $varianceActiveSession = $actualActiveSession - $targetActiveSession;
        $PerformanceCapacityUtilization = $actualActiveSession / $targetActiveSession;

        // Calculate PerformanceActiveStudent
        $PerformanceActiveStudent = ($totalChildrenPackageActive) / $totalChildrenPackage * 100;

        // ActiveProgramRTS4 and ActiveProgram8
        $ActiveProgramRTS4 = ($totalChildrenPackageActive * 4) * 2.5;
        $ActiveProgram8 = ($totalChildrenPackageActive * 8) * 2.5;

        // Total Hours
        $TotalHours = $ActiveProgramRTS4 + $ActiveProgram8;
        $TotalActiveChild = $totalChildrenPackageActive;

        // PerformanceActiveProgramByCapacityPerHour
        $PerformanceActiveProgramByCapacityPerHour = ($TotalHours / ($targetActiveSession * 2.5)) * 100;

        // New Calculation: Total Inactive Students based on attendance 'absent'
        $totalInactiveStudentByAttendance = DB::table('child_schedules')
            ->join('child_infos', 'child_schedules.child_id', '=', 'child_infos.id')
            ->whereIn('child_infos.package_id', [3, 8, 10, 11])
            ->where('child_schedules.attendance', 'absent')
            ->whereMonth('child_schedules.date', Carbon::parse($selectedMonth)->format('m'))
            ->whereYear('child_schedules.date', Carbon::parse($selectedMonth)->format('Y'))
            ->distinct('child_infos.id')  // Ensure distinct children are counted only once
            ->count();

        // New Calculation: Total Inactive Students based on 'Inactive' status in child_infos
        $totalInactiveStudentByStatus = DB::table('child_infos')
            ->whereIn('package_id', [3, 8, 10, 11])
            ->where('status', 'Inactive')
            ->distinct('id')  // Ensuring that each child is counted only once
            ->count();

        // New Calculation: PerformanceLostRiskBySession
        $PerformanceLostRiskBySession = $actualActiveSession > 0 
        ? ($totalInactiveStudentByAttendance / $actualActiveSession) * 100 
        : 0;  // If actualActiveSession is 0, set PerformanceLostRiskBySession to 0

        $PerformanceLostRiskByStudent = $TotalActiveChild > 0 
            ? ($totalInactiveStudentByStatus / $TotalActiveChild) * 100 
            : 0;  // If there are no active children, set PerformanceLostRiskByStudent to 0

        return view('admin.child.intervention', compact(
            'children',
            'actualActiveSession',
            'targetActiveSession',
            'varianceActiveSession',
            'PerformanceCapacityUtilization',
            'totalChildrenPackage',
            'totalChildrenPackageActive',
            'PerformanceActiveStudent',
            'ActiveProgramRTS4',
            'ActiveProgram8',
            'TotalHours',
            'PerformanceActiveProgramByCapacityPerHour',
            'totalInactiveStudentByAttendance', // Pass the total inactive students by attendance to the view
            'totalInactiveStudentByStatus', // Pass the total inactive students by status to the view
            'selectedMonth',
            'TotalActiveChild',
            'PerformanceLostRiskBySession',
            'PerformanceLostRiskByStudent' // Pass the selected month to the view
        ));
    }


}
