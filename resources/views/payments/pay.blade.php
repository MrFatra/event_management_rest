@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran')

@section('content')
    <div class="container mx-auto px-6 mt-12 mb-20">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl border border-border p-8 md:p-12">
                <div class="text-center mb-10">
                    <div
                        class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6 text-primary">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="5" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                    </div>
                    <h1 class="font-heading text-3xl font-bold mb-2">Selesaikan Pembayaran</h1>
                    <p class="text-text-muted">Langkah terakhir sebelum Anda resmi terdaftar di event kami.</p>
                </div>

                <div class="space-y-6 mb-10">
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-text-muted font-medium">Event</span>
                            <span
                                class="font-bold text-text-main text-right">{{ $payment->registration->event->title }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-text-muted font-medium">Order ID</span>
                            <span
                                class="font-mono text-sm font-bold bg-white px-3 py-1 rounded-lg border border-slate-200">{{ $payment->order_id }}</span>
                        </div>
                        <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                            <span class="text-lg font-bold">Total Bayar</span>
                            <span class="text-2xl font-black text-primary">Rp
                                {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <button id="pay-button"
                        class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-hover shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7"></path>
                        </svg>
                        Bayar Sekarang via Midtrans
                    </button>
                    <a href="{{ route('profile.tickets') }}"
                        class="w-full py-4 bg-white text-text-muted rounded-2xl font-bold text-center hover:bg-slate-50 border border-border transition-all">
                        Bayar Nanti (Cek di Tiket Saya)
                    </a>
                </div>

                <div class="mt-12 pt-8 border-t border-border flex items-center justify-center gap-6">
                    <img src="https://dashboard-assets.midtrans.com/assets/logo/midtrans-dark-3a5ac77cd3110b28b32cb590fc968f296d2123e686591d636bd51b276f6ed034.svg"
                        alt="Midtrans Secure" class="h-6 opacity-60">
                    <div class="h-4 w-px bg-slate-300"></div>
                    <p class="text-[10px] text-text-muted font-bold uppercase tracking-widest">Secure Payment by Midtrans
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        payButton.onclick = function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    window.location.href = "{{ route('profile.tickets') }}?success=Pembayaran berhasil!";
                },
                onPending: function (result) {
                    window.location.href = "{{ route('profile.tickets') }}?info=Pembayaran sedang diproses.";
                },
                onError: function (result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function () {
                    alert('Anda menutup popup sebelum menyelesaikan pembayaran');
                }
            });
        };
    </script>
@endpush