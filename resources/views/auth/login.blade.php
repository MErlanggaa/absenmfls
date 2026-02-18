<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mfls Management | Login</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style type="text/tailwindcss">
        @layer components {
            .premium-glass {
                @apply bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl;
            }
            .btn-premium {
                background: linear-gradient(135deg, #6366f1, #a855f7);
                @apply relative overflow-hidden px-6 py-3 rounded-2xl font-bold transition-all duration-300 active:scale-95 flex items-center justify-center gap-2 text-white;
            }
            .input-premium {
                @apply bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all placeholder:text-slate-500;
            }
        }

        @keyframes floating-wobble {
            0%, 100% { transform: translateY(0) rotate(0); }
            25% { transform: translateY(-8px) rotate(-4deg); }
            50% { transform: translateY(0) rotate(0); }
            75% { transform: translateY(8px) rotate(4deg); }
        }
        .animate-premium-wobble {
            animation: floating-wobble 4s ease-in-out infinite;
        }
        .logo-container:hover {
            transform: scale(1.1) !important;
            filter: brightness(1.1);
        }
    </style>
</head>
<body class="bg-[#0f172a] overflow-hidden min-h-screen flex items-center justify-center p-6 font-sans">
    <!-- Animated background -->
    <div class="fixed top-0 left-0 w-full h-full pointer-events-none overflow-hidden z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-indigo-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-purple-600/20 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-lg relative z-10">
        <div class="premium-glass rounded-[3rem] p-12 shadow-2xl text-slate-200">
            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-white rounded-2xl mx-auto flex items-center justify-center shadow-xl shadow-indigo-500/20 mb-6 overflow-hidden border border-white/20 animate-premium-wobble logo-container transition-all duration-500">
                    <img src="{{ asset('loog.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <h2 class="text-3xl font-black tracking-tighter text-white mb-2 italic">Selamat <span class="text-indigo-400">Datang</span></h2>
                <p class="text-slate-400 font-medium">Silakan masuk ke portal manajemen MFLS</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-widest mb-2 pl-4 italic">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full input-premium" placeholder="nama@organisasi.com">
                </div>

                <div class="relative group/pass">
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-widest mb-2 pl-4 italic">Security Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required class="w-full input-premium pr-14" placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-slate-500 hover:text-indigo-400 transition-colors">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path id="eye-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path id="eye-bg-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between px-4">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-white/5 border-white/10 text-indigo-600 focus:ring-indigo-500 transition">
                        <span class="ml-2 text-xs font-medium text-slate-400 group-hover:text-slate-200 transition">Ingat saya</span>
                    </label>
                    <a href="javascript:void(0)" onclick="contactAdmin()" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition italic">Lupa password?</a>
                </div>

                <button type="submit" class="w-full btn-premium py-5 group">
                    <span class="relative z-10 flex items-center justify-center gap-2 uppercase tracking-widest italic">
                        Masuk Sekarang
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                </button>
            </form>
        </div>
        
        <!-- Premium Footer -->
        <footer class="mt-12 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] font-black uppercase tracking-[0.2em] italic text-slate-500 border-t border-white/5 pt-8">
            <div>
                @2026 MFLS . ALL RIGHT
            </div>
            <div>
                DEVELOP BY <a href="https://www.instagram.com/e_erlanggaa" target="_blank" class="text-indigo-400 hover:text-indigo-300 transition">MUHAMMAD ERLANGGA PUTRA WITANTO</a>
            </div>
        </footer>
    </div>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'LOGIN GAGAL!',
                    text: "{{ $errors->first() }}",
                    confirmButtonText: 'COBA LAGI',
                    background: '#1e293b',
                    color: '#fff',
                    customClass: {
                        popup: 'rounded-[3rem] border border-white/10 shadow-2xl backdrop-blur-xl',
                        confirmButton: 'rounded-2xl bg-indigo-600 px-8 py-3 font-black uppercase tracking-widest text-[10px] italic'
                    }
                });
            @endif

            @if(session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'INFO',
                    text: "{{ session('status') }}",
                    background: '#1e293b',
                    color: '#fff',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
        });

        function contactAdmin() {
            Swal.fire({
                icon: 'info',
                title: 'LUPA PASSWORD?',
                text: 'Silakan lapor ke Departemen IT atau hubungi Superadmin (admin@mfls.com) untuk melakukan reset password akun lo, Bos!',
                confirmButtonText: 'SIAP, MENGERTI',
                background: '#1e293b',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[3rem] border border-white/10 shadow-2xl backdrop-blur-xl',
                    confirmButton: 'rounded-2xl bg-indigo-600 px-8 py-3 font-black uppercase tracking-widest text-[10px] italic'
                }
            });
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyePath = document.getElementById('eye-path');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Change icon to eye-off (with a slash)
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>';
            } else {
                passwordInput.type = 'password';
                // Back to normal eye
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</body>
</html>
