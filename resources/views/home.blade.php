@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="py-20 text-center bg-[radial-gradient(circle_at_top_right,var(--color-blue-100)_0%,#ffffff_100%)]">
        <div class="container mx-auto px-6">
            <h1 class="font-heading text-5xl md:text-6xl font-bold mb-4 tracking-tight">
                Platform Informasi Event Kampus Terpadu
            </h1>
            <p class="text-xl text-text-muted max-w-[600px] mx-auto mb-10">Temukan seminar, webinar, dan workshop terbaik.
                Tingkatkan skill dan network Anda disini.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.index') }}"
                    class="bg-primary text-white px-10 py-4 rounded-premium font-semibold text-lg hover:bg-primary-hover shadow-sm hover:shadow-lg transition-all active:scale-95">Explore
                    Event</a>
                @guest
                    <a href="{{ route('register') }}"
                        class="px-10 py-4 rounded-premium border border-border font-semibold text-lg hover:bg-slate-50 transition-colors">Daftar
                        Sekarang</a>
                @endguest
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 mt-20">
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
            <div>
                <h2 class="font-heading text-3xl font-bold mb-2">Event Terbaru</h2>
                <p class="text-text-muted">Jangan lewatkan kesempatan belajar dari yang terbaik.</p>
            </div>
            <a href="{{ route('events.index') }}"
                class="text-primary font-semibold hover:underline flex items-center gap-2">
                Lihat Semua <span>&#8250;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredEvents as $event)
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
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                {{ substr($event->start_time, 0, 5) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-5 border-t border-border">
                            <span class="text-lg font-bold text-text-main">
                                {{ $event->price > 0 ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'Gratis' }}
                            </span>
                            <a href="{{ route('events.show', $event->id) }}"
                                class="px-4 py-2 rounded-premium border border-border text-sm font-semibold hover:bg-slate-50 transition-colors">Detail
                                Event</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- <section class="bg-slate-900 text-white py-24 mt-24">
                                                        <div class="container mx-auto px-6 text-center">
                                                            <h2 class="font-heading text-4xl font-bold mb-6">Mulai Perjalanan Anda Bersama Kami</h2>
                                                            <p class="text-slate-400 max-w-[600px] mx-auto mb-12 text-lg">Gabung dengan komunitas ribuan orang yang terus
                                                                berkembang setiap harinya melalui berbagai event inspiratif.</p>
                                                            <a href="{{ route('register') }}"
                                                                class="bg-white text-slate-900 px-12 py-4 rounded-premium font-semibold text-lg hover:bg-slate-100 transition-colors shadow-lg hover:shadow-xl active:scale-95">Dapatkan
                                                                Tiket Pertama Anda</a>
                                                        </div>
                                                    </section> -->

@endsection