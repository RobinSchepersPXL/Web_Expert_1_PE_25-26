<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TicketsController extends Controller
{
    private function cols(): array
    {
        // Bepaal kolomnamen op basis van bestaande schema
        $priceCol = Schema::hasColumn('tickets', 'prijs') ? 'prijs' : (Schema::hasColumn('tickets', 'price') ? 'price' : null);
        $availableCol = Schema::hasColumn('tickets', 'beschikbare_aantal') ? 'beschikbare_aantal' : (Schema::hasColumn('tickets', 'available') ? 'available' : null);
        $reservedCol = Schema::hasColumn('tickets', 'gereserveerd_aantal') ? 'gereserveerd_aantal' : (Schema::hasColumn('tickets', 'reserved') ? 'reserved' : null);
        $categoryCol = Schema::hasColumn('tickets', 'categorie') ? 'categorie' : (Schema::hasColumn('tickets', 'category') ? 'category' : null);

        return [$priceCol, $availableCol, $reservedCol, $categoryCol];
    }

    public function create(Event $event)
    {
        $this->authorize('update', $event);
        return view('tickets.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        [$priceCol, $availableCol, $reservedCol, $categoryCol] = $this->cols();

        if (!$priceCol || !$availableCol || !$reservedCol) {
            return redirect()->back()->withErrors('Tickets tabel mist vereiste kolommen (prijs/available/reserved).');
        }

        $validated = $request->validate([
            'prijs' => ['required', 'numeric', 'min:0'],
            'beschikbare_aantal' => ['required', 'integer', 'min:1'],
            'categorie' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'event_id' => $event->id,
            $priceCol => $validated['prijs'],
            $availableCol => $validated['beschikbare_aantal'],
            $reservedCol => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($categoryCol) {
            $data[$categoryCol] = $validated['categorie'] ?? null;
        }

        DB::table('tickets')->insert($data);

        return redirect()->route('events.show', $event)
            ->with('status', 'Ticket succesvol aangemaakt!');
    }
}
