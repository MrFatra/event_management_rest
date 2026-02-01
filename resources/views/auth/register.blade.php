@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
    <div class="min-h-[90vh] flex items-center bg-slate-50 py-16 px-6">
        <div class="w-full max-w-[500px] mx-auto">
            <div
                class="bg-white p-10 rounded-3xl shadow-2xl shadow-indigo-100 border border-border transition-all hover:shadow-indigo-200/50">
                <div class="text-center mb-10">
                    <h1 class="font-heading text-3xl font-bold text-text-main mb-2">Buat Akun Baru</h1>
                    <p class="text-text-muted text-sm px-4">Lengkapi data Anda untuk mulai mendaftar berbagai event pilihan.
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 text-sm">
                        <ul class="list-disc list-inside space-y-1 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-text-main mb-2">Nama Lengkap</label>
                        <input type="text" name="name"
                            class="w-full bg-slate-50 border border-border rounded-2xl px-5 py-3.5 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                            placeholder="John Doe" value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-text-main mb-2">Email Address</label>
                        <input type="email" name="email"
                            class="w-full bg-slate-50 border border-border rounded-2xl px-5 py-3.5 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                            placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-text-main mb-2">Password</label>
                            <input type="password" name="password"
                                class="w-full bg-slate-50 border border-border rounded-2xl px-5 py-3.5 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                                placeholder="Min 8 karakter" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-text-main mb-2">Konfirmasi</label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-slate-50 border border-border rounded-2xl px-5 py-3.5 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                                placeholder="Ulangi" required>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-hover shadow-lg shadow-indigo-200 transition-all active:scale-95">Daftar
                            Akun</button>
                    </div>
                </form>

                <div class="text-center mt-10 text-sm text-text-muted">
                    Sudah memiliki akun? <a href="{{ route('login') }}"
                        class="font-bold text-primary hover:underline decoration-2 underline-offset-4">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
@endsection