<x-app-layout>
    <x-slot name="header">DASHBOARD</x-slot>

    @if(auth()->user()->isAnggota())
        <!-- MINIMALIST ANGGOTA UI (MOBILE FIRST) -->
        <div class="space-y-6 lg:max-w-xl lg:mx-auto">
            <!-- Simple Profile Header -->
            <div class="flex items-center gap-4 px-2">
                <div class="w-14 h-14 bg-indigo-600 rounded-[1.2rem] flex items-center justify-center text-white text-xl font-black italic shadow-lg shadow-indigo-100">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-800 italic leading-none tracking-tighter uppercase">{{ auth()->user()->name }}</h3>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mt-1 italic">{{ auth()->user()->department->name ?? 'Anggota MFLS' }}</p>
                </div>
            </div>

            <!-- Main Action Card: Scanner Presence -->
            @php
                $activeEvent = \App\Models\Event::where('is_active', true)->latest()->first();
            @endphp
            
            @if($activeEvent)
            <div class="premium-card bg-indigo-600 text-white border-none shadow-xl shadow-indigo-100 p-8 text-center relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 opacity-50"></div>
                <div class="relative z-10 space-y-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-200 italic">Rapat Sedang Berlangsung</span>
                    <h4 class="text-2xl font-black italic tracking-tighter leading-tight uppercase">{{ $activeEvent->name }}</h4>
                    <div class="pt-4">
                        <a href="{{ route('events.show', $activeEvent->id) }}" class="inline-flex items-center justify-center w-full py-5 bg-white text-indigo-600 rounded-[1.5rem] font-black uppercase tracking-[0.2em] italic text-xs shadow-2xl hover:scale-105 transition-transform duration-300">
                             <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l2 4h5l-4 4 1 5-4-3-4 3 1-5-4-4h5l2-4V4z"></path></svg>
                             Scan Absen Sekarang
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="premium-card bg-slate-100 border-dashed border-slate-300 flex flex-col items-center py-12 text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Belum Ada Rapat Aktif</p>
            </div>
            @endif

            <!-- Mini Stats Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="premium-card p-6 flex flex-col items-center justify-center gap-1">
                    <span class="text-3xl font-black text-slate-800 italic leading-none">{{ auth()->user()->attendances()->count() }}</span>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Total Hadir</span>
                </div>
                <div class="premium-card p-6 flex flex-col items-center justify-center gap-1">
                    <span class="text-3xl font-black text-indigo-600 italic leading-none">{{ auth()->user()->unreadNotifications->count() }}</span>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Notifikasi</span>
                </div>
            </div>

            <!-- Recent Activity List (Very Simple) -->
            <div class="space-y-4 px-2 pt-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-800 uppercase italic tracking-widest">Aktivitas Terakhir</h3>
                    <a href="{{ route('attendances.index') }}" class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic hover:text-indigo-600">Lengkap &rarr;</a>
                </div>
                <div class="space-y-3">
                    @forelse(auth()->user()->attendances()->with('event')->latest()->take(3)->get() as $att)
                    <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-100">
                        <div class="min-w-0">
                            <h5 class="text-xs font-black text-slate-800 uppercase italic truncate">{{ $att->event->name }}</h5>
                            <p class="text-[9px] text-slate-400 font-bold italic mt-0.5">{{ $att->check_in->format('H:i') }} WIB &bull; {{ $att->check_in->format('d M') }}</p>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]"></span>
                    </div>
                    @empty
                    <p class="text-center text-[10px] text-slate-300 font-black uppercase italic tracking-widest py-8">Belum ada history</p>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <!-- EXISTING ADMIN/KADEP UI -->
        <div class="space-y-6">
            <!-- Hero Welcome -->
            <div class="premium-card bg-gradient-to-br from-indigo-600 to-indigo-800 text-white relative overflow-hidden border-none p-6 lg:p-10 mb-4 shadow-xl shadow-indigo-100">
                <div class="absolute top-0 right-0 w-32 h-32 lg:w-64 lg:h-64 bg-white/10 -mr-10 lg:-mr-20 -mt-10 lg:-mt-20 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-200 mb-2 block italic">Verified account</span>
                    <h3 class="text-2xl lg:text-4xl font-black italic tracking-tighter mb-1">Halo, {{ auth()->user()->name }}!</h3>
                    <p class="text-[10px] lg:text-xs font-bold text-indigo-100 uppercase tracking-widest">{{ auth()->user()->role->name }} &bull; {{ auth()->user()->department->name ?? 'ALL DEPARTMENTS' }}</p>
                    
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('events.index') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-black/5 hover:scale-105 transition-transform italic">
                            Cek Rapat
                        </a>
                        @if(auth()->user()->canCreateApproval())
                        <a href="{{ route('approval-requests.create') }}" class="bg-indigo-500/30 backdrop-blur-md border border-white/20 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-500/50 transition-colors italic">
                            Buat Pengajuan
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="premium-card p-5 lg:p-6 text-center">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-blue-100">
                        <svg class="w-5 h-5 lg:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-xl lg:text-3xl font-black text-slate-800 italic leading-none truncate">{{ auth()->user()->attendances()->count() }}</div>
                    <div class="text-[8px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Hadir</div>
                </div>

                <div class="premium-card p-5 lg:p-6 text-center">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-amber-100">
                        <svg class="w-5 h-5 lg:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <div class="text-xl lg:text-3xl font-black text-slate-800 italic leading-none truncate">{{ auth()->user()->unreadNotifications->count() }}</div>
                    <div class="text-[8px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Notifikasi</div>
                </div>

                @if(auth()->user()->canViewAllAttendance())
                <div class="premium-card p-5 lg:p-6 text-center">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-indigo-100">
                        <svg class="w-5 h-5 lg:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="text-xl lg:text-3xl font-black text-slate-800 italic leading-none truncate">{{ \App\Models\User::count() }}</div>
                    <div class="text-[8px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total User</div>
                </div>

                <div class="premium-card p-5 lg:p-6 text-center">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-rose-100">
                        <svg class="w-5 h-5 lg:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div class="text-xl lg:text-3xl font-black text-slate-800 italic leading-none truncate">{{ \App\Models\Event::count() }}</div>
                    <div class="text-[8px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Rapat</div>
                </div>
                @endif
            </div>

            <!-- Dashboard Timeline and Notifications -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="premium-card p-0 overflow-hidden">
                    <div class="p-6 lg:p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-sm lg:text-lg font-black text-slate-900 uppercase italic tracking-tighter">Absensi Terakhir</h3>
                        <a href="{{ route('attendances.index') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest italic hover:underline">Lihat Semua &rarr;</a>
                    </div>
                    <div class="p-6 lg:p-8">
                        <div class="space-y-6">
                            @forelse(auth()->user()->attendances()->with('event')->latest()->take(3)->get() as $att)
                            <div class="flex items-center gap-4 group">
                                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white border border-slate-100 rounded-2xl flex flex-col items-center justify-center shadow-sm text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                     <span class="text-[8px] font-black uppercase leading-none">{{ $att->check_in->format('M') }}</span>
                                     <span class="text-xs lg:text-sm font-black leading-none">{{ $att->check_in->format('d') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs lg:text-sm font-black text-slate-800 uppercase italic tracking-tight truncate">{{ $att->event->name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase italic">{{ $att->check_in->format('H:i') }} WIB &bull; {{ round($att->distance_meter) }}m</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-center py-6 text-[10px] text-slate-300 font-black uppercase italic">Kosong</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Notif -->
                <div class="premium-card p-0 overflow-hidden">
                    <div class="p-6 lg:p-8 border-b border-slate-50 bg-amber-50/50">
                        <h3 class="text-sm lg:text-lg font-black text-slate-900 uppercase italic tracking-tighter">Notifikasi</h3>
                    </div>
                    <div class="p-6 lg:p-8 space-y-4">
                        @forelse(auth()->user()->unreadNotifications->take(2) as $notif)
                        <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 items-center">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-amber-500 shadow-sm border border-amber-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-[11px] font-bold text-slate-700 leading-tight truncate">{{ $notif->data['title'] ?? 'Notif' }}</p>
                                <p class="text-[9px] text-slate-400 font-black italic mt-1 uppercase">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-[10px] text-slate-300 font-black uppercase italic py-4">Semua Terbaca</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
