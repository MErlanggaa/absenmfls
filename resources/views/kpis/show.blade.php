<x-app-layout>
    <x-slot name="header">
        {{ __('HASIL PENILAIAN KPI') }}
    </x-slot>

    <div class="space-y-6 max-w-5xl mx-auto">
        <div class="premium-card mb-6 border-t-4 border-indigo-600">
            <div class="flex flex-col md:flex-row items-center gap-6 mb-8 border-b border-slate-100 pb-6">
                <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100 border border-slate-100 p-2">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-sm font-bold text-slate-700">
                <div class="flex">
                    <div class="w-40">NAMA ANGGOTA</div>
                    <div class="mr-2">:</div>
                    <div class="uppercase text-indigo-700">{{ $kpi->user->name }}</div>
                </div>
                <div class="flex">
                    <div class="w-40">DEPARTEMEN</div>
                    <div class="mr-2">:</div>
                    <div class="uppercase text-indigo-700">{{ $kpi->user->department->name ?? '-' }}</div>
                </div>
                <div class="flex">
                    <div class="w-40">PERIODE PENILAIAN</div>
                    <div class="mr-2">:</div>
                    <div class="uppercase text-indigo-700">{{ \Carbon\Carbon::parse($kpi->period_date)->translatedFormat('F Y') }}</div>
                </div>
                <div class="flex">
                    <div class="w-40">NAMA PENILAI</div>
                    <div class="mr-2">:</div>
                    <div class="uppercase text-indigo-700">{{ $kpi->assessor->name }}</div>
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
        <div class="premium-card mb-6 p-0 overflow-hidden">
            <div class="bg-indigo-600 text-white font-black px-6 py-4 uppercase tracking-widest text-sm shadow-md flex justify-between items-center">
                <span>{{ $cat['title'] }}</span>
                <span class="bg-white text-indigo-600 px-3 py-1 rounded-lg text-xs font-black">
                    @php
                        $scores = $kpi->behavior_scores[$cat['name']] ?? [];
                        $totalCatScore = array_sum($scores);
                        echo "TOTAL NILAI: " . $totalCatScore;
                    @endphp
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse min-w-[700px]">
                    <thead>
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
                    <tbody>
                        @php $qIndex = 0; @endphp
                        @foreach($cat['aspects'] as $aspectIndex => $aspect)
                            @php $rowspan = count($aspect[1]); @endphp
                            @foreach($aspect[1] as $bIndex => $behavior)
                            @php
                                $selectedVal = $scores[$qIndex] ?? 0;
                            @endphp
                            <tr class="border-b border-slate-100 transition hover:bg-slate-50">
                                @if($bIndex == 0)
                                    <td rowspan="{{ $rowspan }}" class="p-3 border-r border-slate-200 text-center font-bold text-slate-400 bg-white">
                                        {{ $aspectIndex + 1 }}
                                    </td>
                                    <td rowspan="{{ $rowspan }}" class="p-3 border-r border-slate-200 font-medium text-slate-700 bg-white">
                                        {{ $aspect[0] }}
                                    </td>
                                @endif
                                <td class="p-3 border-r border-slate-200 text-slate-600">
                                    {{ $behavior }}
                                </td>
                                <td class="p-0 border-r border-slate-200">
                                    <div class="flex h-full min-h-[48px]">
                                        @for($lvl=1; $lvl<=4; $lvl++)
                                            <div class="w-1/4 border-r last:border-r-0 border-slate-200 flex items-center justify-center p-2 
                                                {{ $selectedVal == $lvl ? 'bg-indigo-100 ring-2 ring-indigo-500 inset-0 z-10' : 'bg-slate-50/50' }}">
                                                @if($selectedVal == $lvl)
                                                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center font-black text-xs">
                                                        {{ $lvl }}
                                                    </span>
                                                @else
                                                    <span class="w-4 h-4 rounded-full border-2 border-slate-300"></span>
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

        <div class="flex items-center justify-end gap-4 mb-20">
            <a href="{{ route('kpis.index') }}" class="btn-primary" style="padding-left: 2rem; padding-right: 2rem;">
                KEMBALI KE DAFTAR
            </a>
        </div>
    </div>
</x-app-layout>
