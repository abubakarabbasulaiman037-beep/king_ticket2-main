<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;

class TicketPurchased extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        $subject = 'Your ticket for ' . $this->ticket->event->title;
        return $this->subject($subject)
            ->view('emails.ticket_purchased')
            ->attach(storage_path('app/public/' . $this->ticket->pdf_path));
    }
}
