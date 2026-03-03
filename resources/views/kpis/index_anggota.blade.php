<x-app-layout>
    <x-slot name="header">
        {{ __('KPI SAYA') }}
    </x-slot>

    <div class="space-y-6 max-w-7xl mx-auto">
        <div class="premium-card mb-6 border-t-4 border-indigo-600">
            <h1 class="text-2xl font-black italic text-indigo-900 uppercase tracking-tighter mb-2">Penilaian KPI Saya</h1>
            <p class="text-sm font-medium text-slate-500">Berikut adalah daftar KPI Anda yang telah selesai dinilai dan disahkan oleh Project Director.</p>
        </div>

        <div class="premium-card p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-indigo-50 text-indigo-900 border-b border-indigo-100">
                        <tr>
                            <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Periode</th>
                            <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Penilai</th>
                            <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px] text-center">Skor Total</th>
                            <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Indeks</th>
                            <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kpis as $kpi)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($kpi->period_date)->translatedFormat('F Y') }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600">
                                {{ $kpi->assessor->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 font-black text-sm border border-indigo-100">
                                    {{ $kpi->total_value }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($kpi->index_score === 'Mencapai Target')
                                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest border border-green-100">{{ $kpi->index_score }}</span>
                                @elseif($kpi->index_score === 'Perlu Evaluasi')
                                    <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-600 text-[10px] font-black uppercase tracking-widest border border-yellow-100">{{ $kpi->index_score }}</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-100">{{ $kpi->index_score }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('kpis.show', $kpi->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium italic">
                                Belum ada penilaian KPI untuk Anda yang telah disahkan oleh Project Director saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($kpis->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $kpis->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
