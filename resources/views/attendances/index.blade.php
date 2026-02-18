<x-app-layout>
    <x-slot name="header">RIWAYAT KEHADIRAN</x-slot>

    <div class="space-y-6 pb-12">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm animate-bounce text-sm font-bold italic">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-2 px-2">
            <h3 class="text-xl font-black text-slate-800 italic uppercase tracking-tighter">History Absensi</h3>
            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest italic leading-none">{{ $attendances->count() }} Data</span>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse($attendances as $attendance)
            <div class="premium-card p-6 flex items-center gap-5">
                <!-- Status Icon -->
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center border border-green-100 shadow-sm shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $attendance->check_in->format('d M Y') }}</span>
                        <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest italic">{{ $attendance->check_in->format('H:i') }} WIB</span>
                    </div>
                    <h4 class="text-sm lg:text-base font-black text-slate-900 truncate uppercase italic tracking-tight">{{ $attendance->event->name }}</h4>
                    <p class="text-[9px] text-slate-400 font-bold uppercase italic mt-1 leading-none">
                        Metode: QR Scan &bull; Akurasi: {{ round($attendance->distance_meter) }}m
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-20 bg-white rounded-[2.5rem] border border-slate-200">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-300 italic">Belum Ada Riwayat Absensi</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
