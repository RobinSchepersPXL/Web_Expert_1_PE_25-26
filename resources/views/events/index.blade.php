@extends('layouts.app')

@section('content')
    @php
        // View toggle: list | calendar
        $viewMode = request('view', 'list');
        if (!in_array($viewMode, ['list', 'calendar'])) $viewMode = 'list';

        // Calendar month (YYYY-MM)
        $month = request('month', now()->format('Y-m'));
        try {
            $monthCarbon = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Exception $e) {
            $monthCarbon = now()->startOfMonth();
            $month = $monthCarbon->format('Y-m');
        }

        $prevMonth = $monthCarbon->copy()->subMonth()->format('Y-m');
        $nextMonth = $monthCarbon->copy()->addMonth()->format('Y-m');

        // Favorites helper
        $favoriteIds = $favoriteIds ?? [];
        $favoriteSet = array_flip($favoriteIds);

        // group events by start_date date for calendar view
        $eventsByDate = [];
        foreach ($events as $ev) {
            $d = optional($ev->start_date)->format('Y-m-d');
            if (!$d) continue;
            if (!isset($eventsByDate[$d])) $eventsByDate[$d] = [];
            $eventsByDate[$d][] = $ev; // keep original order (no extra sorting)
        }

        // calendar grid helpers
        $firstDayOfMonth = $monthCarbon->copy();
        $startOfGrid = $firstDayOfMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfGrid = $firstDayOfMonth->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);

        // preserve filters in links
        $baseParams = [
            'q' => request('q'),
            'location' => request('location'),
            'date' => request('date'),
        ];
    @endphp

    <div class="container mx-auto px-4 py-8 max-w-6xl">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Events</h1>

            @can('create', App\Models\Event::class)
                <a href="{{ route('events.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    + Nieuw event
                </a>
            @endcan
        </div>

        {{-- SEARCH FORM (basis) --}}
        <form method="GET" action="{{ route('events.index') }}"
              class="bg-white shadow rounded p-4 mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">

            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Zoek op titel"
                   class="border rounded px-3 py-2">

            <input type="text"
                   name="location"
                   value="{{ request('location') }}"
                   placeholder="Zoek op locatie"
                   class="border rounded px-3 py-2">

            <input type="date"
                   name="date"
                   value="{{ request('date') }}"
                   class="border rounded px-3 py-2">

            {{-- keep view/month when searching --}}
            <input type="hidden" name="view" value="{{ $viewMode }}">
            <input type="hidden" name="month" value="{{ $month }}">

            <button class="bg-gray-900 hover:bg-gray-800 text-white rounded px-4 py-2">
                Zoeken
            </button>

            <a href="{{ route('events.index') }}"
               class="text-center border rounded px-4 py-2 hover:bg-gray-50">
                Reset
            </a>
        </form>

        {{-- VIEW TOGGLE --}}
        <div class="flex items-center gap-2 mb-6">
            <a href="{{ route('events.index', array_filter(array_merge($baseParams, ['view' => 'list']))) }}"
               class="px-3 py-2 rounded border {{ $viewMode === 'list' ? 'bg-gray-900 text-white' : 'hover:bg-gray-50' }}">
                Lijst
            </a>

            <a href="{{ route('events.index', array_filter(array_merge($baseParams, ['view' => 'calendar', 'month' => $month]))) }}"
               class="px-3 py-2 rounded border {{ $viewMode === 'calendar' ? 'bg-gray-900 text-white' : 'hover:bg-gray-50' }}">
                Kalender
            </a>
        </div>

        {{-- LIST VIEW --}}
        @if($viewMode === 'list')
            @if($events->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($events as $event)
                        @php
                            $isFav = auth()->check() && isset($favoriteSet[$event->id]);
                            $images = $event->images ?? [];
                            if (is_string($images)) $images = json_decode($images, true) ?? [];
                            $cover = count($images) ? asset('storage/'.$images[0]) : null;
                        @endphp

                        <a href="{{ route('events.show', $event) }}"
                           class="block bg-white shadow rounded overflow-hidden hover:shadow-md transition">
                            @if($cover)
                                <img src="{{ $cover }}" alt="cover" class="w-full h-40 object-cover">
                            @endif

                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <h2 class="text-xl font-bold">{{ $event->title }}</h2>

                                    @auth
                                        @if($isFav)
                                            <span class="text-yellow-600 font-bold text-lg" title="Favoriet">★</span>
                                        @endif
                                    @endauth
                                </div>

                                <div class="text-sm text-gray-600 mt-2">
                                    📍 {{ $event->location }} <br>
                                    🗓️ {{ optional($event->start_date)->format('d/m/Y H:i') }}
                                </div>

                                <p class="text-gray-700 mt-3 line-clamp-3">
                                    {{ $event->description }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Geen events gevonden.</p>
            @endif
        @endif

        {{-- CALENDAR VIEW --}}
        @if($viewMode === 'calendar')
            <div class="bg-white shadow rounded p-4 mb-4 flex items-center justify-between">
                <div class="font-bold text-lg">
                    {{ $monthCarbon->format('F Y') }}
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('events.index', array_filter(array_merge($baseParams, ['view' => 'calendar', 'month' => $prevMonth]))) }}"
                       class="px-3 py-2 border rounded hover:bg-gray-50">
                        ←
                    </a>
                    <a href="{{ route('events.index', array_filter(array_merge($baseParams, ['view' => 'calendar', 'month' => $nextMonth]))) }}"
                       class="px-3 py-2 border rounded hover:bg-gray-50">
                        →
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-2 text-xs font-semibold text-gray-600 mb-2">
                <div>Ma</div><div>Di</div><div>Wo</div><div>Do</div><div>Vr</div><div>Za</div><div>Zo</div>
            </div>

            <div class="grid grid-cols-7 gap-2">
                @php $cursor = $startOfGrid->copy(); @endphp
                @while($cursor->lte($endOfGrid))
                    @php
                        $dayKey = $cursor->format('Y-m-d');
                        $inMonth = $cursor->month === $monthCarbon->month;
                        $dayEvents = $eventsByDate[$dayKey] ?? [];
                    @endphp

                    <div class="border rounded p-2 min-h-[90px] {{ $inMonth ? 'bg-white' : 'bg-gray-50' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-bold {{ $inMonth ? 'text-gray-900' : 'text-gray-400' }}">
                                {{ $cursor->day }}
                            </div>
                        </div>

                        @foreach($dayEvents as $ev)
                            @php
                                $isFav = auth()->check() && isset($favoriteSet[$ev->id]);
                            @endphp
                            <a href="{{ route('events.show', $ev) }}"
                               class="block text-xs rounded px-2 py-1 mb-1 border hover:bg-gray-50">
                                <span class="font-semibold">{{ \Illuminate\Support\Str::limit($ev->title, 20) }}</span>
                                @auth
                                    @if($isFav)
                                        <span class="text-yellow-600 font-bold"> ★</span>
                                    @endif
                                @endauth
                            </a>
                        @endforeach
                    </div>

                    @php $cursor->addDay(); @endphp
                @endwhile
            </div>

            @if(!$events->count())
                <p class="text-gray-600 mt-4">Geen events gevonden.</p>
            @endif
        @endif

    </div>
@endsection
