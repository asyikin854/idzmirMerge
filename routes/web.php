<?php

use Chip\ChipApi;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\ParentAccount;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\AdminDashboardController;


Route::get('/', function () {
    return redirect()->route('login');
})->name('/');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware(['auth:parent'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/dashboard-parent', [ParentController::class, 'parentDashboard'])->name('parent.dashboard');
    });
});
Route::middleware(['auth:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    });
});

Route::middleware(['auth:therapist'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/dashboard-therapist', [TherapistController::class, 'therapistDashboard'])->name('therapist.dashboard');
    });
});

Route::middleware(['auth:cs'])->group(function () {
    Route::get('/csDashboard', [CsController::class, 'csDashboard'])->name('cs.dashboard');
});

Route::middleware(['auth:sales'])->group(function () {
    Route::get('/salesDashboard', [SalesController::class, 'salesDashboard'])->name('sales.dashboard');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/inquiry-form', [LoginController::class, 'inquiryView'])->name('inquiry-form');
Route::post('/inquiry-form', [LoginController::class, 'inquirySubmit'])->name('inquiry-submit');

//new register account and payment
Route::get('FApaymentDetails-parent/{child_id}', [ParentController::class, 'FApaymentDetails'])->name('FApaymentDetails-parent');
Route::post('FApaymentSubmit-parent', [ParentController::class, 'FApaymentSubmit'])->name('FApaymentSubmit-parent');
Route::get('resetPassword-parent/{child_id}', [ParentController::class, 'resetPassword'])->name('resetPassword-parent');
Route::post('resetPassSubmit-parent', [ParentController::class, 'resetPassSubmit'])->name('resetPassSubmit-parent');



Route::get('schedule-parent', [ParentController::class, 'parentScheduleView'])->name('schedule.view');
Route::get('rescheduleView-parent/{id}', [ParentController::class, 'rescheduleView'])->name('reschedule.view');
Route::post('reschedule-parent/{id}', [ParentController::class, 'reschedule'])->name('reschedule-parent');
Route::get('profile-parent', [ParentController::class, 'parentProfile'])->name('profile-parent');
Route::get('paymentList', [ParentController::class, 'paymentList'])->name('paymentList-parent');
Route::get('program', [ParentController::class, 'program'])->name('program-parent');
Route::get('/changeProgram-parent/{child_id}/{package_id}', [ParentController::class, 'changeProgram'])->name('changeProgram-parent');
Route::get('/newSchedule-parent/{child_id}/{package_id}', [ParentController::class, 'newScheduleView'])->name('newSchedule-parent');
Route::post('/newScheduleSubmit-parent/{child_id}/{package_id}', [ParentController::class, 'newScheduleSubmit'])->name('newScheduleSubmit-parent');
Route::get('/newConsultSchedule-parent/{child_id}/{package_id}', [ParentController::class, 'newConsultScheduleView'])->name('newConsultSchedule-parent');
Route::post('/newConsultSchedule/{child_id}/{package_id}', [ParentController::class, 'newConsultSchedule'])->name('newConsultSchedule.submit');
Route::get('/announcement-parent', [EmailController::class, 'parentInbox'])->name('announcement-parent');
Route::get('/messageDetails-parent', [EmailController::class, 'fetchMessage'])->name('fetchMessage-parent');
Route::get('/newProgPayment-parent/{child_id}/{package_id}', [ParentController::class, 'newProgPayment'])->name('newProgPayment-parent');
Route::post('/submitNewProgPayment', [ParentController::class, 'submitNewProgPayment'])->name('submitNewProgPayment');
Route::get('/payment-success2', [ParentController::class, 'paymentSuccess2'])->name('payment.success2');
Route::get('/payment-failure2/{child_id}/{package_id}', [ParentController::class, 'paymentFailure2'])->name('payment.failure2');


Route::get('register-parent', [RegisterController::class, 'registerView'])->name('register-parent');
Route::post('/check-child-exists', [RegisterController::class, 'checkChildExists'])->name('check.child.exists');
Route::post('registerNew', [RegisterController::class, 'registerNew'])->name('register.new');
Route::get('register2-parent/{id}', [RegisterController::class, 'register2'])->name('register2-parent');
Route::get('product-parent/{child_id}', [RegisterController::class, 'packageView'])->name('product-parent');
Route::post('/packageProceed/{child_id}/{package_id}', [RegisterController::class, 'packageProceed'])->name('packageProceed-parent');
// Route to view the child schedule form (GET)
Route::get('/childScheduleView/{child_id}/{package_id}', [RegisterController::class, 'childScheduleView'])->name('childSchedule.view');
// Route to handle the form submission and save the child schedule (POST)
Route::post('/childSchedule/{child_id}/{package_id}', [RegisterController::class, 'childSchedule'])->name('childSchedule.submit');

Route::get('/consultSchedule-parent/{child_id}/{package_id}', [RegisterController::class, 'consultScheduleView'])->name('consultSchedule-parent');
Route::post('/consultSchedule/{child_id}/{package_id}', [RegisterController::class, 'consultSchedule'])->name('consultSchedule.submit');
// Route to view the checkout page after schedule submission (GET)
Route::get('/bookSummaryExistCust/{child_id}/{package_id}', [RegisterController::class, 'checkoutParent'])->name('checkout-parent');
Route::post('/submitExistCust/{child_id}', [RegisterController::class, 'submitExistCust'])->name('submitExistCust');
Route::get('/successAddExistCust', [RegisterController::class, 'successAddExistCust'])->name('successAddExistCust');

// Route to handle the payment submission (POST)
Route::post('/submitPayment', [RegisterController::class, 'submitPayment'])->name('submitPayment');

Route::post('chip/callback/', function (Request $request) {
    $signature = $request->header('X-Signature');
    $content = $request->getContent();
	
    $response = Http::withHeaders([
        'Authorization' => "Bearer " . env('CHIP_API_KEY')
    ])->get("https://gate.chip-in.asia/api/v1/public_key/");
    $response_body = strval($response->body());

    $response_arr = explode('\n', $response_body, -1);
    array_shift($response_arr);
    array_pop($response_arr);
    $response_arr_flat = "";
    foreach ($response_arr as $string) {
        $response_arr_flat .= $string . "\n";
    }
    $pub_key = "-----BEGIN PUBLIC KEY-----\n" . $response_arr_flat . "-----END PUBLIC KEY-----\n";

    $is_verified = \Chip\ChipApi::verify($content, $signature, $pub_key);

    if (!$is_verified) {
        Log::warning("CALLBACK: X-Signature Mismatch");
        return response()->json([
            'error' => 'X-Signature Mismatch'
        ], 400);
    }

    // Update order status
    Log::info("CALLBACK: Transaction ID $request->id verification successful");
    $adminEmail = "icares.idzmirkidshub@gmail.com";
    $payment = Payment::where('payment_id', $request->id)->first();
    if ($payment) {
        $payment->status = $request->status;
        $payment->save();

        if ($request->status === 'paid') {
            $parentAccount = ParentAccount::where('child_id', $payment->child_id)->first();
            if ($parentAccount && $parentAccount->email) {
                try {
                    Mail::to($parentAccount->email)->send(new PaymentSuccessMail($payment));
                    Mail::to($adminEmail)->send(new PaymentSuccessAdmin($payment));
                    Log::info("CALLBACK: Payment success email sent to " . $parentAccount->email);
                } catch (\Exception $e) {
                    Log::error("CALLBACK: Failed to send payment success email: " . $e->getMessage());
                }
            }
        }
    }

    Log::info("CALLBACK: X-Signature Verified!");
    return response()->json(['status' => 'CALLBACK: OK']);
});

Route::post('/webhook/payment', function (Request $request) {
    $signature = $request->header('X-Signature');
    $content = $request->getContent();
    $event = $request->input('event_type');
    $pub_key = env('CHIP_WEBHOOK_PUBLIC_KEY');

    $is_verified = \Chip\ChipApi::verify($content, $signature, $pub_key);

    if (!$is_verified) {
        Log::warning("WEBHOOK: X-Signature Mismatch");

        return response()->json([
            'error' => 'X-Signature Mismatch'
        ], 400);
    }

    // Upon successfull verification, update transaction status in database if necessary
    // Update order & transaction status to whatever the $event is
    Log::info("WEBHOOK: $request->id");
    $payment = Payment::where('payment_id', $request->id)->first();
    if ($payment) {
        $payment->status = $event;
        $payment->save();
    }

    Log::info("WEBHOOK: X-Signature Ok!");
    return response()->json([
        'status' => 'WEBHOOK: OK',
    ]);
});
// Route for handling payment callback (GET or POST, depending on Chip's method)
// Routes for payment success and failure pages
Route::get('/payment-success', [RegisterController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment-failure/{child_id}/{package_id}', [RegisterController::class, 'paymentFailure'])->name('payment.failure');





Route::get('therapist-session', [TherapistController::class, 'therapistSessions'])->name('therapist-session');
Route::get('stdList-therapist', [TherapistController::class, 'therapistStudents'])->name('stdList-therapist');
Route::get('stdDetails-therapist/{id}', [TherapistController::class, 'studentDetails'])->name('stdDetails-therapist');
Route::get('stdReportList-therapist', [TherapistController::class, 'therapistStdReportList'])->name('stdReportList-therapist');
Route::get('sessionReport-therapist/{id}', [TherapistController::class, 'therapistReportView'])->name('sessionReport-therapist');
Route::post('/therapistReportSubmit', [TherapistController::class, 'therapistReportSubmit'])->name('therapist.reportSubmit');
Route::get('/sessionDetails-therapist/{date}/{time}', [TherapistController::class, 'therapistSessionDetails'])->name('sessionDetails-therapist');
Route::put('/therapistStdAttendance/{id}', [TherapistController::class, 'therapistStdAttendance'])->name('therapist.stdAttendance');


Route::get('/dashboard-cs', [CsController::class, 'csDashboard'])->name('cs.dashboard');
Route::get('/session-cs', [CsController::class, 'csSession'])->name('session-cs');
Route::get('/studentList-cs', [CsController::class, 'csStudentList'])->name('studentList-cs');
Route::get('/stdDetails-cs/{id}', [CsController::class, 'csStdDetails'])->name('stdDetails-cs');
Route::get('/editProgram-cs/{child_id}/{package_id}', [CsController::class, 'csEditProgramView'])->name('editProgramView-cs');
Route::get('/editProgSchedule-cs/{child_id}/{package_id}', [CsController::class, 'csEditProgScheduleView'])->name('editProgScheduleView-cs');
Route::post('/csEditProgSchedule-cs/{child_id}/{package_id}', [CsController::class, 'csEditProgSchedule'])->name('editProgSchedule-cs');
Route::get('/rescheduleView-cs/{id}', [CsController::class, 'csRescheduleView'])->name('rescheduleView-cs');
Route::post('/csReschedule/{id}', [CsController::class, 'csReschedule'])->name('reschedule-cs');
Route::get('/unassignedList-cs', [CsController::class, 'csUnassignedList'])->name('unassignedList-cs');
Route::get('/assignedSession-cs', [CsController::class, 'csAssignedSession'])->name('assignedSession-cs');
Route::get('/assignedDetails-cs/{id}', [CsController::class, 'csAssignedDetails'])->name('assignedDetails-cs');
Route::get('/approvedReportList-cs', [CsController::class, 'csApprovedReportList'])->name('approvedReportList-cs');
Route::get('/download-report/{id}', [CsController::class, 'downloadReport'])->name('downloadReport-cs');
Route::post('/bulk-download-reports', [CsController::class, 'bulkDownloadReports'])->name('bulkDownloadReports-cs');
Route::get('/stdReportList-cs', [CsController::class, 'csStdReportList'])->name('stdReportList-cs');
Route::get('/allSession-cs', [CsController::class, 'csAllSession'])->name('allSession-cs');

Route::get('/assignTherapist-cs/{id}', [CsController::class, 'csAssignTherapist'])->name('assignTherapist-cs');
Route::post('/assignTherapist', [CsController::class, 'assignTherapist'])->name('assign.therapist');
Route::get('/approveRescheduleList-cs', [CsController::class, 'csApproveRescheduleList'])->name('approveRescheduleList-cs');
Route::get('/approveReqView-cs/{id}', [CsController::class, 'csApproveReqView'])->name('approveReqView-cs');
Route::put('/csApproveReq/{id}', [CsController::class, 'csApproveReq'])->name('cs.approveReq');
Route::get('/approvedReport-cs/{id}', [CsController::class, 'csApprovedReport'])->name('approvedReport-cs');
Route::get('/reportApproval-cs/{id}', [CsController::class, 'csReportApproval'])->name('reportApproval-cs');
Route::post('/csReportApproved', [CsController::class, 'csReportApproved'])->name('cs.reportApproved');
Route::get('sendEmail-cs', [EmailController::class, 'csCompose'])->name('composeEmail-cs');
Route::post('csSend', [EmailController::class, 'csSend'])->name('sendEmail-cs');
Route::get('csInbox', [EmailController::class, 'csInbox'])->name('inbox-cs');

//customer service (new sales lead)
Route::get('/dashboard-sales', [SalesController::class, 'salesDashboard'])->name('sales.dashboard');
//New leads
Route::get('/newCustomer-sales', [SalesController::class, 'newCustomer'])->name('newCustomer-sales');
Route::get('/custDetails-sales/{id}', [SalesController::class, 'custDetails'])->name('custDetails-sales');
Route::post('/addCustomer', [SalesController::class, 'addCustomer'])->name('addCustomer');
//Register new customer from leads
Route::get('/custDetails2-sales/{id}', [SalesController::class, 'custDetails2'])->name('custDetails2-sales');
Route::get('/regNewCust-sales/{id}', [SalesController::class, 'regNewCust'])->name('regNewCust-sales');
Route::post('customer/{id}/registerCust', [SalesController::class, 'registerCust'])->name('registerCust-sales');
Route::get('/scheduleSlotView/{child_id}/{package_id}', [SalesController::class, 'bookConsultView'])->name('scheduleSlot-sales');
Route::post('/scheduleSlot/{child_id}/{package_id}', [SalesController::class, 'scheduleSlot'])->name('scheduleSlot.submit');
Route::get('/confirmSchedule-sales/{child_id}/{package_id}', [SalesController::class, 'confirmScheduleView'])->name('confirmSchedule-sales');
Route::post('/confirmSchedule', [SalesController::class, 'confirmSchedule'])->name('confirmSchedule.submit');
//On Boarding management
Route::get('/onBoarding-sales', [SalesController::class, 'onBoarding'])->name('onBoarding-sales');
Route::get('/regOnBoarding-sales/{id}', [SalesController::class, 'regOnBoarding'])->name('regOnBoarding-sales');
Route::put('/submitRegOnBoard-sales/{id}', [SalesController::class, 'submitRegOnBoard'])->name('submitRegOnBoard-sales');
Route::get('/slotBookingView-sales/{child_id}', [SalesController::class, 'slotBookingView'])->name('slotBooking-sales');
Route::post('/slotBooking-sales/{child_id}', [SalesController::class, 'slotBooking'])->name('submitBooking-sales');
Route::get('/bookingSummary-sales/{child_id}', [SalesController::class, 'bookingSummary'])->name('bookingSummary-sales');
Route::post('/confirmBookSlot-sales/{child_id}', [SalesController::class, 'confirmBookSlot'])->name('confirmBookSlot-sales');
//Existing customer management
Route::get('/registeredCustomer-sales', [SalesController::class, 'registeredCustomer'])->name('registeredCustomer-sales');
Route::get('/consultationSessions-sales', [SalesController::class, 'consultationSessions'])->name('consultationSessions-sales');
Route::get('/allSession-sales', [SalesController::class, 'allSession'])->name('allSession-sales');
Route::get('/paymentStatus-sales', [SalesController::class, 'paymentStatus'])->name('paymentStatus-sales');


// Admin Dashboard Route
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

});

Route::prefix('authentication')->group(function () {
    Route::view('login', 'authentication.login')->name('login');
});

//Language Change
Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'de', 'es', 'fr', 'pt', 'cn', 'ae'])) {
        abort(400);
    }
    Session()->put('locale', $locale);
    Session::get('locale');
    return redirect()->back();
})->name('lang');


