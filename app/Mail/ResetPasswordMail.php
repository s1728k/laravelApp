<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $precord;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($precord)
    {
        $this->precord = $precord;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $table = 'App\\User';
        $record = $table::where('email', $this->precord->email)->first();
        $urlpath = '/'.$record->id.'?hash="'.$this->precord->token.'"';
        return $this->view('cb.auth.passwords.password-reset-mail')->with(['urlpath' => $urlpath]);
    }
}
