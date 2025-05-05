<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyFormRequest;
use App\Http\Requests\TicketFormRequest;
use App\Http\Resources\TicketResource;
use App\Mail\TicketCreatedNotification;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TicketsController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        // 🔍 Search by customer name (case-insensitive)
        if ($search = $request->input('search')) {
            $query->where('cus_name', 'like', "%{$search}%");
        }

        // 🆕 Load replies count for UI, and paginate
        $tickets = $query
            ->withCount('replies')
            ->orderByDesc('created_at')
            ->paginate(10); // ← can make per-page configurable

        return TicketResource::collection($tickets);
    }

    public function showAgent($reference)
    {
        $ticket = Ticket::with(['replies.agent:id,name,email'])
            ->where('ref_id', $reference)
            ->first();

        if (! $ticket) {
            return response()->json([
                'message' => 'Ticket not found'
            ], 404);
        }

        // Update ticket status to 'open' if it's 'new'
        if ($ticket->ticket_status === 'new') {
            $ticket->update(['ticket_status' => 'opened']);
        }

        return new TicketResource($ticket);
    }

    public function reply(ReplyFormRequest $request, $reference)
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::where('ref_id', $reference)->first();

        if (! $ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }
//        echo '<pre>'.print_r($ticket, 2);die();

        $reply = $ticket->replies()->create([
            'message' => $request->message,
            'agent_id' => auth()->id(),
        ]);

        $ticket->update(['ticket_status' => 'replied']);

        return response()->json([
            'message' => 'Reply added successfully.',
            'reply' => [
                'message' => $reply->message,
                'replied_by' => auth()->user()->name,
                'timestamp' => $reply->created_at->toDateTimeString()
            ]
        ]);
    }

    public function store(TicketFormRequest $request)
    {
        $data = $request->validated();
//        dd($data);

        do {
            $reference = Str::uuid();
        } while (Ticket::where('ref_id', $reference)->exists());

        $data['ref_id'] = $reference;

        $ticket = Ticket::create($data);

        Mail::to($ticket->email)->send(new TicketCreatedNotification($ticket));

        return response()->json([
            'message' => 'Ticket submitted successfully.',
            'reference' => $ticket->ref_id
        ], 201);
    }

    public function show($reference)
    {
        $ticket = Ticket::with(['replies.agent:id,name,email'])
            ->where('ref_id', $reference)
            ->first();

        if (! $ticket) {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }

        return new TicketResource($ticket);
    }
}