Route::prefix('page-layouts')->group(function () {
    Route::view('box-layout', 'page-layout.box-layout')->name('box-layout');
    Route::view('layout-rtl', 'page-layout.layout-rtl')->name('layout-rtl');
    Route::view('layout-dark', 'page-layout.layout-dark')->name('layout-dark');
    Route::view('hide-on-scroll', 'page-layout.hide-on-scroll')->name('hide-on-scroll');
    Route::view('footer-light', 'page-layout.footer-light')->name('footer-light');
    Route::view('footer-dark', 'page-layout.footer-dark')->name('footer-dark');
    Route::view('footer-fixed', 'page-layout.footer-fixed')->name('footer-fixed');
});

// Route::prefix('starter-kit')->group(function () {
// });

Route::prefix('others')->group(function () {
    Route::view('400', 'errors.400')->name('error-400');
    Route::view('401', 'errors.401')->name('error-401');
    Route::view('403', 'errors.403')->name('error-403');
    Route::view('404', 'errors.404')->name('error-404');
    Route::view('500', 'errors.500')->name('error-500');
    Route::view('503', 'errors.503')->name('error-503');
});



Route::prefix('layouts')->group(function () {
    Route::view('compact-sidebar', 'admin_unique_layouts.compact-sidebar'); //default //Dubai
    Route::view('box-layout', 'admin_unique_layouts.box-layout');    //default //New York //
    Route::view('dark-sidebar', 'admin_unique_layouts.dark-sidebar');

    Route::view('default-body', 'admin_unique_layouts.default-body');
    Route::view('compact-wrap', 'admin_unique_layouts.compact-wrap');
    Route::view('enterprice-type', 'admin_unique_layouts.enterprice-type');

    Route::view('compact-small', 'admin_unique_layouts.compact-small');
    Route::view('advance-type', 'admin_unique_layouts.advance-type');
    Route::view('material-layout', 'admin_unique_layouts.material-layout');

    Route::view('color-sidebar', 'admin_unique_layouts.color-sidebar');
    Route::view('material-icon', 'admin_unique_layouts.material-icon');
    Route::view('modern-layout', 'admin_unique_layouts.modern-layout');
});

