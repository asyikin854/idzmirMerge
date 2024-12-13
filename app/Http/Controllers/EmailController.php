<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use App\Mail\CsEmailMailable;
use App\Models\ParentAccount;
use App\Mail\YourEmailMailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;


class EmailController extends Controller
{
    
    // Show compose email form
    public function compose()
    {
        $parents = DB::table('parent_accounts')->get();
        return view('admin.email.send', compact('parents')); // Pointing to the send.blade.php view
    }

    public function send(Request $request)
    {
        $request->validate([
            'to' => 'nullable|array',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
    
        $emails = $request->input('to') ?? $this->getAllParentEmails();
        $subject = $request->input('subject');
        $messageBody = $request->input('message');
        $uploadedFiles = []; // Renamed variable
    
        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '.' . $file->extension(); // Generate a unique file name
                $path = public_path('attachments'); // Define the path to `public/attachments`
                $file->move($path, $fileName); // Move the file to `public/attachments`
                $uploadedFiles[] = asset('attachments/' . $fileName); // Store the accessible URL
            }
        }
    
        try {
            foreach ($emails as $email) {
                // Send email with attachments
                Mail::to($email)->send(new YourEmailMailable($subject, $messageBody, $uploadedFiles));
    
                // Log success
                DB::table('email_logs')->insert([
                    'recipient' => $email,
                    'subject' => $subject,
                    'message' => $messageBody,
                    'attachments' => json_encode($uploadedFiles),
                    'status' => 'success',
                    'error' => '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Log failure
            DB::table('email_logs')->insert([
                'recipient' => $email ?? 'N/A',
                'subject' => $subject,
                'message' => $messageBody,
                'attachments' => json_encode($uploadedFiles),
                'status' => 'failed',
                'error' => $e->getMessage(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            \Log::error("Failed to send email: " . $e->getMessage());
        }
    
        return redirect()->back()->with('success', 'Email sent successfully.');
    }
    

// {
//     $request->validate([
//         'to' => 'nullable|array',
//         'to.*' => 'email',
//         'subject' => 'required|string|max:255',
//         'message' => 'required|string',
//         'attachment.*' => 'file|max:10240', // Max 10MB per file
//     ]);

//     // Determine recipients
//     $recipients = $request->to ?? ParentAccount::pluck('email')->toArray(); // Use provided emails or fetch from ParentAccount model

//     if (empty($recipients)) {
//         return redirect()->back()->with('error', 'No recipients found.');
//     }

//     // Prepare attachments
//     $attachments = [];
//     if ($request->hasFile('attachment')) {
//         foreach ($request->file('attachment') as $file) {
//             $attachments[] = [
//                 'filePath' => $file->getRealPath(),
//                 'filename' => $file->getClientOriginalName(),
//             ];
//         }
//     }

//     // Send email to each recipient
//     $mg = Mail::create(env('MAILGUN_API_KEY')); // Mailgun instance
//     $domain = env('MAILGUN_DOMAIN');

//     foreach ($recipients as $recipient) {
//         $messageData = [
//             'from' => 'admin@example.com',
//             'to' => $recipient,
//             'subject' => $request->subject,
//             'text' => $request->message,
//         ];

//         // Include attachments if present
//         if (!empty($attachments)) {
//             $messageData['attachment'] = array_map(function ($attachment) {
//                 return [
//                     'filePath' => $attachment['filePath'],
//                     'filename' => $attachment['filename'],
//                 ];
//             }, $attachments);
//         }

//         // Send email via Mailgun
//         try {
//             $mg->messages()->send($domain, $messageData);
//         } catch (\Exception $e) {
//             // Log or handle errors appropriately
//             return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
//         }
//     }

//     return redirect()->back()->with('success', 'Email(s) sent successfully.');
// }


// Display inbox with all notifications/messages sent to the user
private function getAllParentEmails()
{
    return ParentAccount::pluck('email')->toArray();
}


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
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $csInfo = $user;
        $csName = $user->name;
        $csEmail = $user->email;
        $parents = DB::table('parent_accounts')->get();
        return view('sendEmail-cs', compact('parents', 'csName', 'csEmail')); // Pointing to the send.blade.php view
    }

    public function csSend(Request $request)
    {
        $request->validate([
            'to' => 'nullable|array',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',

        ]);
    
        $emails = $request->input('to') ?? $this->getAllParentEmails();
        $subject = $request->input('subject');
        $messageBody = $request->input('message');
        $uploadedFiles = []; // Renamed variable
    
        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '.' . $file->extension(); // Generate a unique file name
                $path = public_path('attachments'); // Define the path to `public/attachments`
                $file->move($path, $fileName); // Move the file to `public/attachments`
                $uploadedFiles[] = asset('attachments/' . $fileName); // Store the accessible URL
            }
        }
       try {
        // Use Laravel's Queueable Mailable class to send emails
        foreach ($emails as $email) {
            Mail::to($email)->send(new CsEmailMailable($subject, $messageBody, $uploadedFiles));
    
            // Log success
            DB::table('email_logs')->insert([
                'recipient' => $email,
                'subject' => $subject,
                'message' => $messageBody,
                'attachments' => json_encode($uploadedFiles),
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
            'attachments' => json_encode($uploadedFiles),
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
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
        $csInfo = $user;
        $csName = $user->name;
        // Assume logged-in user is a parent

        // Fetch notifications sent to the logged-in user (adjust the table/logic as necessary)
        $messages = DB::table('email_logs')->get();

        return view('inbox-cs', compact('messages', 'csName'));
    }

    
    public function parentInbox()
    {
        // Get the logged-in parent account
        $user = Auth::guard('parent')->user();
        if (!$user) {
            return Redirect::route('login')->with('error', 'The session has expired. Please log back into your account.');
        }
    $parentAccount = $user->childInfo->parentAccount;

    // Fetch only the email logs (or announcements) for the parentAccount's email
    $messages = EmailLog::where('recipient', $parentAccount->email)->orderBy('created_at', 'desc')->get();

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


public function sentAdmin()
{
    $messages = EmailLog::orderBy('created_at', 'desc')->get();

    return view('admin.email.sent', ['messages' => $messages]);
}


public function fetchEmailAdmin(Request $request)
{
    // Fetch the message by ID
    $message = EmailLog::find($request->id);

    if (!$message) {
        return response()->json(['error' => 'Message not found'], 404);
    }

    // Return a partial view with message details
    return view('admin.email.fetch', compact('message'))->render();
}


    
}
