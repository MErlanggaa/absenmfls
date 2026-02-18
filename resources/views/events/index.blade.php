<x-app-layout>
    <x-slot name="header">AGENDA RAPAT & KEGIATAN</x-slot>

    <div class="space-y-6 pb-12">
        <!-- Simplified Header/Stats for Mobile -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h3 class="text-xl lg:text-2xl font-black text-slate-800 italic tracking-tighter uppercase leading-none">Jadwal Aktif</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2 italic">Monitoring kehadiran anggota MFLS</p>
            </div>
            
            @if(auth()->user()->canManageEvents())
            <a href="{{ route('events.create') }}" class="btn-primary w-full lg:w-auto py-4 shadow-xl shadow-indigo-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="uppercase text-xs tracking-[0.2em] italic">Buat Rapat Baru</span>
            </a>
            @endif
        </div>

        <!-- Agenda List (Card Style for Mobile) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-4">
            @forelse($events as $event)
            <div class="premium-card p-0 overflow-hidden group">
                <div class="p-6 lg:p-8 flex flex-col lg:flex-row lg:items-center gap-6">
                    <!-- Date Badge -->
                    <div class="w-16 h-16 lg:w-20 lg:h-20 bg-indigo-50 border border-indigo-100 rounded-[1.5rem] lg:rounded-[2rem] flex flex-col items-center justify-center text-indigo-600 transition-all duration-500 group-hover:bg-indigo-600 group-hover:text-white group-hover:rotate-3 shadow-sm">
                        <span class="text-[10px] lg:text-xs font-black uppercase leading-none italic">{{ $event->event_date->format('M') }}</span>
                        <span class="text-xl lg:text-3xl font-black leading-none italic tracking-tighter">{{ $event->event_date->format('d') }}</span>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                             @if($event->is_active)
                                <span class="flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 text-[8px] font-black uppercase rounded-lg border border-green-100 italic tracking-widest">
                                    <span class="w-1 h-1 rounded-full bg-green-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-400 text-[8px] font-black uppercase rounded-lg border border-slate-200 italic tracking-widest">Selesai</span>
                            @endif
                            <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest italic">{{ $event->event_date->format('H:i') }} WIB</span>
                        </div>
                        <h4 class="text-lg lg:text-xl font-black text-slate-900 italic tracking-tighter truncate uppercase">{{ $event->name }}</h4>
                        <p class="text-[10px] lg:text-xs text-slate-400 font-bold italic mt-1 line-clamp-1 leading-relaxed">{{ $event->description }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-4 lg:pt-0 lg:border-l lg:border-slate-100 lg:pl-10">
                        <a href="{{ route('events.show', $event->id) }}" class="flex-1 lg:flex-none inline-flex items-center justify-center px-8 py-3 bg-slate-950 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 italic">
                            Detail
                        </a>
                        @if(auth()->user()->canManageEvents())
                        <div class="flex items-center gap-2">
                            <a href="{{ route('events.edit', $event->id) }}" class="p-3 bg-slate-100 text-slate-400 rounded-2xl hover:bg-amber-100 hover:text-amber-600 transition-all border border-slate-200 lg:border-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form id="delete-form-{{ $event->id }}" action="{{ route('events.destroy', $event->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button onclick="confirmDelete('{{ $event->id }}', 'Jadwal rapat ini bakal hilang selamanya, Bos!')" class="p-3 bg-slate-100 text-slate-400 rounded-2xl hover:bg-rose-100 hover:text-rose-600 transition-all border border-slate-200 lg:border-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20 bg-white rounded-[2.5rem] border border-slate-200">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 italic">Antrian Agenda Kosong</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
