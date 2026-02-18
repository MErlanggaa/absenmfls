<x-app-layout>
    <x-slot name="header">PENGATURAN KEAMANAN</x-slot>

    <div class="space-y-6 pb-12 lg:max-w-2xl lg:mx-auto">
        <!-- Header Profile Card -->
        <div class="premium-card bg-gradient-to-br from-indigo-600 to-indigo-800 text-white border-none p-8 relative overflow-hidden shadow-xl shadow-indigo-100">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 -mr-10 -mt-10 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-[2rem] border border-white/30 flex items-center justify-center text-3xl font-black italic shadow-2xl">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-2xl font-black italic tracking-tighter uppercase leading-none">{{ auth()->user()->name }}</h3>
                    <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mt-2 bg-white/10 inline-block px-3 py-1 rounded-full italic">
                        {{ auth()->user()->department?->name ?? auth()->user()->role->name }} &bull; {{ auth()->user()->email }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Section: Change Password (ONLY FEATURE) -->
        <div class="premium-card">
            <div class="flex items-center gap-4 mb-8">
                 <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center border border-rose-100 italic font-black text-xs">PW</div>
                 <div>
                    <h4 class="text-sm font-black text-slate-800 uppercase italic tracking-tighter">Ganti Password</h4>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Satu-satunya cara biar akun lo tetep privat, Bos!</p>
                 </div>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic ml-4">Password Saat Ini</label>
                    <input type="password" name="current_password" class="w-full input-premium bg-slate-50" autocomplete="current-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-[10px] font-bold text-rose-500 ml-4 uppercase" />
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic ml-4">Password Baru</label>
                    <input type="password" name="password" class="w-full input-premium bg-slate-50" autocomplete="new-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-[10px] font-bold text-rose-500 ml-4 uppercase" />
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic ml-4">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full input-premium bg-slate-50" autocomplete="new-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-[10px] font-bold text-rose-500 ml-4 uppercase" />
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-primary bg-gradient-to-r from-rose-500 to-rose-600 w-full py-4 shadow-lg shadow-rose-100 uppercase text-[10px] italic tracking-widest">
                        Update Password Sekarang
                    </button>
                    <p class="text-center text-[8px] font-black text-slate-300 uppercase tracking-[0.2em] mt-4">
                        Data profil lainnya dikunci oleh sistem
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
