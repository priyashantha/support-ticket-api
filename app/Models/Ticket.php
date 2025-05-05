<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_id',
        'cus_name',
        'email',
        'phone_number',
        'content',
        'ticket_status',
    ];

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