Route::get('layout-{light}', function ($light) {
    session()->put('layout', $light);
    session()->get('layout');
    if ($light == 'vertical-layout') {
        return redirect()->route('pages-vertical-layout');
    }
    return redirect()->route('index');
    return 1;
});
Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
})->name('clear.cache');





Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');


    Route::get('/parents', [AdminDashboardController::class, 'listParents'])->name('admin.parents');
    Route::get('/parents/{id}', [AdminDashboardController::class, 'showParent'])->name('admin.parents.show');
    Route::get('/therapist/therapistList', [AdminDashboardController::class, 'listTherapist'])->name('admin.therapist.list');
    Route::get('/cs/csList', [AdminDashboardController::class, 'listCs'])->name('admin.cs.list');
    Route::get('/sales/salesList', [AdminDashboardController::class, 'listSales'])->name('admin.sales.list');
    Route::get('/payment/paymentList', [AdminDashboardController::class, 'paymentList'])->name('admin.payment.list');
    Route::get('/payment/paymentDetails{id}', [AdminDashboardController::class, 'paymentDetails'])->name('admin.payment.details');
    Route::get('/payment/get-child-schedules', [AdminDashboardController::class, 'getChildSchedulesBySessionId'])->name('getChildSchedulesBySessionId');

});
Route::prefix('admin/package')->group(function () {
    Route::get('/allpackage', [AdminDashboardController::class, 'indexPackage'])->name('admin.package.index');
    Route::get('/create', [AdminDashboardController::class, 'createPackage'])->name('admin.package.create');
    Route::post('/', [AdminDashboardController::class, 'storePackage'])->name('admin.package.store');
    Route::get('/{id}/edit', [AdminDashboardController::class, 'editPackage'])->name('admin.package.edit');
    Route::put('/{id}', [AdminDashboardController::class, 'updatePackage'])->name('admin.package.update');
    Route::delete('/{id}', [AdminDashboardController::class, 'destroyPackage'])->name('admin.package.destroy');
});


