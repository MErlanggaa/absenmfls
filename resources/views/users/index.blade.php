<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="space-y-6 pb-12">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h3 class="text-xl lg:text-2xl font-black text-slate-800 italic tracking-tighter uppercase leading-none">Manajemen Pengguna</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2 italic">Total: {{ $users->total() }} pengguna terdaftar</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn-primary w-full lg:w-auto py-4 shadow-xl shadow-indigo-100 flex justify-center text-center">
                <span class="uppercase text-xs tracking-[0.2em] italic">+ Tambah Pengguna</span>
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 text-green-700 rounded-2xl border border-green-100 italic text-xs font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 italic text-xs font-bold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card View for Mobile & Desktop -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($users as $user)
            <div class="premium-card p-6 flex flex-col relative overflow-hidden group">
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @if($user->is_active)
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[8px] font-black uppercase rounded-lg border border-green-100 italic tracking-widest flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Aktif</span>
                    @else
                        <span class="px-3 py-1 bg-red-50 text-red-600 text-[8px] font-black uppercase rounded-lg border border-red-100 italic tracking-widest">Nonaktif</span>
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 h-14 w-14 rounded-[1rem] bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm group-hover:scale-105 transition-transform">
                        <span class="font-black text-lg italic">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    </div>
                    <div class="min-w-0 pr-12">
                        <div class="text-sm font-black text-slate-800 italic uppercase truncate">{{ $user->name }}</div>
                        <div class="text-[10px] font-medium text-slate-500 truncate">{{ $user->email }}</div>
                        @if($user->phone)
                            <div class="text-[9px] font-bold text-slate-400 mt-0.5">{{ $user->phone }}</div>
                        @endif
                    </div>
                </div>

                <!-- Role & Department -->
                <div class="bg-slate-50 rounded-2xl p-4 mb-4 border border-slate-100 flex-1">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Role</span>
                            <span class="font-bold text-slate-700 text-[10px] uppercase">{{ $user->role->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Departemen</span>
                            <span class="font-bold text-slate-700 text-[10px] uppercase line-clamp-2">{{ $user->department->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-2 border-t border-slate-100 gap-2">
                    <div class="flex gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-100 transition shadow-sm border border-amber-100" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <a href="{{ route('users.show', $user->id) }}" class="p-2.5 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition shadow-sm border border-blue-100" title="Detail">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                    </div>
                    <form action="{{ route('users.toggle-active', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest italic transition shadow-sm border {{ $user->is_active ? 'bg-rose-50 text-rose-600 hover:bg-rose-100 border-rose-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border-emerald-100' }}">
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
