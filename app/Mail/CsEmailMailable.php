<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class YourEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $messageBody;

    public function __construct($subject, $messageBody)
    {
        $this->subject = $subject;
        $this->messageBody = $messageBody;
    }

    public function build()
    {
        return $this->view('admin.email.template')
                    ->subject($this->subject)
                    ->with([
                        'subject' => $this->subject,
                        'messageBody' => $this->messageBody,
                    ]);
    }
}