Route::put('/update-mother/{id}', [AdminDashboardController::class, 'updateMotherInfo'])->name('update.motherInfo');
Route::put('/update-father/{id}', [AdminDashboardController::class, 'updateFatherInfo'])->name('update.fatherInfo');
Route::put('/update-child/{id}', [AdminDashboardController::class, 'updateChildInfo'])->name('update.childInfo');
Route::put('/update-account/{id}', [AdminDashboardController::class, 'updateParentAccount'])->name('update.parentAccount');
Route::delete('/admin/parents/{id}', [AdminDashboardController::class, 'destroyParent'])->name('admin.parents.destroy');

Route::post('/addTherapist', [AdminDashboardController::class, 'addNewTherapist'])->name('addNewTherapist');
Route::post('/updateTherapist', [AdminDashboardController::class, 'updateTherapist'])->name('updateTherapist');
Route::delete('/admin/therapist/{id}', [AdminDashboardController::class, 'deleteTherapist'])->name('admin.therapist.destroy');


Route::post('/addCs', [AdminDashboardController::class, 'addNewCs'])->name('addNewCs');
Route::post('/updateCs', [AdminDashboardController::class, 'updateCs'])->name('updateCs');
Route::delete('/admin/cs/{id}', [AdminDashboardController::class, 'deleteCs'])->name('admin.cs.destroy');


