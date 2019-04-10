<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('includes.mail', ['data' => $this->data["message"]])->subject($this->data["subject"]);

        if(isset($this->data['attachment']) && $this->data['attachment']){
            $email->attach($this->data['attachment']);
            //$email->attach('http://orient.debug/images/mock1.jpg');
        }

        return $email;
    }
}
