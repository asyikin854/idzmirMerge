<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\YourEmailMailable;

use Illuminate\Support\Facades\Mail;
use DB;



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
}
