<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KpiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Normal Anggota sees their own past KPIs BUT only if signed by PD
        $kpis = Kpi::where('user_id', $user->id)
            ->whereNotNull('pd_signature')
            ->whereYear('period_date', $year)
            ->whereMonth('period_date', $month)
            ->latest()
            ->paginate(20);

        return view('kpis.index_anggota', compact('kpis', 'month', 'year'));
    }

    public function indexHead(Request $request)
    {
        $user = auth()->user();

        if (!$user->isKepalaDivisi()) {
            abort(403, 'Akses ditolak.');
        }

        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Kepala Departemen sees their own department members, but NOT themselves and NOT Admin/SuperAdmin
        $members = User::with('role')
            ->where('department_id', $user->department_id)
            ->where('id', '!=', $user->id)
            ->whereHas('role', function ($q) {
            $q->whereNotIn('name', ['admin', 'superadmin', 'kepala_divisi']);
        })
            ->get();

        // Getting the KPI for these members based on selected month/year
        $kpisThisMonth = Kpi::whereIn('user_id', $members->pluck('id'))
            ->whereYear('period_date', $year)
            ->whereMonth('period_date', $month)
            ->get()
            ->keyBy('user_id');

        return view('kpis.index_head', compact('members', 'kpisThisMonth', 'month', 'year'));
    }

    public function indexAdmin(Request $request)
    {
        $user = auth()->user();

        if (!$user->canViewAllKPI()) {
            abort(403, 'Akses ditolak.');
        }

        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Admin/Project Director/VPD/Administrasi/Regional sees all KPIs for selected month
        $kpis = Kpi::with(['user.department', 'assessor'])
            ->whereYear('period_date', $year)
            ->whereMonth('period_date', $month)
            ->latest()
            ->paginate(20)
            ->appends($request->all());

        // Fetch members of Administrasi department who haven't been assessed for the selected month, excluding AdminIT
        $administrasiMembers = User::whereHas('department', function ($q) {
            $q->where('name', 'Departemen Administrasi Data Evaluation & Reporting');
        })->whereDoesntHave('kpis', function ($q) use ($year, $month) {
            $q->whereYear('period_date', $year)
                ->whereMonth('period_date', $month);
        })->get()->reject(function ($m) {
            return $m->isAdminIT() || $m->isSuperAdmin();
        });

        return view('kpis.index_admin', compact('kpis', 'administrasiMembers', 'month', 'year'));
    }

    public function create(Request $request, User $user)
    {
        $auth = auth()->user();

        if (!$auth->isKepalaDivisi() || $auth->department_id !== $user->department_id) {
            abort(403, 'Anda hanya dapat menilai anggota dari departemen Anda sendiri.');
        }

        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Check if already assessed for the selected month
        $existing = Kpi::where('user_id', $user->id)
            ->whereYear('period_date', $year)
            ->whereMonth('period_date', $month)
            ->first();

        if ($existing) {
            return redirect()->route('kpis.show', $existing->id)
                ->with('error', 'KPI untuk anggota ini di periode bulan tersebut sudah pernah diisi.');
        }

        return view('kpis.create', compact('user', 'month', 'year'));
    }

    public function store(Request $request, User $user)
    {
        $auth = auth()->user();

        if (!$auth->isKepalaDivisi() || $auth->department_id !== $user->department_id) {
            abort(403, 'Anda hanya dapat menilai anggota dari departemen Anda sendiri.');
        }

        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
            'head_signature' => 'required|image|max:2048',
            'category_A' => 'required|array|size:7',
            'category_A.*' => 'required|integer|min:1|max:4',
            'category_B' => 'required|array|size:8',
            'category_B.*' => 'required|integer|min:1|max:4',
            'category_C' => 'required|array|size:9',
            'category_C.*' => 'required|integer|min:1|max:4',
            'category_D' => 'required|array|size:8',
            'category_D.*' => 'required|integer|min:1|max:4',
            'category_E' => 'required|array|size:8',
            'category_E.*' => 'required|integer|min:1|max:4',
        ]);

        $config = [
            'category_A' => ['weight' => 25, 'max_score' => 7 * 4],
            'category_B' => ['weight' => 25, 'max_score' => 8 * 4],
            'category_C' => ['weight' => 20, 'max_score' => 9 * 4],
            'category_D' => ['weight' => 15, 'max_score' => 8 * 4],
            'category_E' => ['weight' => 15, 'max_score' => 8 * 4],
        ];

        $totalPenilaian = 0;
        $behaviorScores = [];

        foreach ($config as $cat => $rules) {
            $scores = $request->input($cat);
            $behaviorScores[$cat] = $scores;

            $totalNilai = array_sum($scores);

            // "total nilai itu diitung total capaian kinerja, lalu untuk total penialain itu diitung berdasarkan di per persen persen tadi tuh"
            // Formula: (Total Nilai / Max Score) * Weight
            $catFinal = ($totalNilai / $rules['max_score']) * $rules['weight'];
            $totalPenilaian += $catFinal;
        }

        $indexScore = '';
        if ($totalPenilaian >= 75) {
            $indexScore = 'Mencapai Target';
        }
        elseif ($totalPenilaian >= 65) {
            $indexScore = 'Perlu Evaluasi';
        }
        else {
            $indexScore = 'Tidak Mencapai Target';
        }

        $headSignaturePath = null;
        if ($request->hasFile('head_signature')) {
            $headSignaturePath = $request->file('head_signature')->store('kpi_signatures', 'public');
        }

        $periodDate = Carbon::create($request->year, $request->month, 1)->startOfMonth()->toDateString();

        $kpi = Kpi::create([
            'user_id' => $user->id,
            'assessor_id' => $auth->id,
            'period_date' => $periodDate,
            'behavior_scores' => $behaviorScores,
            'total_value' => number_format($totalPenilaian, 2),
            'index_score' => $indexScore,
            'head_signature' => $headSignaturePath,
        ]);

        // Notify Project Director & Admin
        $pdAndAdmins = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['project_director', 'admin']);
        })->get();

        foreach ($pdAndAdmins as $notifiable) {
            $notifiable->notify(new \App\Notifications\KpiNotification($kpi, 'created'));
        }

        return redirect()->route('kpis.index')->with('success', 'KPI berhasil dinilai dan dikirim ke Project Director.');
    }

    public function show(Kpi $kpi)
    {
        $auth = auth()->user();

        if ($auth->canViewAllKPI()) {
        // allowed
        }
        elseif ($auth->isKepalaDivisi() && $kpi->user->department_id === $auth->department_id) {
        // allowed
        }
        elseif ($auth->isRegionalAndOutreach() && $auth->isKepalaDivisi()) {
        // allowed
        }
        elseif ($auth->isAdministrasi() && $auth->isKepalaDivisi()) {
        // allowed
        }
        elseif ($kpi->user_id === $auth->id && $kpi->pd_signature !== null) {
        // user themselves, ONLY if signed by PD
        }
        else {
            abort(403, 'Anda tidak memiliki akses untuk melihat KPI ini (mungkin belum disahkan oleh Project Director).');
        }

        $pd = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'project_director');
        })->first();

        return view('kpis.show', compact('kpi', 'pd'));
    }


    public function signPd(Request $request, Kpi $kpi)
    {
        $auth = auth()->user();

        if (!in_array($auth->role->name, ['project_director', 'admin'])) {
            abort(403, 'Hanya Project Director yang dapat menandatangani ini.');
        }

        $request->validate([
            'pd_signature' => 'nullable|image|max:2048',
            'pd_notes' => 'nullable|string',
        ]);

        $updateData = [
            'pd_notes' => $request->input('pd_notes'),
        ];

        if ($request->hasFile('pd_signature')) {
            $path = $request->file('pd_signature')->store('kpi_signatures', 'public');
            $updateData['pd_signature'] = $path;
        }
        else {
            // Use default image.png
            $updateData['pd_signature'] = 'default_pd_signature';
        }

        $kpi->update($updateData);

        // Notify the Member
        $kpi->user->notify(new \App\Notifications\KpiNotification($kpi, 'approved'));

        return redirect()->back()->with('success', 'KPI berhasil disahkan oleh Project Director.');
    }

    public function downloadPdf(Kpi $kpi)
    {
        $auth = auth()->user();

        // ONLY Kepala Departemen and Project Director/Admin can download
        $isPdOrAdmin = in_array($auth->role->name, ['project_director', 'admin']);
        $isHeadOfThisDept = $auth->isKepalaDivisi() && $auth->department_id === $kpi->user->department_id;

        if (!$isPdOrAdmin && !$isHeadOfThisDept) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan KPI ini.');
        }

        $pd = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'project_director');
        })->first();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kpis.pdf', compact('kpi', 'pd'))
            ->setPaper('a4', 'portrait')
            ->setOptions($this->pdfOptions());



        $filename = 'KPI_' . str_replace(' ', '_', $kpi->user->name) . '_' . \Carbon\Carbon::parse($kpi->period_date)->format('M_Y') . '.pdf';
        return $pdf->download($filename);
    }

    public function downloadZip()
    {
        $auth = auth()->user();

        // ONLY Vice Project Director and Admin can download bulk
        $isVpdOrAdmin = in_array($auth->role->name, ['vice_project_director', 'project_director', 'admin']);

        if (!$isVpdOrAdmin) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh seluruh data KPI.');
        }

        $kpis = Kpi::with(['user.department', 'assessor'])->latest()->get();

        if ($kpis->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data KPI untuk diunduh.');
        }

        $pd = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'project_director');
        })->first();

        $zip = new \ZipArchive();
        $zipFileName = 'Bulk_KPI_' . now()->format('Y-m-d_His') . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($kpis as $kpi) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kpis.pdf', compact('kpi', 'pd'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions($this->pdfOptions());
                $pdfFileName = 'KPI_' . str_replace([' ', '/', '\\'], '_', $kpi->user->name) . '_' . \Carbon\Carbon::parse($kpi->period_date)->format('M_Y') . '_' . $kpi->id . '.pdf';

                $zip->addFromString($pdfFileName, $pdf->output());
            }
            $zip->close();
        }
        else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
