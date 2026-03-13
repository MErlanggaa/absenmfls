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

                <form action="{{ route('approval-requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-1 italic">Judul Pengajuan</label>
                        <input type="text" name="title" class="w-full input-premium" placeholder="Contoh: Pengajuan Laptop Divisi..." required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-1 italic">Jenis Pengajuan</label>
                        <input type="text" name="type" class="w-full input-premium" placeholder="Anggaran / Kegiatan / Cuti" required>
                    </div>

                    <!-- Auto-filled Department -->
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-1 italic">Departemen (Auto-detect)</label>
                        <div class="w-full bg-slate-50 border border-slate-200 rounded-xl sm:rounded-2xl px-4 py-3 sm:px-6 sm:py-4 flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse shrink-0"></span>
                            <span class="text-xs font-black text-slate-600 uppercase italic break-words">{{ auth()->user()->department->name ?? 'MEMBER MFLS' }}</span>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-2 pl-1 italic">*Pengajuan akan otomatis dikirim ke Vice Project Director terkait.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-1 italic">Deskripsi Detail</label>
                        <textarea name="description" rows="4" class="w-full input-premium" placeholder="Jelaskan secara detail mengenai pengajuan Anda..." required></textarea>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest pl-1 italic">Lampiran Pendukung (Pilih Salah Satu)</label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Option 1: File Upload -->
                            <div class="bg-indigo-50/30 border border-indigo-100/50 rounded-2xl p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-[9px] font-black text-indigo-600 uppercase italic tracking-widest">Upload File</span>
                                </div>
                                <input type="file" name="document" class="w-full text-[10px] text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-slate-900 transition-all cursor-pointer italic">
                                <p class="text-[8px] text-slate-400 mt-2 italic">*PDF/IMG, Maks 5MB</p>
                            </div>

                            <!-- Option 2: Link -->
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    <span class="text-[9px] font-black text-slate-400 uppercase italic tracking-widest">Tempel Link</span>
                                </div>
                                <input type="url" name="document_link" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-[10px] italic outline-none focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="https://drive.google.com/...">
                                <p class="text-[8px] text-slate-400 mt-2 italic">*Link Google Drive / Dropbox / dll.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('approval-requests.index') }}" class="w-full sm:w-auto text-center px-8 py-4 rounded-2xl font-black uppercase text-slate-400 hover:text-slate-600 tracking-widest transition italic border border-slate-200">Batal</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto py-4 px-10 group">
                             <span class="flex items-center justify-center gap-3 uppercase tracking-[0.2em] italic text-sm">
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
