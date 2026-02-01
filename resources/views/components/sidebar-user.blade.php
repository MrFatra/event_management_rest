<div class="lg:col-span-1">
    <div class="bg-white rounded-3xl border border-border overflow-hidden shadow-xl shadow-slate-100">
        <div class="p-8 border-b border-border text-center bg-slate-50">
            <div
                class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center text-3xl font-extrabold mx-auto mb-4 shadow-lg ring-4 ring-white">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="font-bold text-xl text-text-main line-clamp-1 mb-1">{{ $user->name }}</div>
            <div class="text-text-muted text-xs font-medium uppercase tracking-widest">Bergabung
                {{ $user->created_at->format('M Y') }}
            </div>
        </div>
        <div class="p-4 space-y-2">
            <a href="{{ route('profile.index') }}"
                class="flex items-center gap-3 px-6 py-4 rounded-2xl font-bold transition-all group {{ request()->routeIs('profile.index') ? 'bg-indigo-50 text-primary' : 'text-text-muted hover:bg-slate-50' }}">
                <svg class="w-5 h-5 group-hover:text-primary transition-colors" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="group-hover:text-primary transition-colors">Pengaturan Profil</span>
            </a>
            <a href="{{ route('profile.tickets') }}"
                class="flex items-center gap-3 px-6 py-4 rounded-2xl font-bold transition-all group {{ request()->routeIs('profile.tickets') ? 'bg-indigo-50 text-primary' : 'text-text-muted hover:bg-slate-50' }}">
                <svg class="w-5 h-5 group-hover:text-primary transition-colors" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" />
                </svg>
                <span class="group-hover:text-primary transition-colors">Tiket Saya</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-6 py-4 rounded-2xl text-rose-500 font-bold hover:bg-rose-50 transition-all text-left cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>