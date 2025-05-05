@component('mail::message')
    Hi {{ $ticket->cus_name }},

    Thank you for contacting our support team. We’ve successfully received your ticket.

    Ticket Reference: {{ $ticket->ref_id }}

    Our team will review your request and get back to you shortly.

    Best regards,
    Support
@endcomponent
