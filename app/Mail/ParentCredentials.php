<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParentCredentials extends Mailable
{
use Queueable, SerializesModels;

    public $parent_username;
    public $parent_password;

    public function __construct($parent_username, $parent_password)
    {
        $this->parent_username = $parent_username;
        $this->parent_password = $parent_password;
    }

    public function build()
    {
        return $this->subject('Your Parent Account Credentials')
                    ->view('emails.parent_credentials')
                    ->with([
                        'username' => $this->parent_username,
                        'password' => $this->parent_password,
                    ]);
    }
}
