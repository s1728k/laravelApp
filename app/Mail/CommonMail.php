<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommonMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $message;
    protected $email_from;
    protected $from_name;
    protected $email_subject;
    protected $email_attach;
    protected $email_template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($obj)
    {
        $templates = ['common_mail' => 'common_mail'];
        $this->message = $obj['message'];
        $this->email_subject = $obj['subject']??'CommonMail';
        $this->from_name = $obj['from_name']??'HoneyWeb.Org';
        $this->email_from = $obj['from_email']??'no_reply@honeyweb.org';
        $this->email_attach = $obj['attach']??null;
        $this->email_template = $templates[$obj['template']??'common_mail']??'common_mail';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(!empty($this->email_attach)){
            return $this->from($this->email_from, $this->from_name)
                        ->subject($this->email_subject)
                        ->attach($this->email_attach)
                        ->view('cb.email_template.'.$this->email_template)
                        ->with(['obj' => $this->message]);
        }else{
            \Log::Info($this->message);
            return $this->from($this->email_from, $this->from_name)
                        ->subject($this->email_subject)
                        ->view('cb.email_template.'.$this->email_template)
                        ->with(['obj' => $this->message]);
        }
    }
}
