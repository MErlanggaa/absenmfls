<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pengajuan Approval') }}
        </h2>
    </x-slot>

    <div class="space-y-6 pb-12">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h3 class="text-xl lg:text-2xl font-black text-slate-800 italic tracking-tighter uppercase leading-none">Riwayat Pengajuan</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2 italic">Daftar approval & perizinan</p>
            </div>
            @if(!in_array(auth()->user()->role->name, ['project_director', 'vice_project_director']))
                <a href="{{ route('approval-requests.create') }}" class="btn-primary w-full lg:w-auto py-4 shadow-xl shadow-indigo-100 flex justify-center text-center">
                    <span class="uppercase text-xs tracking-[0.2em] italic">+ Buat Pengajuan Baru</span>
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 text-green-700 rounded-2xl border border-green-100 italic text-xs font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($requests as $request)
            <div class="premium-card p-6 flex flex-col relative overflow-hidden group">
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @php
                        $statusColors = [
                            'pending_review' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                            'pending_approval' => 'bg-orange-50 text-orange-600 border-orange-100',
                            'approved' => 'bg-green-50 text-green-600 border-green-100',
                            'rejected' => 'bg-red-50 text-red-600 border-red-100',
                        ];
                        $statusLabels = [
                            'pending_review' => 'Menunggu Review',
                            'pending_approval' => 'Menunggu Approval',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                        ];
                        $color = $statusColors[$request->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                        $label = $statusLabels[$request->status] ?? ucfirst($request->status);
                    @endphp
                    <span class="px-3 py-1 text-[8px] font-black uppercase rounded-lg border italic tracking-widest {{ $color }}">
                        {{ $label }}
                    </span>
                </div>

                <!-- Info -->
                <div class="mb-4 pr-24">
                    <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest italic mb-1">{{ $request->type }}</div>
                    <div class="text-sm font-black text-slate-800 italic uppercase truncate">{{ $request->title }}</div>
                    <div class="text-[10px] font-medium text-slate-500 mt-1">Oleh: <span class="font-bold">{{ $request->user->name }}</span></div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex-1 mb-4">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Departemen</span>
                            <span class="font-bold text-slate-700 text-[10px] uppercase line-clamp-2">{{ $request->department->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Tanggal Diajukan</span>
                            <span class="font-bold text-slate-700 text-[10px] uppercase">{{ $request->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <a href="{{ route('approval-requests.show', $request->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-950 text-white text-[9px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-indigo-600 transition-all shadow-md italic">
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
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 italic">Belum ada data pengajuan</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
