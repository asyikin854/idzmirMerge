<?php

namespace App\Http\Controllers;

use DB;
use App\Models\EmailLog;

use Illuminate\Http\Request;
use App\Mail\CsEmailMailable;
use App\Mail\YourEmailMailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;



class EmailController extends Controller
{
    
    // Show compose email form
    public function compose()
    {
        $parents = DB::table('parent_accounts')->get();
        return view('admin.email.send', compact('parents')); // Pointing to the send.blade.php view
    }

    // Handle sending of email
public function send(Request $request)
{
    $request->validate([
        'to' => 'required|array',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    $emails = $request->input('to');
    $subject = $request->input('subject');
    $messageBody = $request->input('message');

   try {
    // Use Laravel's Queueable Mailable class to send emails
    foreach ($emails as $email) {
        Mail::to($email)->queue(new YourEmailMailable($subject, $messageBody));

        // Log success
        DB::table('email_logs')->insert([
            'recipient' => $email,
            'subject' => $subject,
            'message' => $messageBody,
            'status' => 'success',
            'error' => '',  // Add an empty string for the error field
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} catch (\Exception $e) {
    // Log failure
    DB::table('email_logs')->insert([
        'recipient' => $email,
        'subject' => $subject,
        'message' => $messageBody,
        'status' => 'failed',
        'error' => $e->getMessage(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    \Log::error("Failed to send email: " . $e->getMessage());
}
return redirect()->back()->with('success', 'Email sent successfully.');

}



    // Display inbox with all notifications/messages sent to the user
    public function inbox()
    {
        // Assume logged-in user is a parent
        $userEmail = auth()->user()->email;

        // Fetch notifications sent to the logged-in user (adjust the table/logic as necessary)
        $messages = DB::table('notifications')->where('email', $userEmail)->get();

        return view('inbox', compact('messages'));
    }

    public function csCompose()
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        $parents = DB::table('parent_accounts')->get();
        return view('sendEmail-cs', compact('parents', 'csName')); // Pointing to the send.blade.php view
    }

    public function csSend(Request $request)
    {
        $request->validate([
            'to' => 'required|array',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
    
        $emails = $request->input('to');
        $subject = $request->input('subject');
        $messageBody = $request->input('message');
    
       try {
        // Use Laravel's Queueable Mailable class to send emails
        foreach ($emails as $email) {
            Mail::to($email)->queue(new CsEmailMailable($subject, $messageBody));
    
            // Log success
            DB::table('email_logs')->insert([
                'recipient' => $email,
                'subject' => $subject,
                'message' => $messageBody,
                'status' => 'success',
                'error' => '',  // Add an empty string for the error field
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    } catch (\Exception $e) {
        // Log failure
        DB::table('email_logs')->insert([
            'recipient' => $email,
            'subject' => $subject,
            'message' => $messageBody,
            'status' => 'failed',
            'error' => $e->getMessage(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        \Log::error("Failed to send email: " . $e->getMessage());
    }
    return redirect()->back()->with('success', 'Email sent successfully.');
    
    }
    public function csInbox()
    {
        $user = Auth::guard('cs')->user();
        $csInfo = $user;
        $csName = $user->name;
        // Assume logged-in user is a parent

        // Fetch notifications sent to the logged-in user (adjust the table/logic as necessary)
        $messages = DB::table('notifications')->get();

        return view('inbox-cs', compact('messages', 'csName'));
    }

    
    public function parentInbox()
    {
        // Get the logged-in parent account
        $user = Auth::guard('parent')->user();
    $parentAccount = $user->childInfo->parentAccount;

    // Fetch only the email logs (or announcements) for the parentAccount's email
    $messages = EmailLog::where('recipient', $parentAccount->email)->get();

    // Select the first message by default if no specific message is selected
    $selectedMessage = $messages->first();
    
        return view('announcement-parent', compact('messages', 'parentAccount', 'selectedMessage'));
    }

    public function fetchMessage(Request $request)
{
    // Fetch the message by ID
    $message = EmailLog::find($request->id);

    if (!$message) {
        return response()->json(['error' => 'Message not found'], 404);
    }

    // Return a partial view with message details
    return view('messageDetails-parent', compact('message'))->render();
}

    
}
