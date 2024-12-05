<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class YourEmailMailable extends Mailable
{
	use Queueable, SerializesModels;
	
    public $subject;
    public $messageBody;
    public $uploadedFiles;

    public function __construct($subject, $messageBody, $uploadedFiles)
    {
        $this->subject = $subject;
        $this->messageBody = $messageBody;
        $this->uploadedFiles = $uploadedFiles;
    }

public function build()
{
    $email = $this->view('admin.email.template')
                  ->subject($this->subject)
                  ->with([
                      'subject' => $this->subject,
                      'messageBody' => $this->messageBody,
                  ]);

    // Safely handle attachments
        foreach ($this->uploadedFiles as $file) {
            $email->attach($file);
        }
	
    return $email;
}
}
