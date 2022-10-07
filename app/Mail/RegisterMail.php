<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function build()
    {
        return $this->from('kareemhussen500@gmail.com' , 'Trade Coins')
            ->subject($this->data['subject'])->view('email')
            ->with('data' , $this->data);
    }
}
