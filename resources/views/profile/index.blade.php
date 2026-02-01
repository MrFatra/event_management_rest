@extends('layouts.app')

@section('title', 'Manajemen Profil')

@section('content')
    <div class="container mx-auto px-6 mt-16 mb-24">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 items-start">
            <!-- Sidebar Navigation -->
            <x-sidebar-user :user="$user" />

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white p-10 rounded-3xl border border-border shadow-xl shadow-slate-100">
                    <h2 class="font-heading text-3xl font-bold mb-8 text-text-main">Data Pribadi</h2>

                    @if($errors->any())
                        <div class="mb-8 p-6 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 text-sm">
                            <ul class="list-disc list-inside space-y-1 font-medium">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-10">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-text-main mb-3">Nama Lengkap</label>
                                <input type="text" name="name"
                                    class="w-full bg-slate-50 border border-border rounded-2xl px-6 py-4 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-text-main mb-3">Alamat Email</label>
                                <input type="email" name="email"
                                    class="w-full bg-slate-50 border border-border rounded-2xl px-6 py-4 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="pt-10 border-t border-border">
                            <h3 class="font-heading text-xl font-bold mb-2">Ubah Password</h3>
                            <p class="text-text-muted text-sm mb-8">Hanya isi bagian di bawah jika Anda bermaksud mengganti
                                password keamanan.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-sm font-bold text-text-main mb-3">Password Baru</label>
                                    <input type="password" name="password"
                                        class="w-full bg-slate-50 border border-border rounded-2xl px-6 py-4 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                                        placeholder="Min 8 karakter">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-text-main mb-3">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full bg-slate-50 border border-border rounded-2xl px-6 py-4 outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                                        placeholder="Ulangi password">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="bg-primary text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-primary-hover shadow-lg shadow-indigo-100 transition-all active:scale-95">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection