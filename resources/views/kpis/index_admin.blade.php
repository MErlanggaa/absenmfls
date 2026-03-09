<x-app-layout>
    <x-slot name="header">
        {{ __('KPI - KESELURUHAN') }}
    </x-slot>

    <div class="space-y-8 pb-12">
        <div class="premium-card">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-black italic text-slate-800 uppercase tracking-tight">Data Penilaian KPI</h3>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Daftar KPI seluruh panitia / anggota yang telah dinilai pada periode terpilih.</p>
                </div>
                @if(auth()->user()->canViewAllKPI())
                <div class="flex gap-3">
                    <a href="{{ route('kpis.download-zip') }}" class="inline-flex items-center justify-center w-full sm:w-auto gap-2 px-6 py-3 rounded-2xl bg-amber-600 text-white font-black uppercase tracking-widest text-[10px] italic hover:bg-amber-700 transition shadow-lg shadow-amber-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Semua (ZIP)
                    </a>
                </div>
                @endif
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('kpis.keseluruhan') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <select name="month" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium text-slate-700 bg-slate-50" onchange="this.form.submit()">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month', \Carbon\Carbon::now()->month) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <select name="year" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium text-slate-700 bg-slate-50" onchange="this.form.submit()">
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

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($kpis as $kpi)
                <div class="premium-card p-6 flex flex-col relative overflow-hidden group border-t-4 {{ $kpi->pd_signature ? 'border-emerald-500' : 'border-amber-500' }}">
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4 flex flex-col items-end gap-1">
                        @if($kpi->pd_signature)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[8px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-widest gap-1">
                                Disahkan PD
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[8px] font-black bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-widest gap-1 shadow-sm animate-pulse">
                                Menunggu PD
                            </span>
                        @endif
                    </div>

                    <!-- Member Info -->
                    <div class="flex items-center gap-4 mb-4 pr-24">
                        <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                            <span class="font-black text-lg italic">{{ strtoupper(substr($kpi->user->name, 0, 2)) }}</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-0.5">{{ $kpi->user->department->name ?? '-' }}</div>
                            <div class="text-sm font-black text-slate-800 italic uppercase truncate">{{ $kpi->user->name }}</div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="bg-slate-50 rounded-2xl p-4 mb-4 border border-slate-100 flex-1">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Total Nilai</span>
                                <span class="font-black text-indigo-600 text-[14px] uppercase">{{ $kpi->total_value }}</span>
                            </div>
                            <div>
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Indeks Skor</span>
                                @if($kpi->index_score === 'Mencapai Target')
                                    <span class="text-[10px] font-black text-green-600 uppercase">{{ $kpi->index_score }}</span>
                                @elseif($kpi->index_score === 'Perlu Evaluasi')
                                    <span class="text-[10px] font-black text-yellow-600 uppercase">{{ $kpi->index_score }}</span>
                                @else
                                    <span class="text-[10px] font-black text-red-600 uppercase">{{ $kpi->index_score }}</span>
                                @endif
                            </div>
                            <div class="col-span-2 mt-2 pt-2 border-t border-slate-200">
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Penilai</span>
                                <span class="font-bold text-slate-700 text-[10px] uppercase line-clamp-1">{{ $kpi->assessor->name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-2">
                        <a href="{{ route('kpis.show', $kpi->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-950 text-white text-[9px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-indigo-600 transition-all shadow-md italic">
                            Detail Evaluasi
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full shadow-sm text-center py-16 bg-white rounded-[2.5rem] border border-slate-200">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 italic">Belum ada data KPI yang tercatat.</p>
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
