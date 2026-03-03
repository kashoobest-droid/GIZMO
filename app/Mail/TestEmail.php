<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $bodyMessage;

    public function __construct(string $subjectLine, string $bodyMessage)
    {
        $this->subjectLine = $subjectLine;
        $this->bodyMessage = $bodyMessage;
        $this->subject($subjectLine);
    }

    public function build()
    {
        return $this->view('emails.test')->with(['message' => $this->bodyMessage]);
    }
}
