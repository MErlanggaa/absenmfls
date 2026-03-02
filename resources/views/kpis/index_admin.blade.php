<x-app-layout>
    <x-slot name="header">
        {{ __('KPI - KESELURUHAN') }}
    </x-slot>

    <div class="space-y-6">
        <div class="premium-card">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h3 class="text-xl font-black italic text-slate-800 uppercase tracking-tight">Data Penilaian KPI</h3>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Daftar KPI seluruh panitia / anggota yang telah dinilai.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-indigo-50/50">
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest rounded-tl-xl w-16">No</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest">Nama Anggota</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest">Departemen</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest">Penilai</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest">Bulan/Tahun</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest">Total Nilai</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest">Indeks Skor</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest text-right rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 font-medium">
                        @forelse ($kpis as $index => $kpi)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                <td class="p-4">{{ $kpis->firstItem() + $index }}</td>
                                <td class="p-4 text-slate-800 font-bold capitalize">{{ $kpi->user->name }}</td>
                                <td class="p-4 capitalize">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        {{ $kpi->user->department->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="p-4 capitalize">{{ $kpi->assessor->name }}</td>
                                <td class="p-4">{{ \Carbon\Carbon::parse($kpi->period_date)->translatedFormat('F Y') }}</td>
                                <td class="p-4 font-black text-indigo-600">{{ $kpi->total_value }}</td>
                                <td class="p-4">
                                    @if($kpi->index_score === 'Mencapai Target')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100 uppercase tracking-widest">Mencapai Target</span>
                                    @elseif($kpi->index_score === 'Perlu Evaluasi')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600 border border-yellow-100 uppercase tracking-widest">Perlu Evaluasi</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100 uppercase tracking-widest">Tidak Mencapai Target</span>
                                    @endif
                                </td>
                                <td class="p-4 space-x-2 text-right">
                                    <a href="{{ route('kpis.show', $kpi->id) }}" class="inline-flex items-center justify-center p-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400 font-medium italic">Belum ada data KPI yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($kpis->hasPages())
                <div class="mt-6 border-t border-slate-100 pt-6">
                    {{ $kpis->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
