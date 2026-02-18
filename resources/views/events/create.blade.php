<x-app-layout>
    <x-slot name="header">BUAT JADWAL RAPAT BARU</x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />

    <div class="py-8 pb-20">
        <div class="max-w-5xl mx-auto">
            <form action="{{ route('events.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left: Basic Info -->
                    <div class="premium-card">
                        <h3 class="text-xl font-black text-slate-900 italic tracking-tighter uppercase mb-6 flex items-center gap-2">
                             <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                             </div>
                             Informasi Utama
                        </h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Nama Rapat / Kegiatan</label>
                                <input type="text" name="name" class="w-full input-premium" placeholder="Contoh: Rapat Mingguan Divisi..." required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Waktu Mulai</label>
                                    <input type="datetime-local" name="event_date" class="w-full input-premium text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Durasi (Jam)</label>
                                    <input type="number" name="duration_hours" step="0.5" value="2" class="w-full input-premium" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-4 pl-4 italic">Target Undangan Rapat</label>
                                
                                <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100 space-y-4">
                                    <div class="flex items-center gap-3 pb-3 border-b border-slate-200">
                                        <input type="checkbox" id="select-all-depts" class="w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="select-all-depts" class="text-xs font-black text-slate-700 uppercase italic cursor-pointer">PILIH SEMUA DEPARTEMEN</label>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                        @foreach($departments as $dept)
                                            <div class="flex items-center gap-3 group">
                                                <input type="checkbox" name="target_departments[]" value="{{ $dept->id }}" id="dept-{{ $dept->id }}" class="dept-checkbox w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all group-hover:scale-110">
                                                <label for="dept-{{ $dept->id }}" class="text-xs font-bold text-slate-600 uppercase cursor-pointer group-hover:text-indigo-600 transition-colors">
                                                    {{ $dept->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="mt-3 text-[9px] text-slate-400 italic px-4 flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Jika tidak ada yang dicentang, rapat otomatis untuk SEMURAH (Seluruh Anggota).
                                </p>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Deskripsi Singkat</label>
                                <textarea name="description" rows="3" class="w-full input-premium" placeholder="Tujuan rapat, agenda, atau instruksi peserta..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Map Picker -->
                    <div class="premium-card">
                        <h3 class="text-xl font-black text-slate-900 italic tracking-tighter uppercase mb-6 flex items-center gap-2">
                             <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                             </div>
                             Lokasi & Radius
                        </h3>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Cari Nama Tempat / Alamat</label>
                                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                                    <input type="text" name="location_name" id="location_name" class="flex-1 input-premium" placeholder="Ketik alamat atau nama tempat..." required>
                                    <button type="button" id="btn-search-loc" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-indigo-100 shrink-0">
                                        CARI LOKASI
                                    </button>
                                </div>
                            </div>

                            <!-- Map Picker Container -->
                            <div class="relative w-full h-[300px] rounded-3xl overflow-hidden border border-slate-200 shadow-inner z-0" id="map-picker"></div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Latitude</label>
                                    <input type="text" name="latitude" id="lat" readonly class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-[10px] font-black text-indigo-600 focus:outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Longitude</label>
                                    <input type="text" name="longitude" id="lng" readonly class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-[10px] font-black text-indigo-600 focus:outline-none" required>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2 px-4">
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest italic">Radius Absensi (Meter)</label>
                                    <span id="radius-val" class="text-xs font-black text-indigo-600 italic">100m</span>
                                </div>
                                <input type="range" name="radius" min="10" max="1000" value="100" step="10" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600" id="radius-slider">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center gap-4">
                    <a href="{{ route('events.index') }}" class="px-8 py-4 rounded-2xl font-black uppercase text-slate-400 hover:text-slate-600 tracking-widest transition italic">Batal</a>
                    <button type="submit" class="btn-primary py-5 px-12 group">
                         <span class="flex items-center gap-3 uppercase tracking-[0.2em] italic text-sm">
                            Publish Rapat
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initial coordinates (Jakarta or dynamic)
            const initialLat = -6.175110;
            const initialLng = 106.827088;
            
            const map = L.map('map-picker').setView([initialLat, initialLng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);
            let circle = L.circle([initialLat, initialLng], { radius: 100, color: '#4f46e5', weight: 2 }).addTo(map);

            function updateInputs(lat, lng) {
                document.getElementById('lat').value = lat.toFixed(8);
                document.getElementById('lng').value = lng.toFixed(8);
            }

            // Sync marker position to inputs
            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
                circle.setLatLng(pos);
                map.panTo(pos);
            });

            // Click on map to set marker
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                circle.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            // Radius Slider
            const slider = document.getElementById('radius-slider');
            slider.addEventListener('input', function() {
                const val = parseInt(this.value);
                document.getElementById('radius-val').innerText = val + 'm';
                circle.setRadius(val);
            });

            // Search functionality tied to button
            const provider = new window.GeoSearch.OpenStreetMapProvider();
            
            async function searchAddress() {
                const query = document.getElementById('location_name').value;
                if (!query) return;

                const results = await provider.search({ query: query });
                if (results && results.length > 0) {
                    const firstResult = results[0];
                    const pos = { lat: firstResult.y, lng: firstResult.x };
                    
                    marker.setLatLng(pos);
                    circle.setLatLng(pos);
                    map.flyTo(pos, 16);
                    updateInputs(pos.lat, pos.lng);
                    
                    // Update input with full label if needed
                    // document.getElementById('location_name').value = firstResult.label;
                } else {
                    alert('Lokasi tidak ditemukan, coba alamat lain.');
                }
            }

            document.getElementById('btn-search-loc').addEventListener('click', searchAddress);
            document.getElementById('location_name').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchAddress();
                }
            });

            updateInputs(initialLat, initialLng);

            // Select All Departments Logic
            const selectAll = document.getElementById('select-all-depts');
            const deptCheckboxes = document.querySelectorAll('.dept-checkbox');

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    deptCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                });
            }
        });
    </script>
</x-app-layout>
