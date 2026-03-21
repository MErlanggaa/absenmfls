<x-app-layout>
    <x-slot name="header">
        {{ __('HASIL PENILAIAN KPI') }}
    </x-slot>

    <div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 overflow-x-hidden">
        <div class="premium-card mb-6 border-t-4 border-indigo-600">
            <div class="flex flex-col md:flex-row items-center gap-6 mb-8 border-b border-slate-100 pb-6">
                <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100 border border-slate-100 p-2 shrink-0">
                    <img src="{{ asset('loog.jpeg') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div class="text-center md:text-left flex-1">
                    <h2 class="text-xs text-slate-500 font-black tracking-widest uppercase mb-1">UNIVERSITAS MEDIA NUSANTARA CITRA</h2>
                    <h1 class="text-xl md:text-2xl text-indigo-900 font-black tracking-tighter uppercase mb-2">PANITIA MNCU FUTURE LEADER SCHOLARSHIP</h1>
                    <p class="text-xs text-slate-500 font-medium">Jl. Panjang Blok A8, Jl. Green Garden Pintu Utara RT.1/RW.3, Kedoya Utara Email: beasiswaakbarmncuniversity@gmail.com</p>
                </div>
            </div>

            <div class="text-center mb-10">
                <h3 class="text-xl font-black italic text-slate-800 uppercase tracking-widest border-b-2 border-slate-900 inline-block pb-1">HASIL KEY PERFORMANCE INDICATOR (KPI)</h3>
                <h4 class="text-lg text-slate-700 font-bold uppercase mt-2">MNCU FUTURE LEADER SCHOLARSHIP</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-xs lg:text-sm font-bold text-slate-700">
                <div class="flex items-start">
                    <div class="w-40 shrink-0">NAMA ANGGOTA</div>
                    <div class="mr-2">:</div>
                    <div class="uppercase text-indigo-700 break-words flex-1">{{ $kpi->user->name }}</div>
                </div>
                <div class="flex items-start">
                    <div class="w-40 shrink-0">DEPARTEMEN</div>
                    <div class="mr-2">:</div>
                    <div class="uppercase text-indigo-700 break-words flex-1">{{ $kpi->user->department->name ?? '-' }}</div>
                </div>
                <div class="flex items-start">
                    <div class="w-40 shrink-0">PERIODE PENILAIAN</div>
                    <div class="mr-2">:</div>
                    <div class="uppercase text-indigo-700 flex-1">{{ \Carbon\Carbon::parse($kpi->period_date)->translatedFormat('F Y') }}</div>
                </div>
                <div class="flex items-start">
                    <div class="w-40 shrink-0">NAMA PENILAI</div>
                    <div class="mr-2">:</div>
                    <div class="uppercase text-indigo-700 break-words flex-1">{{ $kpi->assessor->name }}</div>
                </div>
            </div>
        </div>

        @php
            $categories = [
                'A' => [
                    'title' => 'A. SPECIFIC (25%)',
                    'name' => 'category_A',
                    'aspects' => [
                        ['Pemahaman Peran Dan Tugas', [
                            'Memahami tugas dan tanggung jawab sesuai departemen dalam struktur MFLS',
                            'Mengetahui target kerja yang harus dicapai dalam periode bulanan',
                            'Menjalankan tugas sesuai pembagian kerja yang telah ditetapkan'
                        ]],
                        ['Pelaksanaan Tugas Harian', [
                            'Menyelesaikan kegiatan kegiatan kerja harian sesuai rencana kerja bulanan',
                            'Melaksanakan instruksi kerja sesuai arahan koordinator'
                        ]],
                        ['Kejelasan Output Kerja', [
                            'Menyelesaikan pekerjaan sesuai format dan standar MFLS',
                            'Menghasilkan output kerja yang jelas dan sesuai kebutuhan program'
                        ]]
                    ]
                ],
                'B' => [
                    'title' => 'B. MEASURABLE (25%)',
                    'name' => 'category_B',
                    'aspects' => [
                        ['Pencapaian Target Kuantitatif', [
                            'Menunjukkan hasil kerja yang dapat dihitung atau diukur',
                            'Memantau progres capaian pekerjaan secara berkala'
                        ]],
                        ['Kualitas dan Akurasi Hasil Kerja', [
                            'Meminimalkan kesalahan dan revisi dalam pekerjaan',
                            'Melakukan pengecekan ulang sebelum menyerahkan hasil kerja',
                            'Menghasilkan output sesuai standar kualitas MFLS'
                        ]],
                        ['Pelaporan dan Dokumentasi', [
                            'Menyusun laporan pekerjaan secara terstruktur',
                            'Menyelesaikan dan menyampaikan tugas secara akurat sesuai data target pencapaian yang telah ditetapkan',
                            'Menyimpan dokumentasi kegiatan dengan tertib'
                        ]]
                    ]
                ],
                'C' => [
                    'title' => 'C. ACHIEVABLE (20%)',
                    'name' => 'category_C',
                    'aspects' => [
                        ['Manajemen Waktu', [
                            'Dapat Mengatur jadwal kerja harian dan mingguan',
                            'Mengatur waktu dan tidak datang terlambat saat ada pertemuan'
                        ]],
                        ['Pengelolaan Beban Kerja', [
                            'Menghindari penumpukan pekerjaan di akhir periode',
                            'Menentukan prioritas pekerjaan berdasarkan urgensi',
                            'Menyesuaikan target kerja dengan kondisi dan sumber daya',
                            'Menyampaikan kendala pekerjaan kepada koordinator'
                        ]],
                        ['Pemanfaatan Dukungan Tim', [
                            'Berkoordinasi aktif dengan tim atau departemen lain',
                            'Meminta bantuan atau arahan ketika diperlukan',
                            'Bersedia membantu anggota tim lain sesuai kapasitas'
                        ]]
                    ]
                ],
                'D' => [
                    'title' => 'D. RELEVANT (15%)',
                    'name' => 'category_D',
                    'aspects' => [
                        ['Kontribusi terhadap Target Program', [
                            'Mendahulukan pekerjaan yang berdampak langsung pada kegiatan MFLS',
                            'Berkontribusi aktif dalam kegiatan utama program',
                            'Menunjukkan komitmen terhadap keberhasilan MFLS'
                        ]],
                        ['Keselarasan dengan Visi dan Nilai MFLS', [
                            'Menjalankan tugas sesuai nilai kepemimpinan MFLS',
                            'Mendukung kebijakan dan keputusan project director',
                            'Menjaga etika dan sikap profesional'
                        ]],
                        ['Representasi dan Citra Program', [
                            'Menjaga nama baik dan menghindari tindakan yang dapat merugikan citra MFLS',
                            'Bersikap profesional saat berinteraksi dengan pihak eksternal'
                        ]]
                    ]
                ],
                'E' => [
                    'title' => 'E. TIME-BOUND (15%)',
                    'name' => 'category_E',
                    'aspects' => [
                        ['Ketepatan Waktu Pelaksanaan', [
                            'Menyelesaikan tugas sesuai timeline yang telah ditetapkan',
                            'Tidak menunda pekerjaan hingga mendekati tenggat waktu',
                            'Menghormati jadwal kegiatan yang telah disepakati'
                        ]],
                        ['Ketepatan Waktu Pelaporan', [
                            'Menyusun laporan sebelum batas waktu pengumpulan',
                            'Menindaklanjuti evaluasi dalam periode berjalan',
                            'Menyerahkan laporan pekerjaan tepat waktu setiap akhir bulan'
                        ]],
                        ['Responsivitas Deadline', [
                            'Segera menindaklanjuti tugas dengan tenggat singkat',
                            'Menyelesaikan perbaikan atau revisi sesuai waktu yang ditentukan'
                        ]]
                    ]
                ]
            ];
        @endphp

        @foreach($categories as $catKey => $cat)
        <div class="premium-card mb-6 p-0 flex flex-col w-full overflow-hidden">
            <div class="bg-indigo-600 text-white font-black px-4 sm:px-6 py-4 uppercase tracking-widest text-sm shadow-md flex flex-wrap gap-2 justify-between items-center rounded-t-2xl">
                <span>{{ $cat['title'] }}</span>
                <span class="bg-white text-indigo-600 px-3 py-1 rounded-lg text-xs font-black">
                    @php
                        $scores = $kpi->behavior_scores[$cat['name']] ?? [];
                        $totalCatScore = array_sum($scores);
                        echo "TOTAL NILAI: " . $totalCatScore;
                    @endphp
                </span>
            </div>
            
            <div class="w-full bg-white rounded-b-2xl border-t border-slate-100">
                <table class="w-full text-sm text-left border-collapse bg-white block md:table">
                    <thead class="hidden md:table-header-group">
                        <tr class="bg-slate-50 border-b border-indigo-100">
                            <th class="p-3 border-r border-slate-200 text-center w-12 text-slate-500">NO</th>
                            <th class="p-3 border-r border-slate-200 w-48 text-indigo-900 font-bold uppercase text-[10px]">Aspek Perilaku</th>
                            <th class="p-3 border-r border-slate-200 font-bold text-indigo-900 uppercase text-[10px]">Contoh Perilaku</th>
                            <th class="p-3 border-r border-slate-200 text-center w-40 text-indigo-900 font-bold uppercase text-[10px]">
                                Capaian Kinerja (Level)<br/>
                                <div class="flex justify-between mt-2 pt-2 border-t border-slate-300">
                                    <span class="w-1/4 text-center text-red-500 font-black">1</span>
                                    <span class="w-1/4 text-center text-orange-500 font-black">2</span>
                                    <span class="w-1/4 text-center text-blue-500 font-black">3</span>
                                    <span class="w-1/4 text-center text-green-500 font-black">4</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group">
                        @php $qIndex = 0; @endphp
                        @foreach($cat['aspects'] as $aspectIndex => $aspect)
                            @php $rowspan = count($aspect[1]); @endphp
                            
                            <!-- Mobile Category Header -->
                            <tr class="block md:hidden bg-slate-50/80 border-b border-indigo-100/50 p-4 shadow-sm">
                                <td class="block w-full text-xs font-black text-indigo-900 uppercase tracking-widest leading-relaxed">
                                    {{ $aspectIndex + 1 }}. {{ $aspect[0] }}
                                </td>
                            </tr>
                            
                            @foreach($aspect[1] as $bIndex => $behavior)
                            @php
                                $selectedVal = $scores[$qIndex] ?? 0;
                            @endphp
                            <tr class="block md:table-row border-b border-slate-100 transition hover:bg-slate-50 p-5 md:p-0">
                                @if($bIndex == 0)
                                    <td rowspan="{{ $rowspan }}" class="hidden md:table-cell p-3 border-r border-slate-200 text-center font-bold text-slate-400 bg-white">
                                        {{ $aspectIndex + 1 }}
                                    </td>
                                    <td rowspan="{{ $rowspan }}" class="hidden md:table-cell p-3 border-r border-slate-200 font-medium text-slate-700 bg-white">
                                        {{ $aspect[0] }}
                                    </td>
                                @endif
                                
                                <td class="block md:table-cell p-0 md:p-3 pb-4 md:pb-3 border-r-0 md:border-r border-slate-200 text-slate-600 text-sm md:text-sm font-medium md:font-normal leading-relaxed">
                                    {{ $behavior }}
                                </td>
                                
                                <td class="block md:table-cell p-0 md:p-0 border-r-0 md:border-r border-slate-200 mt-2 md:mt-0">
                                    <div class="md:hidden text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Kinerja (Level)</div>
                                    <div class="flex h-12 md:h-full md:min-h-[48px] w-full border border-slate-200 md:border-0 rounded-xl md:rounded-none overflow-hidden bg-slate-50 md:bg-transparent">
                                        @for($lvl=1; $lvl<=4; $lvl++)
                                            <div class="flex-1 md:w-1/4 border-r last:border-r-0 border-slate-200 flex items-center justify-center p-0 md:p-2 relative 
                                                {{ $selectedVal == $lvl ? 'bg-indigo-100 ring-0 md:ring-2 md:ring-indigo-500 inset-0 z-10' : 'bg-slate-50/50' }}">
                                                
                                                <!-- Desktop Indicator -->
                                                <div class="hidden md:flex">
                                                    @if($selectedVal == $lvl)
                                                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center font-black text-xs">
                                                            {{ $lvl }}
                                                        </span>
                                                    @else
                                                        <span class="w-4 h-4 rounded-full border-2 border-slate-300"></span>
                                                    @endif
                                                </div>

                                                <!-- Mobile Indicator -->
                                                <span class="md:hidden z-10 text-xs font-black relative {{ $selectedVal == $lvl ? 'text-indigo-700' : 'text-slate-400' }}">{{ $lvl }}</span>
                                                
                                                @if($selectedVal == $lvl)
                                                    <div class="md:hidden absolute inset-0 z-0 bg-indigo-100 border-2 border-indigo-500 rounded-none"></div>
                                                @endif
                                            </div>
                                        @endfor
                                    </div>
                                </td>
                            </tr>
                            @php $qIndex++; @endphp
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        <!-- HASIL AKHIR -->
        <div class="premium-card mb-10 overflow-hidden p-0 border border-indigo-200">
            <div class="bg-indigo-50 px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h3 class="text-2xl font-black italic text-indigo-900 uppercase tracking-tighter mb-1">TOTAL PENILAIAN</h3>
                    <p class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Kalkulasi Formula MFLS KPI</p>
                </div>
                
                <div class="flex items-center gap-8">
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">TOTAL SKOR</p>
                        <p class="text-4xl font-black text-indigo-600">{{ $kpi->total_value }}</p>
                    </div>

                    <div class="w-px h-16 bg-indigo-200"></div>

                    <div class="text-left w-64">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">INDEKS KETERANGAN</p>
                        @if($kpi->index_score === 'Mencapai Target')
                            <p class="text-2xl font-black text-green-500 uppercase tracking-tight">{{ $kpi->index_score }}</p>
                        @elseif($kpi->index_score === 'Perlu Evaluasi')
                            <p class="text-2xl font-black text-yellow-500 uppercase tracking-tight">{{ $kpi->index_score }}</p>
                        @else
                            <p class="text-2xl font-black text-red-500 uppercase tracking-tight">{{ $kpi->index_score }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- SIGNATURES SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <!-- PD SIGNATURE (LEFT) -->
            <div class="premium-card p-6 flex flex-col items-center justify-start text-center h-full">
                <h4 class="text-xs font-black uppercase text-indigo-900 tracking-widest mb-4">Project Director</h4>
                @if($kpi->pd_signature)
                    <div class="w-48 h-32 border-b-2 border-slate-800 mb-2 flex items-center justify-center">
                        @if($kpi->pd_signature === 'default_pd_signature')
                            <img src="{{ asset('image.png') }}" alt="Tanda Tangan PD" class="max-w-full max-h-full object-contain">
                        @else
                            <img src="{{ asset('storage/' . $kpi->pd_signature) }}" alt="Tanda Tangan PD" class="max-w-full max-h-full object-contain">
                        @endif
                    </div>
                    <p class="font-bold text-slate-700 uppercase mb-4">{{ $pd->name ?? 'Project Director' }}</p>

                    @if($kpi->pd_notes)
                        <div class="w-full mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100 text-left">
                            <h5 class="text-[10px] font-black uppercase text-amber-800 tracking-widest mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Catatan / Saran Project Director
                            </h5>
                            <p class="text-sm text-slate-700 font-medium italic">"{{ $kpi->pd_notes }}"</p>
                        </div>
                    @endif
                @else
                    @if(in_array(auth()->user()->role->name, ['project_director', 'admin']))
                        <div class="w-full text-left">
                            <form action="{{ route('kpis.sign-pd', $kpi->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 w-full">
                                @csrf
                                
                                <div class="bg-amber-50 p-3 rounded-xl border border-amber-100 mb-2">
                                    <p class="text-[10px] text-amber-800 font-bold leading-tight">
                                        Klik "SahKan" untuk menggunakan TTD Default (`image.png`), atau upload TTD baru di bawah ini.
                                    </p>
                                </div>

                                <div>
                                    <label for="pd_notes" class="block text-xs font-bold text-slate-700 mb-2">Catatan / Saran untuk Anggota (Opsional)</label>
                                    <textarea name="pd_notes" id="pd_notes" rows="3" class="w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 p-3" placeholder="Tuliskan evaluasi, saran, atau catatan khusus di sini..."></textarea>
                                </div>

                                <div>
                                    <label for="pd_signature" class="block text-xs font-bold text-slate-700 mb-2">Upload TTD Baru (Opsional)</label>
                                    <input type="file" name="pd_signature" id="pd_signature" accept="image/png, image/jpeg, image/jpg" class="block w-full text-xs text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl p-2 transition cursor-pointer bg-white">
                                </div>
                                <button type="submit" class="w-full mt-2 bg-slate-800 hover:bg-black text-white font-black text-xs uppercase tracking-widest py-3 rounded-xl transition">
                                    SahKan KPI
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="w-48 h-32 border-b-2 border-slate-300 mb-2 flex items-center justify-center bg-slate-50 text-slate-400 text-xs italic">
                            Menunggu Tanda Tangan PD
                        </div>
                        <p class="font-bold text-slate-700 uppercase">{{ $pd->name ?? 'Project Director' }}</p>
                    @endif
                @endif
            </div>

            <!-- HEAD OF DEPT SIGNATURE (RIGHT) -->
            <div class="premium-card p-6 flex flex-col items-center justify-start text-center h-full">
                <h4 class="text-xs font-black uppercase text-indigo-900 tracking-widest mb-4">Kepala Departemen</h4>
                @if($kpi->head_signature)
                    <div class="w-48 h-32 border-b-2 border-slate-800 mb-2 flex items-center justify-center">
                        <img src="{{ asset('storage/' . $kpi->head_signature) }}" alt="Tanda Tangan Kepala Departemen" class="max-w-full max-h-full object-contain">
                    </div>
                    <p class="font-bold text-slate-700 uppercase mb-4">{{ $kpi->assessor->name }}</p>
                @else
                    <div class="w-48 h-32 border-b-2 border-slate-300 mb-2 flex items-center justify-center bg-slate-50 text-slate-400 text-xs italic">
                        Belum ada tanda tangan
                    </div>
                    <p class="font-bold text-slate-700 uppercase mb-4">{{ $kpi->assessor->name }}</p>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mb-20">
            @if(auth()->user()->isKepalaDivisi() || auth()->user()->canViewAllKPI())
                <a href="{{ route('kpis.download', $kpi->id) }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-emerald-600 text-white font-black uppercase tracking-widest text-[10px] italic hover:bg-emerald-700 transition shadow-lg shadow-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    DOWNLOAD PDF
                </a>
            @endif
            @php
                $backRoute = 'kpis.index';
                if(auth()->user()->canViewAllKPI()) {
                    $backRoute = 'kpis.keseluruhan';
                } elseif(auth()->user()->isKepalaDivisi()) {
                    $backRoute = 'kpis.anggota';
                }
            @endphp
            <a href="{{ route($backRoute) }}" class="btn-primary" style="padding-left: 2rem; padding-right: 2rem;">
                KEMBALI KE DAFTAR
            </a>
        </div>
    </div>
</x-app-layout>
