<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketsController extends Controller
{
    /**
     * Form: ticket toevoegen voor event
     * Alleen admin/owner (via EventPolicy update)
     */
    public function create(Event $event)
    {
        $this->authorize('update', $event);

        return view('tickets.create', compact('event'));
    }

    /**
     * Ticket opslaan
     * Kolommen: event_id, prijs, beschikbare_aantal, gereserveerd_aantal, categorie
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'prijs' => ['required', 'numeric', 'min:0'],
            'beschikbare_aantal' => ['required', 'integer', 'min:1'],
            'categorie' => ['nullable', 'string', 'max:255'],
        ]);

        DB::table('tickets')->insert([
            'event_id' => $event->id,
            'prijs' => $validated['prijs'],
            'beschikbare_aantal' => $validated['beschikbare_aantal'],
            'gereserveerd_aantal' => 0,
            'categorie' => $validated['categorie'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('events.show', $event)
            ->with('status', 'Ticket succesvol aangemaakt!');
    }

    /**
     * Form: ticket bewerken
     * Alleen admin/owner van het event van dit ticket
     */
    public function edit(int $ticket)
    {
        $ticketRow = DB::table('tickets')->where('id', $ticket)->first();
        abort_if(!$ticketRow, 404);

        $event = Event::findOrFail($ticketRow->event_id);
        $this->authorize('update', $event);

        return view('tickets.edit', ['ticket' => $ticketRow, 'event' => $event]);
    }

    /**
     * Ticket bijwerken
     * Basisregel: beschikbare_aantal mag niet lager dan gereserveerd_aantal
     */
    public function update(Request $request, int $ticket)
    {
        $ticketRow = DB::table('tickets')->where('id', $ticket)->first();
        abort_if(!$ticketRow, 404);

        $event = Event::findOrFail($ticketRow->event_id);
        $this->authorize('update', $event);

        $validated = $request->validate([
            'prijs' => ['required', 'numeric', 'min:0'],
            'beschikbare_aantal' => ['required', 'integer', 'min:1'],
            'categorie' => ['nullable', 'string', 'max:255'],
        ]);

        if ((int)$validated['beschikbare_aantal'] < (int)$ticketRow->gereserveerd_aantal) {
            return redirect()->back()->withErrors('Beschikbaar aantal kan niet lager zijn dan het gereserveerde aantal.');
        }

        DB::table('tickets')
            ->where('id', $ticket)
            ->update([
                'prijs' => $validated['prijs'],
                'beschikbare_aantal' => $validated['beschikbare_aantal'],
                'categorie' => $validated['categorie'] ?? null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('events.show', $event)
            ->with('status', 'Ticket succesvol bijgewerkt!');
    }

    /**
     * Ticket verwijderen
     * Alleen admin/owner van het event van dit ticket
     */
    public function destroy(int $ticket)
    {
        $ticketRow = DB::table('tickets')->where('id', $ticket)->first();
        abort_if(!$ticketRow, 404);

        $event = Event::findOrFail($ticketRow->event_id);
        $this->authorize('update', $event);

        DB::table('tickets')->where('id', $ticket)->delete();

        return redirect()
            ->route('events.show', $event)
            ->with('status', 'Ticket succesvol verwijderd!');
    }
}