Route::post('/addSales', [AdminDashboardController::class, 'addNewSales'])->name('addNewSales');
Route::post('/updateSales', [AdminDashboardController::class, 'updateSales'])->name('updateSales');
Route::delete('/admin/sales/{id}', [AdminDashboardController::class, 'deleteSales'])->name('admin.sales.destroy');


Route::prefix('admin')->group(function () {
    Route::get('/schedules', [AdminDashboardController::class, 'showSchedules'])->name('admin.schedules.index');
});
Route::get('/schedules/{id}/edit', [AdminDashboardController::class, 'edit'])->name('schedules.edit');

Route::put('/schedules/{id}', [AdminDashboardController::class, 'update'])->name('schedules.update');


Route::get('/schedules', [AdminDashboardController::class, 'showSchedules'])->name('schedules.index');


//email route


Route::get('/inbox', [EmailController::class, 'inbox'])->name('inbox');

// dashboard analytics
Route::get('/get-monthly-data', [AdminDashboardController::class, 'getMonthlyData']);
Route::get('/get-program-data', [AdminDashboardController::class, 'getProgramData']);






Route::get('/admin/email/compose', [EmailController::class, 'compose'])->name('admin.email.compose');
Route::post('/admin/email/send', [EmailController::class, 'send'])->name('admin.email.send');
Route::get('/admin/email/sent', [EmailController::class, 'sentAdmin'])->name('admin.email.sent');
Route::get('/admin/email/fetch', [EmailController::class, 'fetchEmailAdmin'])->name('admin.email.fetch');

Route::get('/children/ready-to-school', [ChildController::class, 'showReadyToSchoolSchedules'])->name('admin.child.rts');
Route::get('/children/full-assessment', [ChildController::class, 'showFullAssessmentSchedules'])->name(name: 'admin.child.fa');
// Route for Intervention with Consistency
Route::get('/children/intervention-with-consistency', [ChildController::class, 'showInterventionWithConsistencySchedules'])->name('admin.child.intervention');

