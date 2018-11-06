<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $rtype;
    protected $precord;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($rtype, $precord)
    {
        $this->rtype = $rtype;
        $this->precord = $precord;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $table = 'App\\'.ucwords(rtrim($this->rtype,'s'));
        $record = $table::where('email', $this->precord->email)->first();
        $urlpath = '/'.$this->rtype.'/'.$record->id.'?hash="'.$this->precord->token.'"';
        return $this->view('c.auth.passwords.password-reset-mail')->with(['rtype' => $this->rtype, 'urlpath' => $urlpath]);
    }
}
