<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyParticipantMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $name;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $name, $token)
    {
        $this->title = $title;
        $this->name = $name;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tes ' . $this->title)->view('participant.email.verify-email', [ 'name' => $this->name, 'token' => $this->token ]);
    }
}
