<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class EventsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only([
            'create', 'store', 'edit', 'update', 'destroy', 'toggleFavorite'
        ]);
    }

    /**
     * Events overview + SEARCH
     * - filter on title, location, date
     * - NO extra sorting (latest blijft standaard)
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // 🔍 Search filters (basis)
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('start_date', $request->date);
        }

        // Basis sortering
        $events = $query->latest()->get();

        // Favorieten voor ingelogde users
        $favoriteIds = [];
        if (Auth::check() && Schema::hasTable('favorites')) {
            $favoriteIds = DB::table('favorites')
                ->where('user_id', Auth::id())
                ->pluck('event_id')
                ->all();
        }

        return view('events.index', compact('events', 'favoriteIds'));
    }

    public function show(Event $event)
    {
        $tickets = collect();

        if (
            Schema::hasTable('tickets') &&
            Schema::hasColumn('tickets', 'event_id')
        ) {
            $tickets = DB::table('tickets')
                ->where('event_id', $event->id)
                ->orderBy('id')
                ->get();
        }

        $isFavorite = false;
        if (Auth::check() && Schema::hasTable('favorites')) {
            $isFavorite = DB::table('favorites')
                ->where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->exists();
        }

        return view('events.show', compact('event', 'tickets', 'isFavorite'));
    }

    public function create()
    {
        $this->authorize('create', Event::class);
        return view('events.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Event::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'max:5120'],
        ]);

        $paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('events', 'public');
            }
        }

        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'capacity' => $validated['capacity'] ?? null,
            'price' => $validated['price'] ?? null,
            'images' => $paths,
        ]);

        return redirect()
            ->route('events.show', $event)
            ->with('status', 'Event succesvol aangemaakt!');
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'location' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $event->update($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('status', 'Event bijgewerkt!');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        foreach ($event->images ?? [] as $img) {
            Storage::disk('public')->delete($img);
        }

        DB::table('tickets')->where('event_id', $event->id)->delete();
        DB::table('favorites')->where('event_id', $event->id)->delete();

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('status', 'Event verwijderd!');
    }

    public function toggleFavorite(Event $event)
    {
        $userId = Auth::id();

        $exists = DB::table('favorites')
            ->where('user_id', $userId)
            ->where('event_id', $event->id)
            ->exists();

        if ($exists) {
            DB::table('favorites')
                ->where('user_id', $userId)
                ->where('event_id', $event->id)
                ->delete();

            return response()->json(['favorited' => false]);
        }

        DB::table('favorites')->insert([
            'user_id' => $userId,
            'event_id' => $event->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['favorited' => true]);
    }
}
