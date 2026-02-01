<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Event Management') | Formal & Elegant</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css'])

    @stack('styles')
</head>

<body class="font-sans bg-slate-50 text-text-main leading-relaxed overflow-x-hidden">
    <nav class="navbar sticky top-0 bg-white/80 backdrop-blur-xl border-b border-border z-1000 h-[70px]">
        <div class="container mx-auto px-6 flex justify-between items-center h-full">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-primary font-heading font-bold text-2xl">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor" />
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span>EventHub</span>
            </a>

            <div class="flex gap-8 items-center">
                <a href="{{ route('events.index') }}"
                    class="font-medium hover:text-primary transition-colors {{ request()->routeIs('events.*') ? 'text-primary' : 'text-text-muted' }}">Explore
                    Event</a>
                @auth
                    <a href="{{ route('profile.tickets') }}"
                        class="font-medium hover:text-primary transition-colors {{ request()->routeIs('profile.tickets') ? 'text-primary' : 'text-text-muted' }}">Tiket
                        Saya</a>
                    <a href="{{ route('profile.index') }}"
                        class="font-medium hover:text-primary transition-colors {{ request()->routeIs('profile.index') ? 'text-primary' : 'text-text-muted' }}">Profil</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-4 py-1.5 rounded-premium border border-border font-semibold hover:bg-slate-100 transition-colors text-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="font-medium hover:text-primary transition-colors text-text-muted">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="bg-primary text-white px-6 py-2.5 rounded-premium font-semibold hover:bg-primary-hover shadow-sm hover:shadow-md transition-all active:scale-95">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="min-h-[80vh]">
        @if(session('success'))
            <div class="container mx-auto px-6 my-8">
                <div
                    class="p-4 rounded-premium bg-emerald-50 text-emerald-600 border border-emerald-200 font-medium animate-in fade-in slide-in-from-top-4 duration-300">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mx-auto px-6 my-8">
                <div
                    class="p-4 rounded-premium bg-rose-50 text-rose-600 border border-rose-200 font-medium animate-in fade-in slide-in-from-top-4 duration-300">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')

    </main>

    <footer class="bg-text-main text-white pt-16 pb-8 mt-20">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-start mb-12 gap-8">
                <div class="max-w-[300px]">
                    <div class="text-white font-heading font-bold text-2xl mb-6">EventHub</div>
                    <p class="text-slate-400">Platform ini menyediakan event formal dan inspiratif untuk Anda.</p>
                </div>
                <div class="flex gap-12 md:gap-24">
                    <div>
                        <h4 class="text-slate-400 font-heading font-bold mb-6">Navigation</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ url('/') }}"
                                    class="text-slate-300 hover:text-white transition-colors">Home</a></li>
                            <li><a href="{{ route('events.index') }}"
                                    class="text-slate-300 hover:text-white transition-colors">Explore</a></li>
                            <li><a href="#" class="text-slate-300 hover:text-white transition-colors">Tentang Kami</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center pt-8 border-t border-slate-700 text-slate-500 text-sm">
                &copy; {{ date('Y') }} EventHub. Made by <a href="https://github.com/MrFatra" target="_blank"
                    class="text-slate-300 hover:text-white transition-colors">MrFatra</a>.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>