<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\RedactaUser;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $link;

    public function __construct(RedactaUser $user, string $link, string $expiration)
    {
        $this->user = $user;
        $this->link = $link;
        $this->expiration = $expiration;
    }

    public function build()
    {
        return $this->subject('Restablecer contraseña de su cuenta en RedActa')
                    ->view('emails.password-reset')
                    ->with([
                        'name' => $this->user->name,
                        'link' => $this->link,
                        'expiration' => $this->expiration,
                    ]);
    }
}