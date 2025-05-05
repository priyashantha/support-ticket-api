<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reference' => $this->ref_id,
            'status' => $this->ticket_status,
            'customer_name' => $this->cus_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'content' => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
            'replies' => $this->replies->map(function ($reply) {
                return [
                    'message' => $reply->message,
                    'replied_by' => $reply->agent->name ?? 'Unknown',
                    'timestamp' => $reply->created_at->toDateTimeString(),
                ];
            }),
        ];
    }
}
