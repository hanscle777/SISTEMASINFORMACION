<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClienteReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $subjectLine;
    public $messageBody;

    public function __construct(User $client, string $subjectLine, string $messageBody)
    {
        $this->client = $client;
        $this->subjectLine = $subjectLine;
        $this->messageBody = $messageBody;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.clients.reminder');
    }
}
