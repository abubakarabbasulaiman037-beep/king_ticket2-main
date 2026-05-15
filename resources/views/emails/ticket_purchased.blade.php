@component('mail::message')
# Your ticket is ready

Thanks for your purchase. Attached is your ticket PDF including a QR code for entry.

@component('mail::button', ['url' => url('/tickets/'.$ticket->id)])
View Ticket
@endcomponent

Thanks,
<br>KingTicket
@endcomponent
