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

        <!-- Debug Notification Section -->
        <div class="premium-card mt-6 border-2 border-indigo-100 bg-indigo-50/50">
            <h4 class="text-sm font-black text-indigo-900 uppercase italic tracking-tighter mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Debug Notifikasi (Android)
            </h4>
            
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest italic ml-4">Status & Token FCM</label>
                    <textarea id="fcm-token-display" readonly rows="4" class="w-full input-premium bg-white font-mono text-[10px] text-slate-500" placeholder="Token akan muncul di sini..."></textarea>
                </div>

                <div class="flex gap-2">
                    <button onclick="checkFcm()" type="button" class="flex-1 py-3 bg-indigo-600 text-white rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Cek Token Saya
                    </button>
                    <button onclick="copyToken()" type="button" class="px-4 py-3 bg-white text-indigo-600 border border-indigo-100 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-indigo-50 transition">
                        Copy
                    </button>
                </div>
                
                <p class="text-[9px] text-indigo-400 italic font-bold">
                    *Tekan "Cek Token", jika muncul teks panjang, kirim ke Admin buat dicek.
                </p>
            </div>
        </div>

        <script>
            // We don't import here because firebase-push.js is already loaded in app layout
            
            // Helper to wait for manager
            function waitForManager() {
                return new Promise((resolve, reject) => {
                    if (window.firebasePushManager) return resolve(window.firebasePushManager);
                    
                    let retries = 0;
                    const interval = setInterval(() => {
                        retries++;
                        if (window.firebasePushManager) {
                            clearInterval(interval);
                            resolve(window.firebasePushManager);
                        }
                        if (retries > 10) { // 5 seconds timeout
                            clearInterval(interval);
                            reject("Timeout: Firebase Manager lambat loading.");
                        }
                    }, 500);
                });
            }

            window.checkFcm = async function() {
                const display = document.getElementById('fcm-token-display');
                display.value = "Menunggu sistem siap...";
                
                try {
                    const manager = await waitForManager();
                    display.value = "Memeriksa Token...";

                    // Re-request permission just in case
                    const permission = await Notification.requestPermission();
                    if (permission !== 'granted') {
                        display.value = "Ijin Notifikasi DITOLAK (Denied). Buka setting browser HP -> Site Settings -> Notifications -> Allow.";
                        return;
                    }

                    // Manually get token using SDK referenced in global scope or wait for manager's messaging
                    // Since manager uses modules internally, we access its public 'messaging' property 
                    // But we need 'getToken' function. 
                    // Easiest way: Use the manager's messaging instance directly
                    
                    // Dynamic import to get getToken function
                    const { getToken } = await import('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js');
                    
                    const token = await getToken(manager.messaging, {
                        vapidKey: manager.vapidKey,
                        serviceWorkerRegistration: await navigator.serviceWorker.ready
                    });

                    if (token) {
                        display.value = token;
                    } else {
                        display.value = "Gagal mendapatkan token. Coba clear cache browser.";
                    }
                } catch (err) {
                    display.value = "Error: " + err;
                    console.error(err);
                }
            };

            window.copyToken = function() {
                const copyText = document.getElementById("fcm-token-display");
                copyText.select();
                document.execCommand("copy");
                alert("Token dicopy!");
            }
        </script>
    </div>
</x-app-layout>
