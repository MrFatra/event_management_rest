@extends('layouts.app')

@section('title', 'Tiket Saya')

@section('content')
    <div class="container mx-auto px-6 mt-16 mb-24">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 items-start">
            <!-- Sidebar Navigation -->
            <x-sidebar-user :user="$user" />

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <h2 class="font-heading text-3xl font-bold mb-10 text-text-main">Riwayat Tiket & Transaksi</h2>

                @if($registrations->isEmpty())
                    <div class="bg-white p-20 rounded-3xl border-2 border-dashed border-border text-center">
                        <div class="mb-8 inline-flex p-8 bg-slate-50 rounded-full text-slate-300">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-text-main mb-3">Belum ada pesanan tiket.</h3>
                        <p class="text-text-muted mb-10 max-w-sm mx-auto">Ayo temukan event menarik untuk meningkatkan keahlian
                            Anda!</p>
                        <a href="{{ route('events.index') }}"
                            class="inline-flex items-center px-8 py-4 bg-primary text-white rounded-2xl font-bold hover:bg-primary-hover shadow-md shadow-indigo-100 transition-all active:scale-95">Cari
                            Event Sekarang</a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($registrations as $reg)
                            <div
                                class="bg-white p-6 rounded-3xl border border-border shadow-sm hover:shadow-md transition-all duration-300 flex flex-col md:flex-row items-center gap-8">
                                <div
                                    class="w-full md:w-[140px] h-[140px] shrink-0 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                                    @if($reg->event->poster)
                                        <img src="{{ asset('storage/' . $reg->event->poster) }}" alt="{{ $reg->event->title }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="grow text-center md:text-left">
                                    <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-3">
                                        <span
                                            class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-bold tracking-widest">
                                            @if($reg->payment)
                                                #{{ $reg->payment->order_id }}
                                            @else
                                                #{{ $reg->id }}
                                            @endif
                                        </span>
                                        @php
                                            $statusStyles = match ($reg->status) {
                                                'registered' => 'bg-emerald-50 text-emerald-600 ring-emerald-600/20',
                                                'pending' => 'bg-amber-50 text-amber-600 ring-amber-600/20',
                                                'cancelled' => 'bg-rose-50 text-rose-600 ring-rose-600/20',
                                                default => 'bg-slate-50 text-slate-600 ring-slate-600/20'
                                            };
                                        @endphp
                                        <span
                                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest ring-1 ring-inset {{ $statusStyles }}">
                                            {{ $reg->status }}
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold mb-2 hover:text-primary transition-colors">
                                        <a href="{{ route('events.show', $reg->event->id) }}">{{ $reg->event->title }}</a>
                                    </h3>
                                    <div class="flex items-center justify-center md:justify-start gap-4 text-text-muted text-sm">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($reg->event->start_date)->format('d M Y') }}
                                        </span>
                                        <span>&bull;</span>
                                        <span class="font-medium">{{ substr($reg->event->start_time, 0, 5) }} WIB</span>
                                    </div>
                                </div>
                                <div
                                    class="text-center md:text-right shrink-0 pt-4 md:pt-0 border-t md:border-t-0 md:pl-8 border-border w-full md:w-auto">
                                    <div class="text-2xl font-extrabold text-text-main mb-6 py-2">
                                        {{ $reg->event->price > 0 ? 'Rp ' . number_format($reg->event->price, 0, ',', '.') : 'GRATIS' }}
                                    </div>
                                    <a href="{{ route('events.show', $reg->event->id) }}"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-primary border-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover transition-colors shadow-sm">Lihat
                                        Detail Tiket <span>&#8250;</span></a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $registrations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection