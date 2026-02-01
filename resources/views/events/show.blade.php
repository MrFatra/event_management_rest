@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <div class="container mx-auto px-6 mt-12 mb-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2">
                <div class="h-[400px] bg-slate-200 rounded-3xl overflow-hidden mb-10 shadow-lg relative group">
                    @if($event->poster)
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}"
                            class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full flex items-center justify-center text-slate-400 text-2xl font-heading shadow-inner">
                            No Banner Image
                        </div>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 p-6 bg-linear-to-t from-black/50 to-transparent">
                        <span
                            class="px-4 py-1.5 bg-white text-primary rounded-full text-sm font-bold uppercase tracking-widest shadow-sm">
                            {{ $event->category->name }}
                        </span>
                    </div>
                </div>

                <div class="mb-12">
                    <h1 class="font-heading text-4xl font-bold mb-6 tracking-tight">{{ $event->title }}</h1>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-border shadow-sm">
                            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-text-muted font-bold uppercase tracking-widest">Tanggal</div>
                                <div class="font-bold text-sm">
                                    {{ \Carbon\Carbon::parse($event->start_date)->locale('id')->translatedFormat('d F Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-border shadow-sm">
                            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-text-muted font-bold uppercase tracking-widest">Waktu</div>
                                <div class="font-bold text-sm">{{ substr($event->start_time, 0, 5) }} -
                                    {{ substr($event->end_time, 0, 5) }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-border shadow-sm">
                            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-text-muted font-bold uppercase tracking-widest">Lokasi</div>
                                <div class="font-bold text-sm line-clamp-1">
                                    {{ $event->is_online ? 'Online' : $event->location }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="prose prose-slate max-w-none">
                        <h3 class="font-heading text-2xl font-bold mb-6 text-text-main">Tentang Event Ini</h3>
                        <div class="text-slate-600 leading-relaxed text-lg">
                            {!! $event->description !!}
                        </div>
                    </div>
                </div>

                @if($event->speakers->isNotEmpty())
                    <div class="mb-12">
                        <h3 class="font-heading text-2xl font-bold mb-8 text-text-main">Pembicara Ahli</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($event->speakers as $speaker)
                                <div
                                    class="flex items-start gap-4 p-6 bg-white rounded-2xl border border-border shadow-sm hover:border-primary/30 transition-colors">
                                    <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 shadow-md">
                                        <img src="{{ $speaker->photo ? asset('storage/' . $speaker->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($speaker->name) }}"
                                            alt="{{ $speaker->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg mb-1">{{ $speaker->name }}</h4>
                                        <p class="text-text-muted text-sm leading-snug">{{ $speaker->bio }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($event->benefits)
                    <div class="mb-12">
                        <h3 class="font-heading text-2xl font-bold mb-6 text-text-main">Benefit & Fasilitas</h3>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 p-8 bg-slate-50 rounded-3xl border border-slate-200">
                            @foreach($event->benefits as $benefit)
                                <div class="flex items-center gap-3">
                                    <div
                                        class="shrink-0 w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-slate-700">{{ $benefit['benefit'] ?? $benefit }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Card Registration -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 flex flex-col gap-5">
                    <div class="bg-white rounded-3xl p-8 border border-border shadow-2xl ">
                        <div class="text-[10px] text-text-muted font-bold uppercase tracking-[0.2em] mb-3">Biaya Registrasi
                        </div>
                        <div class="text-4xl font-extrabold text-primary mb-8 font-heading">
                            {{ $event->price > 0 ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'GRATIS' }}
                        </div>

                        @auth
                            @if (!$registration)
                                <form action="{{ route('events.register', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-hover shadow-lg shadow-indigo-200 transition-all active:scale-95 hover:scale-105 mb-4 cursor-pointer">
                                        Daftar Sekarang
                                    </button>
                                </form>
                            @elseif($registration->status === 'pending')
                                <form action="{{ route('payments.pay', $registration->id) }}" method="GET">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-hover shadow-lg shadow-indigo-200 transition-all active:scale-95 hover:scale-105 mb-4 cursor-pointer">
                                        Bayar Sekarang
                                    </button>
                                </form>
                                <form action="{{ route('events.cancel', $registration->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-4 bg-red-500 text-white rounded-2xl font-bold text-lg hover:bg-red-600 shadow-lg shadow-red-200 transition-all active:scale-95 hover:scale-105 mb-4 cursor-pointer">
                                        Batalkan Pendaftaran
                                    </button>
                                </form>
                            @elseif($registration->status === 'registered')
                                <button type="submit"
                                    class="w-full py-4 bg-primary/50 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-200 mb-4 cursor-not-allowed">
                                    Sudah Terdaftar
                                </button>
                            @elseif($registration->status === 'cancelled')
                                <button type="submit"
                                    class="w-full py-4 bg-primary/50 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-200 mb-4 cursor-not-allowed">
                                    Dibatalkan
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="w-full block text-center py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-hover shadow-lg shadow-indigo-200 transition-all active:scale-95 mb-4">
                                Mulai Mendaftar</a>
                        @endauth

                        <p class="text-xs text-text-muted text-center leading-relaxed">
                            Akses penuh materi workshop bersertifikat resmi dari EventHub.
                        </p>

                        <div class="mt-8 pt-8 border-t border-border space-y-6">
                            <div>
                                <div class="font-bold text-xs uppercase tracking-wider text-text-main mb-2">Penyelenggara
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-primary">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700">EventHub Official</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-bold text-xs uppercase tracking-wider text-text-main mb-2">Tipe Acara</div>
                                <div
                                    class="inline-flex px-4 py-1.5 bg-indigo-50 text-primary border border-indigo-100 rounded-full text-xs font-bold uppercase tracking-wider">
                                    {{ ucfirst($event->event_type->value ?? $event->event_type) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($payment)
                        <div class="bg-white rounded-3xl p-8 border border-border shadow-2xl flex flex-col gap-3">
                            <p class="text-center text-lg font-bold">Tiket Anda:</p>
                            <div class="text-center text-4xl font-extrabold font-heading">
                                {{ $payment->order_id }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection