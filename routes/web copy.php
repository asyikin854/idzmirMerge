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
Route::get('/checkout-parent/{child_id}/{package_id}', [RegisterController::class, 'checkoutParent'])->name('checkout-parent');

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
    $payment = Payment::where('payment_id', $request->id)->first();
    if ($payment) {
        $payment->status = $request->status;
        $payment->save();

        if ($request->status === 'paid') {
            $parentAccount = ParentAccount::where('child_id', $payment->child_id)->first();
            if ($parentAccount && $parentAccount->email) {
                try {
                    Mail::to($parentAccount->email)->send(new PaymentSuccessMail($payment));
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


Route::get('/dashboard-sales', [SalesController::class, 'salesDashboard'])->name('sales.dashboard');
Route::get('/newCustomer-sales', [SalesController::class, 'newCustomer'])->name('newCustomer-sales');
Route::get('/custDetails-sales/{id}', [SalesController::class, 'custDetails'])->name('custDetails-sales');
Route::get('/registeredCustomer-sales', [SalesController::class, 'registeredCustomer'])->name('registeredCustomer-sales');
Route::get('/custDetails2-sales/{id}', [SalesController::class, 'custDetails2'])->name('custDetails2-sales');
Route::post('/addCustomer', [SalesController::class, 'addCustomer'])->name('addCustomer');
Route::get('/regNewCust-sales/{id}', [SalesController::class, 'regNewCust'])->name('regNewCust-sales');
Route::post('customer/{id}/registerCust', [SalesController::class, 'registerCust'])->name('registerCust-sales');
Route::get('/scheduleSlotView/{child_id}/{package_id}', [SalesController::class, 'scheduleSlotView'])->name('scheduleSlot-sales');
Route::post('/scheduleSlot/{child_id}/{package_id}', [SalesController::class, 'scheduleSlot'])->name('scheduleSlot.submit');
Route::get('/confirmSchedule-sales/{child_id}/{package_id}', [SalesController::class, 'confirmScheduleView'])->name('confirmSchedule-sales');
Route::post('/confirmSchedule', [SalesController::class, 'confirmSchedule'])->name('confirmSchedule.submit');
Route::get('/consultationSessions-sales', [SalesController::class, 'consultationSessions'])->name('consultationSessions-sales');
Route::get('/paymentStatus-sales', [SalesController::class, 'paymentStatus'])->name('paymentStatus-sales');


// Admin Dashboard Route
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

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

Route::prefix('dashboard')->group(function () {
    Route::view('index', 'dashboard.index')->name('index');
    Route::view('dashboard-02', 'dashboard.dashboard-02')->name('dashboard-02');
    Route::view('dashboard-03', 'dashboard.dashboard-03')->name('dashboard-03');

    Route::view('dashboard-04', 'dashboard.dashboard-04')->name('dashboard-04');
    Route::view('dashboard-05', 'dashboard.dashboard-05')->name('dashboard-05');
});

Route::prefix('widgets')->group(function () {
    Route::view('general-widget', 'widgets.general-widget')->name('general-widget');
    Route::view('chart-widget', 'widgets.chart-widget')->name('chart-widget');
});

Route::prefix('page-layouts')->group(function () {
    Route::view('box-layout', 'page-layout.box-layout')->name('box-layout');
    Route::view('layout-rtl', 'page-layout.layout-rtl')->name('layout-rtl');
    Route::view('layout-dark', 'page-layout.layout-dark')->name('layout-dark');
    Route::view('hide-on-scroll', 'page-layout.hide-on-scroll')->name('hide-on-scroll');
    Route::view('footer-light', 'page-layout.footer-light')->name('footer-light');
    Route::view('footer-dark', 'page-layout.footer-dark')->name('footer-dark');
    Route::view('footer-fixed', 'page-layout.footer-fixed')->name('footer-fixed');
});

Route::prefix('project')->group(function () {
    Route::view('projects', 'project.projects')->name('projects');
    Route::view('projectcreate', 'project.projectcreate')->name('projectcreate');
});

Route::view('file-manager', 'file-manager')->name('file-manager');
Route::view('kanban', 'kanban')->name('kanban');

Route::prefix('ecommerce')->group(function () {
    Route::view('product', 'apps.product')->name('product');
    Route::view('page-product', 'apps.product-page')->name('product-page');
    Route::view('list-products', 'apps.list-products')->name('list-products');
    Route::view('payment-details', 'apps.payment-details')->name('payment-details');
    Route::view('order-history', 'apps.order-history')->name('order-history');
    Route::view('invoice-template', 'apps.invoice-template')->name('invoice-template');
    Route::view('cart', 'apps.cart')->name('cart');
    Route::view('list-wish', 'apps.list-wish')->name('list-wish');
    Route::view('checkout', 'apps.checkout')->name('checkout');
    Route::view('pricing', 'apps.pricing')->name('pricing');
});

Route::prefix('email')->group(function () {
    Route::view('email-application', 'apps.email-application')->name('email-application');
    Route::view('email-compose', 'apps.email-compose')->name('email-compose');
});

Route::prefix('chat')->group(function () {
    Route::view('chat', 'apps.chat')->name('chat');
    Route::view('video-chat', 'apps.video-chat')->name('chat-video');
});

Route::prefix('users')->group(function () {
    Route::view('user-profile', 'apps.user-profile')->name('user-profile');
    Route::view('edit-profile', 'apps.edit-profile')->name('edit-profile');
    Route::view('user-cards', 'apps.user-cards')->name('user-cards');
});


Route::view('bookmark', 'apps.bookmark')->name('bookmark');
Route::view('contacts', 'apps.contacts')->name('contacts');
Route::view('calendar-basic', 'apps.calendar-basic')->name('calendar-basic');
Route::view('task', 'apps.task')->name('task');
Route::view('social-app', 'apps.social-app')->name('social-app');
Route::view('to-do', 'apps.to-do')->name('to-do');
Route::view('search', 'apps.search')->name('search');

Route::prefix('ui-kits')->group(function () {
    Route::view('state-color', 'ui-kits.state-color')->name('state-color');
    Route::view('typography', 'ui-kits.typography')->name('typography');
    Route::view('avatars', 'ui-kits.avatars')->name('avatars');
    Route::view('helper-classes', 'ui-kits.helper-classes')->name('helper-classes');
    Route::view('grid', 'ui-kits.grid')->name('grid');
    Route::view('tag-pills', 'ui-kits.tag-pills')->name('tag-pills');
    Route::view('progress-bar', 'ui-kits.progress-bar')->name('progress-bar');
    Route::view('modal', 'ui-kits.modal')->name('modal');
    Route::view('alert', 'ui-kits.alert')->name('alert');
    Route::view('popover', 'ui-kits.popover')->name('popover');
    Route::view('tooltip', 'ui-kits.tooltip')->name('tooltip');
    Route::view('loader', 'ui-kits.loader')->name('loader');
    Route::view('dropdown', 'ui-kits.dropdown')->name('dropdown');
    Route::view('accordion', 'ui-kits.accordion')->name('accordion');
    Route::view('tab-bootstrap', 'ui-kits.tab-bootstrap')->name('tab-bootstrap');
    Route::view('tab-material', 'ui-kits.tab-material')->name('tab-material');
    Route::view('box-shadow', 'ui-kits.box-shadow')->name('box-shadow');
    Route::view('list', 'ui-kits.list')->name('list');
});

Route::prefix('bonus-ui')->group(function () {
    Route::view('scrollable', 'bonus-ui.scrollable')->name('scrollable');
    Route::view('tree', 'bonus-ui.tree')->name('tree');
    Route::view('bootstrap-notify', 'bonus-ui.bootstrap-notify')->name('bootstrap-notify');
    Route::view('rating', 'bonus-ui.rating')->name('rating');
    Route::view('dropzone', 'bonus-ui.dropzone')->name('dropzone');
    Route::view('tour', 'bonus-ui.tour')->name('tour');
    Route::view('sweet-alert2', 'bonus-ui.sweet-alert2')->name('sweet-alert2');
    Route::view('modal-animated', 'bonus-ui.modal-animated')->name('modal-animated');
    Route::view('owl-carousel', 'bonus-ui.owl-carousel')->name('owl-carousel');
    Route::view('ribbons', 'bonus-ui.ribbons')->name('ribbons');
    Route::view('pagination', 'bonus-ui.pagination')->name('pagination');
    Route::view('breadcrumb', 'bonus-ui.breadcrumb')->name('breadcrumb');
    Route::view('range-slider', 'bonus-ui.range-slider')->name('range-slider');
    Route::view('image-cropper', 'bonus-ui.image-cropper')->name('image-cropper');
    Route::view('sticky', 'bonus-ui.sticky')->name('sticky');
    Route::view('basic-card', 'bonus-ui.basic-card')->name('basic-card');
    Route::view('creative-card', 'bonus-ui.creative-card')->name('creative-card');
    Route::view('tabbed-card', 'bonus-ui.tabbed-card')->name('tabbed-card');
    Route::view('dragable-card', 'bonus-ui.dragable-card')->name('dragable-card');
    Route::view('timeline-v-1', 'bonus-ui.timeline-v-1')->name('timeline-v-1');
    Route::view('timeline-v-2', 'bonus-ui.timeline-v-2')->name('timeline-v-2');
    Route::view('timeline-small', 'bonus-ui.timeline-small')->name('timeline-small');
});

Route::prefix('builders')->group(function () {
    Route::view('form-builder-1', 'builders.form-builder-1')->name('form-builder-1');
    Route::view('form-builder-2', 'builders.form-builder-2')->name('form-builder-2');
    Route::view('pagebuild', 'builders.pagebuild')->name('pagebuild');
    Route::view('button-builder', 'builders.button-builder')->name('button-builder');
});

Route::prefix('animation')->group(function () {
    Route::view('animate', 'animation.animate')->name('animate');
    Route::view('scroll-reval', 'animation.scroll-reval')->name('scroll-reval');
    Route::view('aos', 'animation.aos')->name('aos');
    Route::view('tilt', 'animation.tilt')->name('tilt');
    Route::view('wow', 'animation.wow')->name('wow');
});


Route::prefix('icons')->group(function () {
    Route::view('flag-icon', 'icons.flag-icon')->name('flag-icon');
    Route::view('font-awesome', 'icons.font-awesome')->name('font-awesome');
    Route::view('ico-icon', 'icons.ico-icon')->name('ico-icon');
    Route::view('themify-icon', 'icons.themify-icon')->name('themify-icon');
    Route::view('feather-icon', 'icons.feather-icon')->name('feather-icon');
    Route::view('whether-icon', 'icons.whether-icon')->name('whether-icon');
    Route::view('simple-line-icon', 'icons.simple-line-icon')->name('simple-line-icon');
    Route::view('material-design-icon', 'icons.material-design-icon')->name('material-design-icon');
    Route::view('pe7-icon', 'icons.pe7-icon')->name('pe7-icon');
    Route::view('typicons-icon', 'icons.typicons-icon')->name('typicons-icon');
    Route::view('ionic-icon', 'icons.ionic-icon')->name('ionic-icon');
});

Route::prefix('buttons')->group(function () {
    Route::view('buttons', 'buttons.buttons')->name('buttons');
    Route::view('flat-buttons', 'buttons.flat-buttons')->name('flat-buttons');
    Route::view('edge-buttons', 'buttons.buttons-edge')->name('buttons-edge');
    Route::view('raised-button', 'buttons.raised-button')->name('raised-button');
    Route::view('button-group', 'buttons.button-group')->name('button-group');
});

Route::prefix('forms')->group(function () {
    Route::view('form-validation', 'forms.form-validation')->name('form-validation');
    Route::view('base-input', 'forms.base-input')->name('base-input');
    Route::view('radio-checkbox-control', 'forms.radio-checkbox-control')->name('radio-checkbox-control');
    Route::view('input-group', 'forms.input-group')->name('input-group');
    Route::view('megaoptions', 'forms.megaoptions')->name('megaoptions');
    Route::view('datepicker', 'forms.datepicker')->name('datepicker');
    Route::view('time-picker', 'forms.time-picker')->name('time-picker');
    Route::view('datetimepicker', 'forms.datetimepicker')->name('datetimepicker');
    Route::view('daterangepicker', 'forms.daterangepicker')->name('daterangepicker');
    Route::view('touchspin', 'forms.touchspin')->name('touchspin');
    Route::view('select2', 'forms.select2')->name('select2');
    Route::view('switch', 'forms.switch')->name('switch');
    Route::view('typeahead', 'forms.typeahead')->name('typeahead');
    Route::view('clipboard', 'forms.clipboard')->name('clipboard');
    Route::view('default-form', 'forms.default-form')->name('default-form');
    Route::view('form-wizard', 'forms.form-wizard')->name('form-wizard');
    Route::view('form-two-wizard', 'forms.form-wizard-two')->name('form-wizard-two');
    Route::view('wizard-form-three', 'forms.form-wizard-three')->name('form-wizard-three');
    Route::post('form-wizard-three', function () {
        return redirect()->route('form-wizard-three');
    })->name('form-wizard-three-post');
    Route::view('parentReg', 'forms.form-wizard-three')->name('form-wizard-three');
    Route::post('form-wizard-three', function () {
        return redirect()->route('form-wizard-three');
    })->name('form-wizard-three-post');
});

Route::prefix('tables')->group(function () {
    Route::view('bootstrap-basic-table', 'tables.bootstrap-basic-table')->name('bootstrap-basic-table');
    Route::view('bootstrap-sizing-table', 'tables.bootstrap-sizing-table')->name('bootstrap-sizing-table');
    Route::view('bootstrap-border-table', 'tables.bootstrap-border-table')->name('bootstrap-border-table');
    Route::view('bootstrap-styling-table', 'tables.bootstrap-styling-table')->name('bootstrap-styling-table');
    Route::view('table-components', 'tables.table-components')->name('table-components');
    Route::view('datatable-basic-init', 'tables.datatable-basic-init')->name('datatable-basic-init');
    Route::view('datatable-advance', 'tables.datatable-advance')->name('datatable-advance');
    Route::view('datatable-styling', 'tables.datatable-styling')->name('datatable-styling');
    Route::view('datatable-ajax', 'tables.datatable-ajax')->name('datatable-ajax');
    Route::view('datatable-server-side', 'tables.datatable-server-side')->name('datatable-server-side');
    Route::view('datatable-plugin', 'tables.datatable-plugin')->name('datatable-plugin');
    Route::view('datatable-api', 'tables.datatable-api')->name('datatable-api');
    Route::view('datatable-data-source', 'tables.datatable-data-source')->name('datatable-data-source');
    Route::view('datatable-ext-autofill', 'tables.datatable-ext-autofill')->name('datatable-ext-autofill');
    Route::view('datatable-ext-basic-button', 'tables.datatable-ext-basic-button')->name('datatable-ext-basic-button');
    Route::view('datatable-ext-col-reorder', 'tables.datatable-ext-col-reorder')->name('datatable-ext-col-reorder');
    Route::view('datatable-ext-fixed-header', 'tables.datatable-ext-fixed-header')->name('datatable-ext-fixed-header');
    Route::view('datatable-ext-html-5-data-export', 'tables.datatable-ext-html-5-data-export')->name('datatable-ext-html-5-data-export');
    Route::view('datatable-ext-key-table', 'tables.datatable-ext-key-table')->name('datatable-ext-key-table');
    Route::view('datatable-ext-responsive', 'tables.datatable-ext-responsive')->name('datatable-ext-responsive');
    Route::view('datatable-ext-row-reorder', 'tables.datatable-ext-row-reorder')->name('datatable-ext-row-reorder');
    Route::view('datatable-ext-scroller', 'tables.datatable-ext-scroller')->name('datatable-ext-scroller');
    Route::view('jsgrid-table', 'tables.jsgrid-table')->name('jsgrid-table');
});

Route::prefix('charts')->group(function () {
    Route::view('echarts', 'charts.echarts')->name('echarts');
    Route::view('chart-apex', 'charts.chart-apex')->name('chart-apex');
    Route::view('chart-google', 'charts.chart-google')->name('chart-google');
    Route::view('chart-sparkline', 'charts.chart-sparkline')->name('chart-sparkline');
    Route::view('chart-flot', 'charts.chart-flot')->name('chart-flot');
    Route::view('chart-knob', 'charts.chart-knob')->name('chart-knob');
    Route::view('chart-morris', 'charts.chart-morris')->name('chart-morris');
    Route::view('chartjs', 'charts.chartjs')->name('chartjs');
    Route::view('chartist', 'charts.chartist')->name('chartist');
    Route::view('chart-peity', 'charts.chart-peity')->name('chart-peity');
});

Route::view('sample-page', 'pages.sample-page')->name('sample-page');
Route::view('internationalization', 'pages.internationalization')->name('internationalization');

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

Route::prefix('authentication')->group(function () {
    Route::view('login', 'authentication.login')->name('login');
    Route::view('login-one', 'authentication.login-one')->name('login-one');
    Route::view('login-two', 'authentication.login-two')->name('login-two');
    Route::view('login-bs-validation', 'authentication.login-bs-validation')->name('login-bs-validation');
    Route::view('login-bs-tt-validation', 'authentication.login-bs-tt-validation')->name('login-bs-tt-validation');
    Route::view('login-sa-validation', 'authentication.login-sa-validation')->name('login-sa-validation');
    Route::view('sign-up', 'authentication.sign-up')->name('sign-up');
    Route::view('sign-up-one', 'authentication.sign-up-one')->name('sign-up-one');
    Route::view('sign-up-two', 'authentication.sign-up-two')->name('sign-up-two');
    Route::view('sign-up-wizard', 'authentication.sign-up-wizard')->name('sign-up-wizard');
    Route::view('unlock', 'authentication.unlock')->name('unlock');
    Route::view('forget-password', 'authentication.forget-password')->name('forget-password');
    Route::view('reset-password', 'authentication.reset-password')->name('reset-password');
    Route::view('maintenance', 'authentication.maintenance')->name('maintenance');
});

Route::view('comingsoon', 'comingsoon.comingsoon')->name('comingsoon');
Route::view('comingsoon-bg-video', 'comingsoon.comingsoon-bg-video')->name('comingsoon-bg-video');
Route::view('comingsoon-bg-img', 'comingsoon.comingsoon-bg-img')->name('comingsoon-bg-img');

Route::view('basic-template', 'email-templates.basic-template')->name('basic-template');
Route::view('email-header', 'email-templates.email-header')->name('email-header');
Route::view('template-email', 'email-templates.template-email')->name('template-email');
Route::view('template-email-2', 'email-templates.template-email-2')->name('template-email-2');
Route::view('ecommerce-templates', 'email-templates.ecommerce-templates')->name('ecommerce-templates');
Route::view('email-order-success', 'email-templates.email-order-success')->name('email-order-success');


Route::prefix('gallery')->group(function () {
    Route::view('index', 'apps.gallery')->name('gallery');
    Route::view('with-gallery-description', 'apps.gallery-with-description')->name('gallery-with-description');
    Route::view('gallery-masonry', 'apps.gallery-masonry')->name('gallery-masonry');
    Route::view('masonry-gallery-with-disc', 'apps.masonry-gallery-with-disc')->name('masonry-gallery-with-disc');
    Route::view('gallery-hover', 'apps.gallery-hover')->name('gallery-hover');
});

Route::prefix('blog')->group(function () {
    Route::view('index', 'apps.blog')->name('blog');
    Route::view('blog-single', 'apps.blog-single')->name('blog-single');
    Route::view('add-post', 'apps.add-post')->name('add-post');
});


Route::view('faq', 'apps.faq')->name('faq');

Route::prefix('job-search')->group(function () {
    Route::view('job-cards-view', 'apps.job-cards-view')->name('job-cards-view');
    Route::view('job-list-view', 'apps.job-list-view')->name('job-list-view');
    Route::view('job-details', 'apps.job-details')->name('job-details');
    Route::view('job-apply', 'apps.job-apply')->name('job-apply');
});

Route::prefix('learning')->group(function () {
    Route::view('learning-list-view', 'apps.learning-list-view')->name('learning-list-view');
    Route::view('learning-detailed', 'apps.learning-detailed')->name('learning-detailed');
});

Route::prefix('maps')->group(function () {
    Route::view('map-js', 'apps.map-js')->name('map-js');
    Route::view('vector-map', 'apps.vector-map')->name('vector-map');
});

Route::prefix('editors')->group(function () {
    Route::view('summernote', 'apps.summernote')->name('summernote');
    Route::view('ckeditor', 'apps.ckeditor')->name('ckeditor');
    Route::view('simple-mde', 'apps.simple-mde')->name('simple-mde');
    Route::view('ace-code-editor', 'apps.ace-code-editor')->name('ace-code-editor');
});

Route::view('knowledgebase', 'apps.knowledgebase')->name('knowledgebase');
Route::view('support-ticket', 'apps.support-ticket')->name('support-ticket');
Route::view('landing-page', 'pages.landing-page')->name('landing-page');

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

Route::put('/update-mother/{id}', [AdminDashboardController::class, 'updateMotherInfo'])->name('update.motherInfo');
Route::put('/update-father/{id}', [AdminDashboardController::class, 'updateFatherInfo'])->name('update.fatherInfo');
Route::put('/update-child/{id}', [AdminDashboardController::class, 'updateChildInfo'])->name('update.childInfo');
Route::put('/update-account/{id}', [AdminDashboardController::class, 'updateParentAccount'])->name('update.parentAccount');


Route::post('/addTherapist', [AdminDashboardController::class, 'addNewTherapist'])->name('addNewTherapist');
Route::post('/updateTherapist', [AdminDashboardController::class, 'updateTherapist'])->name('updateTherapist');

Route::post('/addCs', [AdminDashboardController::class, 'addNewCs'])->name('addNewCs');
Route::post('/updateCs', [AdminDashboardController::class, 'updateCs'])->name('updateCs');

Route::post('/addSales', [AdminDashboardController::class, 'addNewSales'])->name('addNewSales');
Route::post('/updateSales', [AdminDashboardController::class, 'updateSales'])->name('updateSales');


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
