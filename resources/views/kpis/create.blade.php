<x-app-layout>
    <x-slot name="header">
        {{ __('ISI PENILAIAN KPI') }}
    </x-slot>

    <div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 overflow-x-hidden">
        <form action="{{ route('kpis.store', $user->id) }}" method="POST" id="kpiForm" enctype="multipart/form-data">
            @csrf
            
            <input type="hidden" name="month" value="{{ old('month', $month ?? \Carbon\Carbon::now()->month) }}">
            <input type="hidden" name="year" value="{{ old('year', $year ?? \Carbon\Carbon::now()->year) }}">

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
                    <h3 class="text-xl font-black italic text-slate-800 uppercase tracking-widest border-b-2 border-slate-900 inline-block pb-1">KEY PERFORMANCE INDICATOR (KPI)</h3>
                    <h4 class="text-lg text-slate-700 font-bold uppercase mt-2">MNCU FUTURE LEADER SCHOLARSHIP</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-xs lg:text-sm font-bold text-slate-700">
                    <div class="flex items-start">
                        <div class="w-32 shrink-0">NAMA</div>
                        <div class="mr-2">:</div>
                        <div class="uppercase text-indigo-700 break-words flex-1">{{ $user->name }}</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-32 shrink-0">DEPARTEMEN</div>
                        <div class="mr-2">:</div>
                        <div class="uppercase text-indigo-700 break-words flex-1">{{ $user->department->name ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-32 shrink-0">PERIODE NILAI</div>
                        <div class="mr-2">:</div>
                        <div class="uppercase text-indigo-700 flex-1">
                            {{ \Carbon\Carbon::create($year ?? \Carbon\Carbon::now()->year, $month ?? \Carbon\Carbon::now()->month, 1)->translatedFormat('F Y') }}
                        </div>
                    </div>
                </div>
            </div>

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-xs font-bold mb-6 border border-red-100 italic">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            <!-- START CATEGORIES -->
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
                <div class="bg-indigo-600 text-white font-black px-4 sm:px-6 py-4 uppercase tracking-widest text-sm shadow-md rounded-t-2xl">
                    {{ $cat['title'] }}
                </div>
                
                <div class="w-full rounded-b-2xl bg-white border-t border-slate-100">
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
                                <tr class="block md:table-row border-b border-slate-100 hover:bg-slate-50/30 transition p-5 md:p-0">
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
                                        <div class="md:hidden text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Pilih Level Kinerja</div>
                                        <div class="flex h-12 md:h-full md:min-h-[48px] w-full border border-slate-200 md:border-0 rounded-xl md:rounded-none overflow-hidden bg-slate-50 md:bg-transparent">
                                            @for($lvl=1; $lvl<=4; $lvl++)
                                                <label class="flex-1 md:w-1/4 border-r last:border-r-0 border-slate-200 flex items-center justify-center p-0 md:p-2 hover:bg-indigo-50 transition cursor-pointer relative group">
                                                    
                                                    <!-- Hide native radio on mobile, show on desktop -->
                                                    <input type="radio" required class="peer hidden md:block w-4 h-4 text-indigo-600 focus:ring-indigo-500 cursor-pointer absolute md:relative z-20 m-0" name="{{ $cat['name'] }}[{{ $qIndex }}]" value="{{ $lvl }}" {{ old($cat['name'] . '.' . $qIndex) == $lvl ? 'checked' : '' }}>
                                                    
                                                    <!-- Mobile overlay input -->
                                                    <input type="radio" required class="peer md:hidden opacity-0 absolute inset-0 z-20 w-full h-full cursor-pointer m-0" name="{{ $cat['name'] }}[{{ $qIndex }}]" value="{{ $lvl }}" {{ old($cat['name'] . '.' . $qIndex) == $lvl ? 'checked' : '' }}>

                                                    <!-- Mobile numbers -->
                                                    <span class="md:hidden z-10 text-xs font-black text-slate-400 peer-checked:text-indigo-700 relative">{{ $lvl }}</span>
                                                    
                                                    <!-- Mobile highlight border/bg -->
                                                    <div class="md:hidden absolute inset-0 z-0 bg-transparent peer-checked:bg-indigo-100 border-2 border-transparent peer-checked:border-indigo-500 rounded-none transition-colors"></div>
                                                </label>
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

            <!-- TATACARA & SUBMIT -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10 w-full">
                <div class="premium-card p-4 sm:p-6 border-l-4 border-indigo-400 w-full overflow-hidden">
                    <h4 class="text-xs font-black uppercase text-indigo-900 tracking-widest mb-4">Tata Cara Penilaian (Level)</h4>
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-center border-collapse border border-slate-200 text-xs min-w-[400px] bg-white">
                        <tr class="bg-slate-50 font-bold uppercase">
                            <td class="border border-slate-200 p-2">Level</td>
                            <td class="border border-slate-200 p-2 text-red-500">1</td>
                            <td class="border border-slate-200 p-2 text-orange-500">2</td>
                            <td class="border border-slate-200 p-2 text-blue-500">3</td>
                            <td class="border border-slate-200 p-2 text-green-500">4</td>
                        </tr>
                        <tr class="font-bold">
                            <td class="border border-slate-200 p-2 bg-slate-50 uppercase">Definisi</td>
                            <td class="border border-slate-200 p-2">Buruk</td>
                            <td class="border border-slate-200 p-2">Kurang</td>
                            <td class="border border-slate-200 p-2">Baik</td>
                            <td class="border border-slate-200 p-2">Istimewa</td>
                        </tr>
                        <tr class="text-[10px] text-slate-500">
                            <td class="border border-slate-200 p-2 bg-slate-50 font-bold uppercase">Remark</td>
                            <td class="border border-slate-200 p-2" colspan="2">Tidak Mencapai Target</td>
                            <td class="border border-slate-200 p-2" colspan="2">Sesuai Target atau Lebih</td>
                        </tr>
                    </table>
                </div>

                <div class="premium-card p-4 sm:p-6 border-l-4 border-emerald-400 w-full overflow-hidden">
                    <h4 class="text-xs font-black uppercase text-indigo-900 tracking-widest mb-4">Indeks Skor</h4>
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-center border-collapse border border-slate-200 text-xs min-w-[300px] bg-white">
                        <tr class="bg-slate-50 font-bold uppercase">
                            <td class="border border-slate-200 p-2">Indeks Skor</td>
                            <td class="border border-slate-200 p-2">Keterangan</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-200 p-2 font-black text-green-600">75 - 100</td>
                            <td class="border border-slate-200 p-2 font-bold uppercase text-slate-700">Mencapai Target</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-200 p-2 font-black text-yellow-500">65 - 74</td>
                            <td class="border border-slate-200 p-2 font-bold uppercase text-slate-700">Perlu Evaluasi</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-200 p-2 font-black text-red-500">< 65</td>
                            <td class="border border-slate-200 p-2 font-bold uppercase text-slate-700">Tidak Mencapai Target</td>
                        </tr>
                    </table>
                    </div>
                </div>
            </div>

            <!-- TANDA TANGAN -->
            <div class="premium-card mb-10 p-6 border-l-4 border-indigo-600">
                <h4 class="text-xs font-black uppercase text-indigo-900 tracking-widest mb-4">Tanda Tangan Kepala Departemen</h4>
                <p class="text-[10px] text-slate-500 mb-4">* Wajib menyertakan foto tanda tangan asli Anda (Kepala Departemen) untuk validasi pengisian KPI.</p>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <label for="head_signature" class="block text-sm font-medium text-slate-700 mb-2">Upload Gambar Tanda Tangan (PNG/JPG)</label>
                        <input type="file" name="head_signature" id="head_signature" accept="image/png, image/jpeg, image/jpg" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl p-2 transition cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mb-20">
                <a href="{{ route('kpis.index') }}" class="px-8 py-4 rounded-2xl bg-slate-100 text-slate-500 font-black uppercase tracking-widest text-[10px] italic hover:bg-slate-200 transition">BATAL</a>
                <button type="button" onclick="confirmSubmit()" class="btn-primary" style="padding-left: 2rem; padding-right: 2rem;">
                    SIMPAN PENILAIAN
                </button>
            </div>
        </form>
    </div>

    <script>
        function confirmSubmit() {
            // Check HTML5 validity
            if(!document.getElementById('kpiForm').checkValidity()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ISI DULU SEMUA BOS!',
                    text: 'Pastikan 40 point penilaian sudah dipilih semuanya (Level 1-4).',
                });
                return;
            }

            Swal.fire({
                title: 'Yakin mau simpan?',
                text: "Pastikan semua nilai sudah benar, nilai KPI tidak dapat diubah setelah disimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'IYA, SIMPAN!',
                cancelButtonText: 'CEK LAGI'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Tunggu Sebentar...',
                        text: 'Sedang menghitung Kalkulasi Total Nilai...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    document.getElementById('kpiForm').submit();
                }
            })
        }
    </script>
</x-app-layout>
