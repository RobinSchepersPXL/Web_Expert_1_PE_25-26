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
    /**
     * Only authenticated users may access create/store/edit/update/destroy/toggleFavorite.
     * Index and show remain public.
     */
    public function __construct()
    {
        $this->middleware('auth')->only([
            'create', 'store', 'edit', 'update', 'destroy', 'toggleFavorite'
        ]);
    }

    /**
     * Public overview.
     * - Guests: latest() order
     * - Logged-in users: favorites are shown on top (handled in view via favoriteIds)
     */
    public function index()
    {
        $events = Event::query()->latest()->get();

        $favoriteIds = [];
        if (Auth::check() && Schema::hasTable('favorites')) {
            $favoriteIds = DB::table('favorites')
                ->where('user_id', Auth::id())
                ->pluck('event_id')
                ->all();
        }

        return view('events.index', compact('events', 'favoriteIds'));
    }

    /**
     * Public detail page.
     * Shows event + tickets (only if tickets table AND event_id column exist) + favorite status.
     */
    public function show(Event $event)
    {
        $tickets = collect();

        if (Schema::hasTable('tickets') && Schema::hasColumn('tickets', 'event_id')) {
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

    /**
     * Only admins may create events (enforced by policy).
     */
    public function create()
    {
        $this->authorize('create', Event::class);

        return view('events.create');
    }

    /**
     * Store event (admin-only via policy).
     */
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
            'images.*' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
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
            ->with('status', 'Event successfully created!');
    }

    /**
     * Only admin-owner may edit (policy update).
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    /**
     * Update event (admin-owner via policy update).
     * Supports deleting existing images and uploading new ones.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'new_images' => ['nullable', 'array', 'max:10'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['string'],
        ]);

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'capacity' => $validated['capacity'] ?? null,
            'price' => $validated['price'] ?? null,
        ]);

        $currentImages = $event->images ?? [];

        // Delete selected images
        if (!empty($validated['delete_images'])) {
            foreach ($validated['delete_images'] as $imagePath) {
                if (in_array($imagePath, $currentImages)) {
                    Storage::disk('public')->delete($imagePath);
                    $currentImages = array_values(array_diff($currentImages, [$imagePath]));
                }
            }
        }

        // Upload new images
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $currentImages[] = $file->store('events', 'public');
            }
        }

        $event->images = $currentImages;
        $event->save();

        return redirect()
            ->route('events.show', $event)
            ->with('status', 'Event successfully updated!');
    }

    /**
     * Delete event (admin-owner via policy delete).
     * Deletes associated images + tickets/favorites rows if present.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        foreach ($event->images ?? [] as $imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        // Only delete tickets if table + column exist
        if (Schema::hasTable('tickets') && Schema::hasColumn('tickets', 'event_id')) {
            DB::table('tickets')->where('event_id', $event->id)->delete();
        }

        if (Schema::hasTable('favorites')) {
            DB::table('favorites')->where('event_id', $event->id)->delete();
        }

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('status', 'Event successfully deleted!');
    }

    /**
     * Toggle favorite (AJAX).
     * Only authenticated users.
     */
    public function toggleFavorite(Event $event)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!Schema::hasTable('favorites')) {
            return response()->json(['message' => 'Favorites table missing'], 500);
        }

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

            return response()->json([
                'favorited' => false,
                'event_id' => $event->id,
            ], 200);
        }

        DB::table('favorites')->insert([
            'user_id' => $userId,
            'event_id' => $event->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'favorited' => true,
            'event_id' => $event->id,
        ], 200);
    }
}
