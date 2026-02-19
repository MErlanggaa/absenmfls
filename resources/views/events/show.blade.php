<x-app-layout>
    <x-slot name="header">DETAIL RAPAT & KEGIATAN</x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-12 pb-32 lg:pb-20">
        <div class="max-w-7xl mx-auto space-y-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm animate-bounce text-sm font-bold italic">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Meeting Info -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="premium-card relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8">
                            @if($event->is_active)
                                <span class="bg-green-50 text-green-600 text-[10px] font-black uppercase px-4 py-2 rounded-full border border-green-100 tracking-widest italic">Aktif</span>
                            @else
                                <span class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase px-4 py-2 rounded-full border border-slate-100 tracking-widest italic">Selesai</span>
                            @endif
                        </div>

                        <div class="mb-10">
                            <span class="text-indigo-600 font-black text-[10px] tracking-[0.3em] uppercase mb-2 block italic">Agenda Details</span>
                            <h3 class="text-4xl font-black text-slate-900 mb-4 italic tracking-tighter uppercase">{{ $event->name }}</h3>
                            <p class="text-slate-500 leading-relaxed text-lg italic">{{ $event->description }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="flex items-center p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-indigo-600 mr-4 border border-slate-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest italic">Waktu</div>
                                    <div class="text-xs font-black text-slate-800 italic leading-none truncate">{{ $event->event_date->format('l, d F Y') }}</div>
                                    <div class="text-[10px] text-indigo-500 font-bold uppercase mt-1">{{ $event->event_date->format('H:i') }} WIB</div>
                                </div>
                            </div>

                            <div class="flex items-center p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-indigo-600 mr-4 border border-slate-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest italic">Lokasi</div>
                                    <div class="text-xs font-black text-slate-800 italic leading-none truncate">{{ $event->location->name ?? 'Lokasi Terdaftar' }}</div>
                                    <div class="text-[10px] text-indigo-500 font-bold uppercase mt-1 italic">{{ $event->location->radius }} Meter</div>
                                </div>
                            </div>
                        </div>

                        <!-- Map View -->
                        <div class="w-full h-80 rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-inner z-0" id="map-view"></div>
                    </div>

                    @if(auth()->user()->canViewAllAttendance())
                    <!-- Attendance Management / List (SPECIAL ACCESS ONLY) -->
                    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-10 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Monitoring Kehadiran</h3>
                                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mt-1 italic">Realtime Database Participation</p>
                            </div>
                            
                            @if($users)
                            <div class="relative w-full md:w-64">
                                <input type="text" id="memberSearch" placeholder="CARI ANGGOTA..." class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest italic outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            @endif

                            <span class="px-4 py-2 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full uppercase italic tracking-widest shrink-0">{{ $event->attendances->count() }} Terdaftar</span>
                        </div>

                        <div class="overflow-x-auto">
                            @if($users)
                                <!-- FULL USER LIST FOR ADMIN/KADEP -->
                                <table class="w-full text-left" id="attendanceTable">
                                    <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">
                                        <tr>
                                            <th class="px-10 py-6">Nama Anggota / Dept</th>
                                            <th class="px-10 py-6">Waktu Presensi</th>
                                            <th class="px-10 py-6 text-right">Status / Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @foreach($users as $user)
                                            @php $att = $event->attendances->where('user_id', $user->id)->first(); @endphp
                                            <tr class="hover:bg-slate-50/50 transition duration-300 member-row">
                                                <td class="px-10 py-6">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 rounded-xl {{ $att ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center font-black text-xs mr-4 transition-colors">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-black text-slate-800 italic uppercase member-name">{{ $user->name }}</div>
                                                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $user->department->name ?? 'MEMBER' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-10 py-6 text-[10px] font-black italic uppercase tracking-widest text-slate-400">
                                                    @if($att)
                                                        <span class="text-slate-800">{{ $att->check_in->format('H:i:s') }} WIB</span>
                                                    @else
                                                        --:--:--
                                                    @endif
                                                </td>
                                                <td class="px-10 py-6 text-right">
                                                    <div class="flex items-center justify-end gap-2">
                                                        @if(!$att || $att->status === 'hadir')
                                                        <form id="manual-att-{{ $user->id }}" action="{{ route('attendances.manual-store') }}" method="POST" class="flex gap-1">
                                                            @csrf
                                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                            <input type="hidden" name="status" id="status-{{ $user->id }}">
                                                            
                                                            <button type="button" onclick="confirmManualAttendance('{{ $user->id }}', '{{ $user->name }}', 'hadir')" class="px-3 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-widest italic transition-all {{ ($att && $att->status === 'hadir') ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'bg-slate-100 text-slate-400 hover:bg-green-500 hover:text-white' }}">Hadir</button>
                                                            <button type="button" onclick="confirmManualAttendance('{{ $user->id }}', '{{ $user->name }}', 'izin')" class="px-3 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-widest italic transition-all {{ ($att && $att->status === 'izin') ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-400 hover:bg-amber-500 hover:text-white' }}">Izin</button>
                                                            <button type="button" onclick="confirmManualAttendance('{{ $user->id }}', '{{ $user->name }}', 'sakit')" class="px-3 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-widest italic transition-all {{ ($att && $att->status === 'sakit') ? 'bg-rose-500 text-white' : 'bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white' }}">Sakit</button>
                                                        </form>
                                                        @else
                                                            <span class="px-4 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-widest italic {{ $att->status === 'sakit' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }}">
                                                                {{ $att->status }}
                                                            </span>
                                                            <button onclick="confirmManualAttendance('{{ $user->id }}', '{{ $user->name }}', 'hadir', 'Reset status absensi anggota ini?')" class="ml-2 text-[8px] font-black uppercase underline text-indigo-500 italic">Reset</button>
                                                            <form id="manual-att-{{ $user->id }}" action="{{ route('attendances.manual-store') }}" method="POST" class="hidden">
                                                                @csrf
                                                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                                <input type="hidden" name="status" value="hadir">
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Sidebar: QR / Scanner / My Status -->
                <div class="space-y-8">
                    @php
                        $user = auth()->user();
                        $canManage = $user->canManageEvents();
                        $myAttendance = $event->attendances->where('user_id', $user->id)->first();
                    @endphp

                    @if($canManage)
                        <!-- Admin/Administrasi: QR Generator -->
                        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[3rem] p-10 text-white shadow-2xl relative overflow-hidden text-center">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 -mr-16 -mt-16 rounded-full blur-2xl"></div>
                            <h4 class="text-lg font-black mb-8 relative italic tracking-widest uppercase">QR Presensi</h4>
                            
                            <div id="qr-display" class="w-48 h-48 bg-white/10 rounded-3xl mx-auto flex items-center justify-center border-2 border-dashed border-white/20 mb-8 backdrop-blur-sm group hover:scale-105 transition duration-500 overflow-hidden shadow-2xl">
                                <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l2 4h5l-4 4 1 5-4-3-4 3 1-5-4-4h5l2-4V4z"></path></svg>
                            </div>

                            <button onclick="generateQr()" class="w-full py-5 bg-white text-indigo-900 rounded-[2rem] font-black uppercase tracking-widest hover:bg-slate-50 transition transform active:scale-95 shadow-xl italic text-[10px]">
                                Generate QR Baru
                            </button>
                            <p class="mt-6 text-[9px] text-indigo-200 font-bold italic uppercase tracking-widest">QR aktif selama 60 menit</p>
                        </div>
                    @endif

                    <!-- User Presence Section -->
                    <div class="premium-card text-center p-10 shadow-lg">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-8 italic">Status Saya</h4>
                        
                        @if($myAttendance)
                            <div class="p-8 bg-green-50 rounded-[2.5rem] border border-green-100">
                                <div class="w-16 h-16 bg-green-500 rounded-2xl mx-auto flex items-center justify-center text-white mb-6 shadow-xl shadow-green-100 group">
                                    <svg class="w-8 h-8 group-hover:scale-110 transition duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="text-xl font-black text-green-900 italic tracking-tighter uppercase">{{ $myAttendance->status }}</div>
                                <div class="text-[9px] text-green-600 font-black uppercase mt-1 italic tracking-widest">{{ $myAttendance->check_in->format('H:i') }} WIB</div>
                            </div>
                        @elseif($event->is_active)
                            <button onclick="openScanner()" class="w-full py-6 bg-indigo-600 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition transform active:scale-95 shadow-xl shadow-indigo-100 flex items-center justify-center group italic text-[11px]">
                                <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l2 4h5l-4 4 1 5-4-3-4 3 1-5-4-4h5l2-4V4z"></path></svg>
                                SCAN QR ABSEN
                            </button>
                        @else
                            <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100">
                                <p class="text-[10px] font-black text-slate-300 uppercase italic tracking-widest">Akses Tutup</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scanner Modal Overlay -->
    <div id="scanner-modal" class="fixed inset-0 bg-slate-900/70 backdrop-blur-2xl z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-[3.5rem] p-8 lg:p-16 max-w-sm lg:max-w-4xl w-full relative shadow-2xl overflow-hidden text-center border-4 border-white/20">
            <button onclick="closeScanner()" class="absolute top-8 right-8 lg:top-12 lg:right-12 p-4 bg-slate-100 rounded-2xl text-slate-400 hover:text-red-500 transition shadow-sm z-50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-3xl lg:text-5xl font-black mb-3 text-slate-900 uppercase italic tracking-tighter">SCANNER AKTIF</h3>
            <p class="text-[11px] lg:text-sm text-slate-400 mb-10 lg:mb-16 font-black uppercase tracking-[0.3em] italic">Arahkan kamera ke QR Admin untuk presensi</p>
            
            <div class="relative w-full h-80 lg:h-[550px] bg-slate-950 rounded-[2.5rem] lg:rounded-[4rem] overflow-hidden shadow-2xl border-8 lg:border-[16px] border-slate-50">
                <div id="reader" class="w-full h-full"></div>
                <!-- Laser Scan Animation -->
                <div class="absolute inset-x-0 h-1 bg-indigo-500 shadow-[0_0_20px_#4f46e5,0_0_40px_#4f46e5] animate-laser z-20"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-indigo-500/10 to-transparent h-1/2 animate-laser-fade z-10"></div>
            </div>
            
            <form id="attendance-form" action="{{ route('attendance.check-in') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="qr_code" id="qr_input">
                <input type="hidden" name="latitude" id="lat_input">
                <input type="hidden" name="longitude" id="lng_input">
            </form>
        </div>
    </div>

    <style>
        @keyframes laser {
            0% { top: 0%; }
            100% { top: 100%; }
        }
        @keyframes laser-fade {
            0% { top: -50%; }
            100% { top: 100%; }
        }
        .animate-laser { animation: laser 3s linear infinite; }
        .animate-laser-fade { animation: laser-fade 3s linear infinite; }
    </style>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $event->location->latitude }};
            const lng = {{ $event->location->longitude }};
            const radius = {{ $event->location->radius }};
            
            const map = L.map('map-view', { zoomControl: false, attributionControl: false }).setView([lat, lng], 17);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            L.marker([lat, lng]).addTo(map);
            L.circle([lat, lng], { radius: radius, color: '#4f46e5', weight: 2, fillOpacity: 0.15 }).addTo(map);
        });

        function generateQr() {
            fetch("{{ route('events.qrcode', $event->id) }}")
                .then(response => response.json())
                .then(data => {
                    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${data.code}&color=4338ca&bgcolor=ffffff`;
                    document.getElementById('qr-display').innerHTML = `<img src="${qrUrl}" class="w-full h-full object-cover p-2 rounded-2xl" alt="QR Code" />`;
                });
        }

        // Manual Attendance Confirmation
        function confirmManualAttendance(userId, userName, status, customMsg = null) {
            const statusText = status === 'hadir' ? 'HADIR' : (status === 'izin' ? 'IZIN' : 'SAKIT');
            const msg = customMsg || `Yakin ingin mengabsenkan **${userName}** sebagai **${statusText}**?`;

            Swal.fire({
                title: 'KONFIRMASI ABSEN',
                html: msg,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#f43f5e',
                confirmButtonText: 'YA, PROSES!',
                cancelButtonText: 'BATAL',
                background: '#ffffff',
                customClass: {
                    title: 'text-sm font-black italic uppercase tracking-tighter',
                    htmlContainer: 'text-[11px] font-bold uppercase tracking-widest text-slate-500 italic py-4',
                    confirmButton: 'premium-swal-button',
                    cancelButton: 'premium-swal-button'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('manual-att-' + userId);
                    const statusInput = document.getElementById('status-' + userId);
                    if (statusInput) statusInput.value = status;
                    form.submit();
                }
            });
        }

        // Live Search Member
        const searchInput = document.getElementById('memberSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const term = this.value.toLowerCase();
                const rows = document.querySelectorAll('.member-row');
                
                rows.forEach(row => {
                    const name = row.querySelector('.member-name').textContent.toLowerCase();
                    if (name.includes(term)) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });
        }

        function openScanner() {
            const scannerModal = document.getElementById('scanner-modal');
            scannerModal.classList.remove('hidden');
            
            // Show loading state in reader
            document.getElementById('reader').innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-white gap-4">
                    <div class="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-[10px] font-black uppercase tracking-widest italic animate-pulse">Menghubungkan Satelit & Kamera...</p>
                </div>
            `;

            if (!navigator.geolocation) {
                Swal.fire('ERROR', 'Browser lo nggak support GPS, Bos!', 'error');
                closeScanner();
                return;
            }

            navigator.geolocation.getCurrentPosition(position => {
                document.getElementById('lat_input').value = position.coords.latitude;
                document.getElementById('lng_input').value = position.coords.longitude;
                
                // Clear loading
                document.getElementById('reader').innerHTML = "";

                // Use Html5Qrcode for manual camera control
                const html5QrCode = new Html5Qrcode("reader");
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                // Success callback
                const onScanSuccess = (decodedText) => {
                    html5QrCode.stop().then(() => {
                        document.getElementById('qr_input').value = decodedText;
                        document.getElementById('attendance-form').submit();
                    }).catch(error => {
                        console.error("Failed to stop scanner", error);
                        document.getElementById('qr_input').value = decodedText;
                        document.getElementById('attendance-form').submit();
                    });
                };

                // Try rear camera first (environment)
                html5QrCode.start(
                    { facingMode: "environment" }, 
                    config, 
                    onScanSuccess
                ).catch(err => {
                    console.error("Rear camera failed, trying fallback...", err);
                    // Fallback to ANY available camera (usually front if rear fails)
                    html5QrCode.start(
                        { facingMode: "user" }, 
                        config, 
                        onScanSuccess
                    ).catch(finalErr => {
                        Swal.fire({
                            icon: 'error',
                            title: 'KAMERA GAGAL',
                            text: 'Cek izin kamera atau pastikan Bos pakai HTTPS/Localhost: ' + finalErr,
                            confirmButtonText: 'SIAP'
                        });
                        closeScanner();
                    });
                });

                window.scannerInstance = html5QrCode;

            }, error => {
                let msg = 'Izin lokasi (GPS) ditolak, Bos! Aktifin di setting browser ya.';
                if (error.code === error.PERMISSION_DENIED) {
                    msg = 'Akses lokasi ditolak! Tanpa GPS, sistem nggak bisa verifikasi posisi lo.';
                }
                Swal.fire('GPS BLOCKED', msg, 'error');
                closeScanner();
            }, { enableHighAccuracy: true, timeout: 5000 });
        }

        function closeScanner() {
            document.getElementById('scanner-modal').classList.add('hidden');
            if (window.scannerInstance) {
                window.scannerInstance.stop().catch(err => console.error("Scanner stop fail", err));
            }
        }
    </script>
</x-app-layout>
