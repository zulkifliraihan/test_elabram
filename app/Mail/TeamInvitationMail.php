<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $team, $invite;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($team, $invite)
    {
        $this->team = $team;
        $this->invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->subject("Invitation from {$this->team->name}")
        ->view('mail.invitation-team');
    }
}
