@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="min-h-[85vh] flex items-center bg-slate-50 py-12 px-6">
        <div class="w-full max-w-[450px] mx-auto">
            <div
                class="bg-white p-10 rounded-3xl shadow-2xl shadow-indigo-100 border border-border transition-all hover:shadow-indigo-200/50">
                <div class="text-center mb-10">
                    <h1 class="font-heading text-3xl font-bold text-text-main mb-2">Selamat Datang</h1>
                    <p class="text-text-muted text-sm px-4">Masuk untuk mengelola pendaftaran event dan melihat riwayat
                        tiket Anda.</p>
                </div>

                @if($errors->any())
                    <div
                        class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 text-sm font-medium animate-pulse">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-text-main mb-2">Email Address</label>
                        <input type="email" name="email"
                            class="w-full bg-slate-50 border border-border rounded-2xl px-5 py-3.5 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                            placeholder="nama@email.com" value="{{ old('email') }}" required autoFocus>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-bold text-text-main">Password</label>
                            <a href="#" class="text-xs font-bold text-primary hover:underline">Lupa Password?</a>
                        </div>
                        <input type="password" name="password"
                            class="w-full bg-slate-50 border border-border rounded-2xl px-5 py-3.5 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-hover shadow-lg shadow-indigo-200 transition-all active:scale-95">Masuk
                            Sekarang</button>
                    </div>
                </form>

                <div class="text-center mt-10 text-sm text-text-muted">
                    Belum memiliki akun? <a href="{{ route('register') }}"
                        class="font-bold text-primary hover:underline decoration-2 underline-offset-4">Daftar Akun Baru</a>
                </div>
            </div>
        </div>
    </div>
@endsection