<x-app-layout>
    <x-slot name="header">DETAIL PENGAJUAN</x-slot>

    <div class="py-12 pb-24 lg:pb-20">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Main Content Card -->
            <div class="premium-card relative overflow-hidden">
                @php
                    $statusColors = [
                        'pending_review' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'pending_approval' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                        'approved' => 'bg-green-50 text-green-600 border-green-100',
                        'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                    ];
                    $statusLabels = [
                        'pending_review' => 'Menunggu Review VPD',
                        'pending_approval' => 'Menunggu Approval PD',
                        'approved' => 'Selesai / Disetujui',
                        'rejected' => 'Ditolak',
                    ];
                    $color = $statusColors[$approvalRequest->status] ?? 'bg-slate-50 text-slate-400 border-slate-100';
                    $label = $statusLabels[$approvalRequest->status] ?? $approvalRequest->status;
                @endphp

                <div class="absolute top-0 right-0 p-8">
                    <span class="{{ $color }} text-[10px] font-black uppercase px-4 py-2 rounded-full border tracking-widest italic shadow-sm">
                        {{ $label }}
                    </span>
                </div>

                <div class="mb-10">
                    <span class="text-indigo-600 font-black text-[10px] tracking-[0.3em] uppercase mb-2 block italic">Proposal Request</span>
                    <h3 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4 italic tracking-tighter uppercase">{{ $approvalRequest->title }}</h3>
                    <div class="flex items-center gap-3 text-[10px] font-black text-slate-400 uppercase italic tracking-widest">
                        <span>{{ $approvalRequest->user->name }}</span>
                        <span>&bull;</span>
                        <span>{{ $approvalRequest->department->name ?? 'ALL DEPARTMENTS' }}</span>
                        <span>&bull;</span>
                        <span>{{ $approvalRequest->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Jenis Pengajuan</h4>
                            <p class="text-slate-800 font-bold italic">{{ $approvalRequest->type }}</p>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Deskripsi</h4>
                            <p class="text-slate-600 leading-relaxed italic whitespace-pre-wrap text-sm">{{ $approvalRequest->description }}</p>
                        </div>
                    </div>
                    <div class="bg-indigo-50/50 rounded-[2rem] p-6 border border-indigo-100 flex flex-col justify-center items-center text-center">
                        <h4 class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-3 italic">Dokumen Lampiran</h4>
                        @if($approvalRequest->document_path)
                            <a href="{{ route('approval-requests.download', basename($approvalRequest->document_path)) }}" class="inline-flex items-center gap-2 bg-indigo-600 px-6 py-3 rounded-xl text-[10px] font-black text-white hover:bg-slate-900 transition-all uppercase tracking-widest italic shadow-lg shadow-indigo-100">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path></svg>
                                <span>Unduh Dokumen</span>
                            </a>
                        @else
                            <p class="text-[9px] font-black text-slate-300 uppercase italic">Lampiran Kosong</p>
                        @endif
                    </div>
                </div>

                @if($approvalRequest->rejected_reason)
                <div class="bg-rose-50 rounded-[2rem] p-6 border border-rose-100">
                    <h4 class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-2 italic">Alasan Penolakan:</h4>
                    <p class="text-rose-900 font-bold italic text-sm">{{ $approvalRequest->rejected_reason }}</p>
                </div>
                @endif
            </div>

            <!-- Approval Pipeline / History -->
            <div class="premium-card">
                <h4 class="text-sm font-black text-slate-800 uppercase italic tracking-tighter mb-8">Riwayat & Progress Persetujuan</h4>
                
                <div class="space-y-8 relative">
                    <!-- Vertical Line -->
                    <div class="absolute left-6 top-2 bottom-2 w-0.5 bg-slate-100 border-l border-dashed border-slate-200"></div>

                    @foreach($approvalRequest->logs as $log)
                    <div class="relative flex items-start gap-8">
                        <div class="w-12 h-12 rounded-2xl {{ $log->status == 'approved' ? 'bg-green-600' : 'bg-rose-600' }} flex items-center justify-center text-white shadow-lg shrink-0 relative z-10">
                             @if($log->status == 'approved')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                             @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                             @endif
                        </div>
                        <div class="flex-1 min-w-0 pt-1">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <h5 class="text-xs font-black text-slate-800 uppercase italic tracking-tight">{{ $log->status == 'approved' ? 'Diterima' : 'Ditolak' }} oleh {{ $log->approver->name }}</h5>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $log->approver->role->name ?? 'Approver' }} &bull; {{ $log->approved_at->format('H:i') }} WIB, {{ $log->approved_at->format('d M') }}</p>
                                </div>
                                @if($log->note)
                                <div class="bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">
                                    <p class="text-[9px] text-slate-500 font-black italic uppercase italic">"{{ $log->note }}"</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Next Step Indicator -->
                    @if($approvalRequest->status !== 'approved' && $approvalRequest->status !== 'rejected')
                    <div class="relative flex items-start gap-8">
                        <div class="w-12 h-12 rounded-2xl bg-white border-2 border-dashed border-slate-200 flex items-center justify-center text-slate-300 shrink-0 relative z-10">
                            <div class="w-2 h-2 rounded-full bg-slate-200 animate-pulse"></div>
                        </div>
                        <div class="flex-1 pt-3">
                            <h5 class="text-[10px] font-black text-slate-300 uppercase italic tracking-widest">
                                {{ $approvalRequest->status === 'pending_review' ? 'Menunggu Reviewvpd...' : 'Menunggu Approval PD...' }}
                            </h5>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Management Actions -->
            @php
                $user = auth()->user();
                $canApprove = false;
                if ($approvalRequest->status == 'pending_review' && ($user->role->name == 'vice_project_director' || $user->isSuperAdmin())) $canApprove = true;
                if ($approvalRequest->status == 'pending_approval' && ($user->role->name == 'project_director' || $user->isSuperAdmin())) $canApprove = true;
            @endphp
            
            @if($canApprove)
            <div class="premium-card bg-slate-900 text-white border-none shadow-2xl p-10">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-indigo-400 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black italic tracking-tighter uppercase leading-none">Keputusan Persetujuan</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Authorized Personnel Only</p>
                    </div>
                </div>

                <form method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-4">Berikan Catatan (Opsional)</label>
                        <textarea name="note" rows="3" class="w-full bg-white/5 border border-white/10 rounded-[1.5rem] p-6 text-sm italic focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Tulis catatan jika diperlukan..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <button type="submit" formaction="{{ route('approval-requests.approve', $approvalRequest->id) }}" class="flex items-center justify-center w-full py-5 bg-green-600 rounded-[1.5rem] font-black uppercase text-[10px] italic tracking-widest hover:bg-green-700 transition shadow-xl shadow-green-900/20">
                            Setujui
                        </button>
                        <button type="submit" formaction="{{ route('approval-requests.reject', $approvalRequest->id) }}" class="flex items-center justify-center w-full py-5 bg-rose-600 rounded-[1.5rem] font-black uppercase text-[10px] italic tracking-widest hover:bg-rose-700 transition shadow-xl shadow-rose-900/20">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
