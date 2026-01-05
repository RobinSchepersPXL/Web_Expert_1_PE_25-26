<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct(string $token, string $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        $link = url('/password/reset/' . $this->token . '?email=' . urlencode($this->email));

        return $this->subject('Reset your password')
            ->view('emails.reset-password') // create a simple view `resources/views/emails/reset-password.blade.php`
            ->with(['link' => $link]);
    }
}
