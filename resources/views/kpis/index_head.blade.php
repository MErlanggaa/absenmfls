<x-app-layout>
    <x-slot name="header">
        {{ __('PENILAIAN KPI ANGGOTA') }}
    </x-slot>

    <div class="space-y-6">
        <div class="premium-card">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h3 class="text-xl font-black italic text-slate-800 uppercase tracking-tight">Anggota Departemen {{ auth()->user()->department->name ?? '' }}</h3>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Daftar anggota untuk periode penilaian {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-indigo-50/50">
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest rounded-tl-xl w-16">No</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest">Nama Anggota</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest">Email</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest">Status Penilaian</th>
                            <th class="p-4 border-b border-indigo-100 text-[10px] font-black uppercase text-indigo-600 tracking-widest text-right rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 font-medium">
                        @forelse ($members as $index => $member)
                            @php
                                $kpi = $kpisThisMonth->get($member->id);
                            @endphp
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                <td class="p-4">{{ $loop->iteration }}</td>
                                <td class="p-4 text-slate-800 font-bold capitalize">{{ $member->name }}</td>
                                <td class="p-4 text-slate-500">{{ $member->email }}</td>
                                <td class="p-4">
                                    @if($kpi)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-black bg-green-50 text-green-600 border border-green-100 uppercase tracking-widest gap-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            Sudah Dinilai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-black bg-yellow-50 text-yellow-600 border border-yellow-100 uppercase tracking-widest gap-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 space-x-2 text-right">
                                    @if($kpi)
                                        <a href="{{ route('kpis.show', $kpi->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-50 text-slate-600 hover:bg-slate-800 hover:text-white transition-all shadow-sm border border-slate-200 text-xs font-bold tracking-widest uppercase">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Lihat
                                        </a>
                                    @else
                                        <a href="{{ route('kpis.create', $member->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-100 text-xs font-bold tracking-widest uppercase">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Isi KPI
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 font-medium italic">Belum ada anggota di departemen ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
