<?php

use Illuminate\Support\Facades\Route;
use App\Models\Ticket;
use App\Mail\TicketCreatedNotification;

Route::get('/test-mail', function () {
    $ticket = Ticket::latest()->first();
//    echo '<pre>'.print_r($ticket, 1);die();
    return new TicketCreatedNotification($ticket);
});

Route::get('/', function () {
    return view('welcome');
});
