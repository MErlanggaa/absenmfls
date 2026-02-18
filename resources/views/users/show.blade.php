<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Card -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-24"></div>
                <div class="px-6 pb-6">
                    <div class="flex items-end -mt-12 mb-4">
                        <div class="h-20 w-20 rounded-full bg-white border-4 border-white shadow-md flex items-center justify-center">
                            <span class="text-2xl font-bold text-indigo-700">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                        <div class="ml-4 mb-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                        </div>
                        <div class="ml-auto mb-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">Edit</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-500">Role</p>
                            <p class="font-semibold text-gray-800 text-sm">{{ $user->role->name ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-500">Departemen</p>
                            <p class="font-semibold text-gray-800 text-sm">{{ $user->department->name ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-500">Telepon</p>
                            <p class="font-semibold text-gray-800 text-sm">{{ $user->phone ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-500">Status</p>
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance History -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h4 class="font-bold text-gray-800">Riwayat Kehadiran ({{ $user->attendances->count() }} event)</h4>
                </div>
                <div class="p-6">
                    @if($user->attendances->isEmpty())
                        <p class="text-gray-400 text-sm text-center py-4">Belum ada riwayat kehadiran.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase pb-2">Event</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase pb-2">Check In</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase pb-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($user->attendances->take(10) as $att)
                                <tr>
                                    <td class="py-2 text-sm text-gray-900">{{ $att->event->name ?? '-' }}</td>
                                    <td class="py-2 text-sm text-gray-500">{{ $att->check_in->format('d M Y H:i') }}</td>
                                    <td class="py-2">
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800">{{ ucfirst($att->status) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Approval Requests -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h4 class="font-bold text-gray-800">Pengajuan Approval ({{ $user->approvalRequests->count() }})</h4>
                </div>
                <div class="p-6">
                    @if($user->approvalRequests->isEmpty())
                        <p class="text-gray-400 text-sm text-center py-4">Belum ada pengajuan.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($user->approvalRequests->take(5) as $req)
                            <li class="flex justify-between items-center py-2 border-b border-gray-50">
                                <span class="text-sm text-gray-900">{{ $req->title }}</span>
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $req->status === 'approved' ? 'bg-green-100 text-green-800' : ($req->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="flex justify-start">
                <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm">← Kembali ke Daftar</a>
            </div>

        </div>
    </div>
</x-app-layout>
