<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Presensi MFLS') }}</title>

        <!-- PWA Meta Tags -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="MFLS">
        <link rel="apple-touch-icon" href="/loog.jpeg">
        <link rel="icon" type="image/jpeg" href="/loog.jpeg">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind Play CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Outfit', 'sans-serif'],
                        },
                        colors: {
                            primary: '#4f46e5',
                            secondary: '#7c3aed',
                            surface: '#f8fafc',
                        }
                    }
                }
            }
        </script>

        <script>
            window.firebaseConfig = {
                apiKey: "{{ config('firebase.api_key') }}",
                authDomain: "{{ config('firebase.auth_domain') }}",
                projectId: "{{ config('firebase.project_id') }}",
                storageBucket: "{{ config('firebase.storage_bucket') }}",
                messagingSenderId: "{{ config('firebase.messaging_sender_id') }}",
                appId: "{{ config('firebase.app_id') }}"
            };
            window.firebaseVapidKey = "{{ config('firebase.vapid_key') }}";
        </script>
        @vite(['resources/js/app.js'])
        <script type="module" src="{{ asset('js/firebase-push.js') }}"></script>

        <style type="text/tailwindcss">
            @layer base {
                body {
                    @apply bg-[#f1f5f9] text-slate-700 antialiased overflow-x-hidden;
                }
            }

            @layer components {
                .premium-card {
                    @apply bg-white rounded-[2rem] p-8 border border-slate-200 shadow-[0_4px_20px_rgba(0,0,0,0.03)] transition-all duration-300 hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] hover:-translate-y-1;
                }

                .btn-primary {
                    background: linear-gradient(135deg, #4f46e5, #7c3aed);
                    @apply px-6 py-3 rounded-2xl font-bold text-white transition-all duration-300 active:scale-95 flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-indigo-200;
                }

                .sidebar-link {
                    @apply flex items-center gap-4 px-6 py-4 rounded-2xl transition-all duration-300 text-slate-500 font-medium;
                }

                .sidebar-link:hover {
                    @apply bg-slate-100 text-indigo-600;
                }

                .sidebar-link.active {
                    @apply bg-indigo-50 text-indigo-600 border border-indigo-100 font-bold;
                }

                .input-premium {
                    @apply bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all placeholder:text-slate-400;
                }

                /* Mobile Bottom Nav Styles */
                .mobile-nav-link {
                    @apply flex flex-col items-center justify-center gap-1 text-[10px] font-black uppercase tracking-tighter transition-all duration-300 text-slate-400;
                }
                .mobile-nav-link.active {
                    @apply text-indigo-600;
                }
            }
        </style>
    </head>
    <body class="pb-24 lg:pb-0">
        <div class="flex min-h-screen">
            <!-- Sidebar (Desktop) -->
            <aside class="w-80 hidden lg:flex flex-col p-6 sticky top-0 h-screen z-50">
                <div class="bg-white h-full rounded-[2.5rem] flex flex-col border border-slate-200 shadow-sm">
                    <div class="p-8">
                        <div class="flex items-center gap-4 group">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100 transition-transform duration-500 overflow-hidden border border-slate-100">
                                <img src="{{ asset('loog.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h1 class="font-black text-xl tracking-tighter text-slate-900 italic">MFLS <span class="text-indigo-600">ABSEN</span></h1>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-none">Management System</p>
                            </div>
                        </div>
                    </div>

                    <nav class="flex-1 px-4 space-y-2">
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('events.index') }}" class="sidebar-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Agenda Rapat</span>
                        </a>

                        @if(auth()->user()->canCreateApproval())
                        <a href="{{ route('approval-requests.index') }}" class="sidebar-link {{ request()->routeIs('approval-requests.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <span>Pengajuan</span>
                        </a>
                        @endif

                        <a href="{{ route('attendances.index') }}" class="sidebar-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Riwayat Absen</span>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Profil Saya</span>
                        </a>

                        @if(auth()->user()->canManageUsers())
                        <div class="px-6 pt-8 pb-2">
                             <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Management</span>
                        </div>
                        <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span>Data Pengguna</span>
                        </a>
                        @endif
                    </nav>

                    <div class="p-6 border-t border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-6 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all uppercase text-xs tracking-widest italic">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Bottom Nav (Mobile) -->
            <nav class="lg:hidden fixed bottom-0 left-0 right-0 h-20 bg-white border-t border-slate-200 px-6 flex items-center justify-between z-[100] shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
                <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Home</span>
                </a>
                <a href="{{ route('events.index') }}" class="mobile-nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Agenda</span>
                </a>
                
                @if(auth()->user()->canCreateApproval())
                <a href="{{ route('approval-requests.index') }}" class="mobile-nav-link {{ request()->routeIs('approval-requests.*') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span>Ajuan</span>
                </a>
                @endif

                <a href="{{ route('attendances.index') }}" class="mobile-nav-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>History</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="mobile-nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Profil</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="mobile-nav-link text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Out</span>
                    </button>
                </form>
            </nav>

            <!-- Main Content Area -->
            <main class="flex-1 flex flex-col p-4 lg:p-6 lg:pl-0">
                <!-- Top Header -->
                <header class="bg-white rounded-3xl lg:rounded-[2.5rem] p-4 lg:p-6 mb-6 flex justify-between items-center px-6 lg:px-8 border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="lg:hidden w-10 h-10 bg-white rounded-xl flex items-center justify-center overflow-hidden shadow-lg shadow-indigo-100 border border-slate-100">
                            <img src="{{ asset('loog.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xs lg:text-sm font-black text-slate-800 uppercase tracking-widest italic">{{ $header ?? 'OVERVIEW' }}</h2>
                    </div>
                    
                    <div class="flex items-center gap-4 lg:gap-6">
                        <a href="{{ route('notifications.index') }}" class="relative p-2 text-slate-400 hover:text-indigo-600 transition">
                            <svg class="w-5 h-5 lg:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 w-3.5 h-3.5 bg-red-500 rounded-full text-[8px] flex items-center justify-center text-white border-2 border-white font-black">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>

                        <div class="flex items-center gap-2 lg:gap-3 pl-4 lg:pl-6 border-l border-slate-100 group">
                            <div class="text-right hidden sm:block">
                                <p class="text-[10px] lg:text-xs font-black text-slate-900 leading-none mb-1 italic">{{ auth()->user()->name }}</p>
                                <p class="text-[8px] lg:text-[9px] font-black text-indigo-500 uppercase tracking-[0.2em] leading-none">
                                    {{ auth()->user()->department->name ?? auth()->user()->role->name }}
                                </p>
                            </div>
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-indigo-50 rounded-lg lg:rounded-xl flex items-center justify-center text-indigo-600 font-bold border border-indigo-100 text-xs lg:text-base">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    {{ $slot }}
                </div>

                <!-- Premium Footer -->
                <footer class="mt-8 py-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] font-black uppercase tracking-[0.2em] italic text-slate-400">
                    <div>
                        @2026 MFLS . ALL RIGHT
                    </div>
                    <div>
                        DEVELOP BY <a href="https://www.instagram.com/e_erlanggaa" target="_blank" class="text-indigo-500 hover:text-indigo-600 transition">MUHAMMAD ERLANGGA PUTRA WITANTO</a>
                    </div>
                </footer>
            </main>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { @apply bg-slate-300 rounded-full; }
            
            /* SweetAlert Custom Styling */
            .swal2-popup {
                @apply rounded-[2.5rem] p-8 border-none !important;
                font-family: 'Outfit', sans-serif !important;
            }
            .swal2-title {
                @apply font-black italic tracking-tighter uppercase text-slate-800 !important;
            }
            .swal2-confirm {
                @apply rounded-2xl bg-indigo-600 px-8 py-3 font-black uppercase tracking-widest text-[10px] italic !important;
            }
            .swal2-cancel {
                @apply rounded-2xl bg-slate-100 text-slate-400 px-8 py-3 font-black uppercase tracking-widest text-[10px] italic !important;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'BERHASIL!',
                        text: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'WADUH!',
                        text: "{{ session('error') }}",
                        confirmButtonText: 'OKE, SORI'
                    });
                @endif
                @if(session('message'))
                    Swal.fire({
                        icon: 'info',
                        title: 'INFO',
                        text: "{{ session('message') }}",
                        confirmButtonText: 'MENGERTI'
                    });
                @endif

                if ('serviceWorker' in navigator) {
                    window.addEventListener('load', () => {
                        navigator.serviceWorker.register('/firebase-messaging-sw.js')
                            .then(reg => console.log('PWA Service Worker Registered!'))
                            .catch(err => console.log('PWA Service Worker Failed!', err));
                    });
                }
            });

            // Global Confirmation for Delete
            function confirmDelete(id, text = "Data ini bakal dihapus permanen, Bos!") {
                Swal.fire({
                    title: 'YAKIN MAU HAPUS?',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'IYA, HAPUS!',
                    cancelButtonText: 'GAK JADI'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                })
            }
        </script>
    </body>
</html>
