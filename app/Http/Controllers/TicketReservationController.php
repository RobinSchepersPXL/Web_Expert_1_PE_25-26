<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketReservationController extends Controller
{
    public function store(Request $request, int $ticketId)
    {
        if (!Auth::check()) {
            return redirect()->back()->withErrors('Je moet ingelogd zijn om tickets te reserveren.');
        }

        $request->validate([
            'aantal' => ['required', 'integer', 'min:1'],
        ]);

        $ticket = DB::table('tickets')->where('id', $ticketId)->first();

        if (!$ticket) {
            return redirect()->back()->withErrors('Ticket niet gevonden.');
        }

        // Beschikbaarheid berekenen
        $beschikbaar = $ticket->beschikbare_aantal - $ticket->gereserveerd_aantal;

        if ($beschikbaar < $request->aantal) {
            return redirect()->back()->withErrors('Niet genoeg tickets beschikbaar.');
        }

        DB::transaction(function () use ($ticket, $request) {

            // gereserveerd_aantal verhogen
            DB::table('tickets')
                ->where('id', $ticket->id)
                ->update([
                    'gereserveerd_aantal' => $ticket->gereserveerd_aantal + $request->aantal,
                ]);

            // reservatie opslaan
            DB::table('ticket_reservations')->insert([
                'user_id' => Auth::id(),
                'ticket_id' => $ticket->id,
                'aantal' => $request->aantal,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });


        return redirect()->back()->with('status', 'Tickets succesvol gereserveerd!');
    }
}
