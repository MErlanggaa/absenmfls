<x-app-layout>
    <x-slot name="header">EDIT JADWAL RAPAT</x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />

    <div class="py-8 pb-20">
        <div class="max-w-5xl mx-auto">
            <form action="{{ route('events.update', $event->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left: Basic Info -->
                    <div class="premium-card">
                        <h3 class="text-xl font-black text-slate-900 italic tracking-tighter uppercase mb-6 flex items-center gap-2">
                             <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                             </div>
                             Informasi Utama
                        </h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Nama Rapat / Kegiatan</label>
                                <input type="text" name="name" class="w-full input-premium" value="{{ old('name', $event->name) }}" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Waktu Mulai</label>
                                    <input type="datetime-local" name="event_date" class="w-full input-premium text-sm" value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Durasi (Jam)</label>
                                    <input type="number" name="duration_hours" step="0.5" value="{{ old('duration_hours', \Carbon\Carbon::parse($event->end_date)->diffInHours(\Carbon\Carbon::parse($event->event_date))) }}" class="w-full input-premium" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Deskripsi Singkat</label>
                                <textarea name="description" rows="5" class="w-full input-premium" required>{{ old('description', $event->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Map Picker -->
                    <div class="premium-card">
                        <h3 class="text-xl font-black text-slate-900 italic tracking-tighter uppercase mb-6 flex items-center gap-2">
                             <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                             </div>
                             Lokasi & Radius
                        </h3>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Cari Nama Tempat / Alamat</label>
                                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                                    <input type="text" id="location_name" class="flex-1 input-premium" placeholder="Ketik alamat atau nama tempat...">
                                    <button type="button" id="btn-search-loc" class="px-8 py-4 bg-amber-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-amber-100 shrink-0">
                                        CARI LOKASI
                                    </button>
                                </div>
                            </div>

                            <!-- Map Picker Container -->
                            <div class="relative w-full h-[300px] rounded-3xl overflow-hidden border border-slate-200 shadow-inner z-0" id="map-picker"></div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Latitude</label>
                                    <input type="text" name="latitude" id="lat" value="{{ old('latitude', $event->location->latitude ?? '') }}" readonly class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-[10px] font-black text-amber-600 focus:outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 pl-4 italic">Longitude</label>
                                    <input type="text" name="longitude" id="lng" value="{{ old('longitude', $event->location->longitude ?? '') }}" readonly class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-[10px] font-black text-amber-600 focus:outline-none" required>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2 px-4">
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest italic">Radius Absensi (Meter)</label>
                                    <span id="radius-val" class="text-xs font-black text-amber-600 italic">{{ old('radius', $event->location->radius ?? 100) }}m</span>
                                </div>
                                <input type="range" name="radius" min="10" max="1000" value="{{ old('radius', $event->location->radius ?? 100) }}" step="10" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-600" id="radius-slider">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center gap-4">
                    <a href="{{ route('events.show', $event->id) }}" class="px-8 py-4 rounded-2xl font-black uppercase text-slate-400 hover:text-slate-600 tracking-widest transition italic">Batal</a>
                    <button type="submit" class="px-12 py-5 bg-amber-500 hover:bg-amber-600 text-white rounded-[2rem] font-black transition transform active:scale-95 shadow-xl shadow-amber-200 group">
                         <span class="flex items-center gap-3 uppercase tracking-[0.2em] italic text-sm">
                            Simpan Perubahan
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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
            const initialLat = {{ old('latitude', $event->location->latitude ?? -6.175110) }};
            const initialLng = {{ old('longitude', $event->location->longitude ?? 106.827088) }};
            const initialRadius = {{ old('radius', $event->location->radius ?? 100) }};
            
            const map = L.map('map-picker').setView([initialLat, initialLng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);
            let circle = L.circle([initialLat, initialLng], { radius: initialRadius, color: '#f59e0b', weight: 2 }).addTo(map);

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

            // Search functionality
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
        });
    </script>
</x-app-layout>
