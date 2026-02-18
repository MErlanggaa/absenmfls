<x-app-layout>
    <x-slot name="header">BUAT PENGAJUAN BARU</x-slot>

    <div class="py-8 pb-20">
        <div class="max-w-3xl mx-auto">
            <div class="premium-card">
                <div class="mb-10 text-center">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 border border-indigo-100 italic font-black shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter uppercase mb-1">Form Pengajuan</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic leading-none">Submission Portal Management</p>
                </div>

                <form action="{{ route('approval-requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Judul Pengajuan</label>
                            <input type="text" name="title" class="w-full input-premium" placeholder="Contoh: Pengajuan Laptop Divisi..." required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Jenis Pengajuan</label>
                            <input type="text" name="type" class="w-full input-premium" placeholder="Anggaran / Kegiatan / Cuti" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Auto-filled Department -->
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Departemen (Auto-detect)</label>
                            <div class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                <span class="text-xs font-black text-slate-600 uppercase italic">{{ auth()->user()->department->name ?? 'MEMBER MFLS' }}</span>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 pl-4 italic">*Pengajuan akan otomatis dikirim ke Vice Project Director terkait.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Dokumen Pendukung (PDF/IMG)</label>
                            <input type="file" name="document" class="w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer italic">
                            <p class="text-[9px] text-slate-400 mt-2 pl-4 italic">*Maksimal file 5MB</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Deskripsi Detail</label>
                        <textarea name="description" rows="5" class="w-full input-premium" placeholder="Jelaskan secara detail mengenai pengajuan Anda..." required></textarea>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-center gap-4">
                        <a href="{{ route('approval-requests.index') }}" class="px-8 py-4 rounded-2xl font-black uppercase text-slate-400 hover:text-slate-600 tracking-widest transition italic">Batal</a>
                        <button type="submit" class="btn-primary py-5 px-12 group">
                             <span class="flex items-center gap-3 uppercase tracking-[0.2em] italic text-sm">
                                Kirim Pengajuan
                                <svg class="w-5 h-5 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
