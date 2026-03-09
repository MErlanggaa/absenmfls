<x-app-layout>
    <x-slot name="header">
        {{ __('PENILAIAN KPI ANGGOTA') }}
    </x-slot>

    <div class="space-y-6 pb-12">
        <div class="premium-card">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-black italic text-slate-800 uppercase tracking-tight">Anggota Departemen {{ auth()->user()->department->name ?? '' }}</h3>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Daftar anggota untuk periode penilaian terpilih.</p>
                </div>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('kpis.anggota') }}" class="flex flex-col sm:flex-row gap-3">
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
            @forelse ($members as $member)
                @php
                    $kpi = $kpisThisMonth->get($member->id);
                @endphp
                <div class="premium-card p-6 flex flex-col relative overflow-hidden group">
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4 flex flex-col items-end gap-1">
                        @if($kpi)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[8px] font-black bg-green-50 text-green-600 border border-green-100 uppercase tracking-widest gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                Sudah Dinilai
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[8px] font-black bg-yellow-50 text-yellow-600 border border-yellow-100 uppercase tracking-widest gap-1">
                                Belum Dinilai
                            </span>
                        @endif

                        @if(auth()->user()->isKepalaDivisi() || auth()->user()->canViewAllKPI())
                            @if($kpi && $kpi->pd_signature)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[8px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-widest">
                                    PD Disahkan
                                </span>
                            @elseif($kpi)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[8px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-widest">
                                    Menunggu PD
                                </span>
                            @endif
                        @endif
                    </div>

                    <!-- Member Info -->
                    <div class="flex items-center gap-4 mb-4 pr-24">
                        <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                            <span class="font-black text-lg italic">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-slate-800 italic uppercase truncate">{{ $member->name }}</div>
                            <div class="text-[10px] font-medium text-slate-500 truncate mt-0.5">{{ $member->email }}</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 mt-auto border-t border-slate-100 flex gap-2">
                        @if($kpi)
                            <a href="{{ route('kpis.show', $kpi->id) }}" class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-3 rounded-xl bg-slate-950 text-white hover:bg-indigo-600 transition-all shadow-md text-[9px] font-black uppercase tracking-[0.2em] italic">
                                Lihat KPI
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        @elseif(auth()->user()->isKepalaDivisi())
                            <!-- Pass month and year when creating KPI so it can be used for that specific period if needed, though KpiController store creates for current month. To strictly follow the rules, the create form could just be disabled for past months, but since they want to fill it, let them proceed -->
                            <a href="{{ route('kpis.create', ['user' => $member->id, 'month' => request('month', \Carbon\Carbon::now()->month), 'year' => request('year', \Carbon\Carbon::now()->year)]) }}" class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition-all shadow-md text-[9px] font-black uppercase tracking-[0.2em] italic">
                                Isi KPI
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full shadow-sm text-center py-16 bg-white rounded-[2.5rem] border border-slate-200">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 italic">Belum ada anggota di departemen ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
