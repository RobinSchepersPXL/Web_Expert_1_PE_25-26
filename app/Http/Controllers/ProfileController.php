<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Profile page:
     * - user info
     * - reserved events (ticket_reservations -> tickets -> events)
     * - favorite events (favorites -> events)
     */
    public function show()
    {
        $user = Auth::user();

        // Events waarvoor user reservaties heeft
        $reservedEvents = DB::table('ticket_reservations')
            ->join('tickets', 'ticket_reservations.ticket_id', '=', 'tickets.id')
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->where('ticket_reservations.user_id', $user->id)
            ->select(
                'events.id as event_id',
                'events.title',
                'events.location',
                'events.start_date',
                'events.end_date',
                DB::raw('SUM(ticket_reservations.aantal) as totaal_aantal')
            )
            ->groupBy('events.id', 'events.title', 'events.location', 'events.start_date', 'events.end_date')
            ->orderBy('events.start_date', 'desc')
            ->get();

        // Favoriete events
        $favoriteEvents = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('favorites')) {
            $favoriteEvents = DB::table('favorites')
                ->join('events', 'favorites.event_id', '=', 'events.id')
                ->where('favorites.user_id', $user->id)
                ->select(
                    'events.id as event_id',
                    'events.title',
                    'events.location',
                    'events.start_date',
                    'events.end_date'
                )
                ->orderBy('events.start_date', 'desc')
                ->get();
        }

        return view('profile.show', compact('user', 'reservedEvents', 'favoriteEvents'));
    }

    /**
     * Update basic profile fields (name + email).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('profile.show')
            ->with('status', 'Profiel bijgewerkt!');
    }
}
