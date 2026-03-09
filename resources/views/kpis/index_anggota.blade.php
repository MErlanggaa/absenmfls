<x-app-layout>
    <x-slot name="header">
        {{ __('KPI SAYA') }}
    </x-slot>

    <div class="space-y-6 max-w-7xl mx-auto pb-12">
        <div class="premium-card mb-6 border-t-4 border-indigo-600">
            <h1 class="text-2xl font-black italic text-indigo-900 uppercase tracking-tighter mb-2">Penilaian KPI Saya</h1>
            <p class="text-sm font-medium text-slate-500">Berikut adalah daftar KPI Anda yang telah selesai dinilai dan disahkan oleh Project Director.</p>
            
            <!-- Filter Form -->
            <form method="GET" action="{{ route('kpis.index') }}" class="mt-6 flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <select name="month" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium text-slate-700 bg-slate-50">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month', \Carbon\Carbon::now()->month) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <select name="year" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium text-slate-700 bg-slate-50">
                        @php $currentYear = \Carbon\Carbon::now()->year; @endphp
                        @foreach(range($currentYear - 2, $currentYear + 1) as $y)
                            <option value="{{ $y }}" {{ request('year', $currentYear) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="sm:w-auto w-full px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-md">
                    Filter
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($kpis as $kpi)
            <div class="premium-card p-6 flex flex-col relative overflow-hidden group">
                <!-- Info Header -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Periode</div>
                        <div class="text-sm font-black text-slate-800 italic uppercase truncate">{{ \Carbon\Carbon::parse($kpi->period_date)->translatedFormat('F Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Skor Total</div>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 font-black text-sm border border-indigo-100 shadow-sm">
                            {{ $kpi->total_value }}
                        </span>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4 mb-4 border border-slate-100 flex-1">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Penilai</span>
                            <span class="font-bold text-slate-700 text-[10px] uppercase line-clamp-1">{{ $kpi->assessor->name }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Indeks</span>
                            @if($kpi->index_score === 'Mencapai Target')
                                <span class="text-[10px] font-black text-green-600 uppercase">{{ $kpi->index_score }}</span>
                            @elseif($kpi->index_score === 'Perlu Evaluasi')
                                <span class="text-[10px] font-black text-yellow-600 uppercase">{{ $kpi->index_score }}</span>
                            @else
                                <span class="text-[10px] font-black text-red-600 uppercase">{{ $kpi->index_score }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <a href="{{ route('kpis.show', $kpi->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-950 text-white text-[9px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-indigo-600 transition-all shadow-md italic">
                        Lihat Detail
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full shadow-sm text-center py-16 bg-white rounded-[2.5rem] border border-slate-200">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 italic">Belum ada penilaian KPI untuk Anda yang telah disahkan oleh Project Director saat ini.</p>
            </div>
            @endforelse
        </div>
        
        @if($kpis->hasPages())
        <div class="mt-6 border-t border-slate-100 pt-6">
            {{ $kpis->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
