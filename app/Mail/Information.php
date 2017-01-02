<?php

namespace App\Mail;

use App\Pojo\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class Information extends Mailable
{
    use Queueable, SerializesModels;


    public $Data;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->Data =$message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject($this->Data->getTitle());
        if(empty($this->Data->getAttach()))
            return $this->view('mail.layout');

        return $this->view('mail.layout')
            ->attachData(Storage::get($this->Data->getAttach()),'termo.pdf',[
                'mime' => 'application/pdf',
            ]);

    }
}
