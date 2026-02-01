@extends('layouts.app')

@section('title', 'Explore Event')

@section('content')
    <div class="bg-[radial-gradient(circle_at_top_right,var(--color-blue-100)_0%,#ffffff_100%)] py-16">
        <div class="container mx-auto px-6">
            <h1 class="font-heading text-4xl font-bold mb-8">Cari Event yang Sesuai Untuk Anda</h1>

            <form action="{{ route('events.index') }}" method="GET"
                class="bg-white p-8 rounded-premium shadow-lg grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-semibold mb-2 text-text-main">Search</label>
                    <div class="relative">
                        <input type="text" name="search"
                            class="w-full bg-slate-50 border border-border rounded-premium px-4 py-2.5 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                            placeholder="Cari judul event..." value="{{ request('search') }}">
                        <svg class="w-5 h-5 absolute right-3 top-2.5 text-text-muted" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-text-main">Kategori</label>
                    <select name="category"
                        class="w-full bg-slate-50 border border-border rounded-premium px-4 py-2.5 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all appearance-none">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-text-main">Tipe</label>
                    <select name="type"
                        class="w-full bg-slate-50 border border-border rounded-premium px-4 py-2.5 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all appearance-none">
                        <option value="">Semua Tipe</option>
                        <option value="seminar" {{ request('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="webinar" {{ request('type') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                        <option value="workshop" {{ request('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                    </select>
                </div>

                <button type="submit"
                    class="bg-primary text-white font-bold h-[46px] rounded-premium hover:bg-primary-hover shadow-sm transition-all active:scale-95">Terapkan
                    Filter</button>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-6 mt-16">
        @if($events->isEmpty())
            <div class="text-center py-20 bg-white rounded-premium border border-dashed border-border">
                <div class="mb-6 inline-flex p-6 bg-slate-50 rounded-full">
                    <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-text-main mb-2">Maaf, event tidak ditemukan.</h3>
                <p class="text-text-muted mb-8 max-w-md mx-auto">Silakan coba kata kunci atau filter lainnya untuk menemukan
                    event yang Anda cari.</p>
                <a href="{{ route('events.index') }}"
                    class="inline-flex items-center px-6 py-3 border border-border rounded-premium font-semibold hover:bg-slate-50 transition-colors">
                    Reset Semua Filter
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @foreach($events as $event)
                    <div
                        class="bg-white rounded-premium border border-border overflow-hidden group hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
                        <div class="h-[200px] bg-slate-200 overflow-hidden relative">
                            @if($event->poster)
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    No Poster Available
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <span
                                class="inline-block px-3 py-1 bg-indigo-50 text-primary rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                                {{ $event->category->name }}
                            </span>
                            <h3 class="text-xl font-bold mb-3 line-clamp-2 leading-tight">{{ $event->title }}</h3>
                            <div class="flex gap-4 text-text-muted text-sm mb-6">
                                <span class="flex items-center gap-1.5">
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                </span>
                                <span class="text-slate-300">&bull;</span>
                                <span
                                    class="font-medium text-slate-600">{{ ucfirst($event->event_type->value ?? $event->event_type) }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-5 border-t border-border">
                                <span class="text-lg font-bold text-text-main">
                                    {{ $event->price > 0 ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'Gratis' }}
                                </span>
                                <a href="{{ route('events.show', $event->id) }}"
                                    class="px-4 py-2 rounded-premium border border-border text-sm font-semibold hover:bg-slate-50 transition-colors">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center mb-20 pagination-tailwind">
                <x-pagination :paginator="$events" />
            </div>
        @endif
    </div>
@endsection