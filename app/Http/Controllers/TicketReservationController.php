<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketReservationController extends Controller
{
    /**
     * Reservatie opslaan:
     * - check beschikbaarheid (beschikbare_aantal - gereserveerd_aantal)
     * - verhoog gereserveerd_aantal
     * - insert in ticket_reservations (user_id, ticket_id, aantal)
     */
    public function store(Request $request, int $ticket)
    {
        if (!Auth::check()) {
            return redirect()->back()->withErrors('Je moet ingelogd zijn om te reserveren.');
        }

        $validated = $request->validate([
            'aantal' => ['required', 'integer', 'min:1'],
        ]);

        try {
            DB::transaction(function () use ($ticket, $validated) {

                // Lock ticket row om overselling te vermijden (basis correctness)
                $t = DB::table('tickets')
                    ->where('id', $ticket)
                    ->lockForUpdate()
                    ->first();

                if (!$t) {
                    throw new \RuntimeException('Ticket niet gevonden.');
                }

                $beschikbaar = (int)$t->beschikbare_aantal - (int)$t->gereserveerd_aantal;

                if ($beschikbaar < (int)$validated['aantal']) {
                    throw new \RuntimeException('Niet genoeg tickets beschikbaar.');
                }

                // Update gereserveerd
                DB::table('tickets')
                    ->where('id', $ticket)
                    ->update([
                        'gereserveerd_aantal' => (int)$t->gereserveerd_aantal + (int)$validated['aantal'],
                        'updated_at' => now(),
                    ]);

                // Insert reservatie
                DB::table('ticket_reservations')->insert([
                    'user_id' => Auth::id(),
                    'ticket_id' => $ticket,
                    'aantal' => (int)$validated['aantal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->back()->with('status', 'Reservatie succesvol!');
    }
}
