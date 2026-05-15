<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchased;
use App\Models\Ticket;

class SendTicketEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $ticketId;

    public function __construct($ticketId)
    {
        $this->ticketId = $ticketId;
    }

    public function handle()
    {
        $ticket = Ticket::with('user','event')->find($this->ticketId);
        if (!$ticket || !$ticket->user) return;

        Mail::to($ticket->user->email)->queue(new TicketPurchased($ticket));
    }
}
