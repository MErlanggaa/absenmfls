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

        if ($user->canViewAllKPI()) {
            // Admin/Project Director/VPD sees all KPIs
            $kpis = Kpi::with(['user.department', 'assessor'])->latest()->paginate(20);

            // Fetch members of Administrasi department who haven't been assessed this month
            // so VPD/Admin can assess them as per requirement
            $currentMonth = Carbon::now()->startOfMonth();
            $administrasiMembers = User::whereHas('department', function ($q) {
                $q->where('name', 'Administrasi');
            })->whereDoesntHave('kpis', function ($q) use ($currentMonth) {
                $q->whereYear('period_date', $currentMonth->year)
                    ->whereMonth('period_date', $currentMonth->month);
            })->get();

            return view('kpis.index_admin', compact('kpis', 'administrasiMembers'));
        }
        elseif ($user->isKepalaDivisi() || $user->isAdministrasi()) {
            // Kepala Departemen / Administrasi sees their own department members
            $members = User::where('department_id', $user->department_id)
                ->where('id', '!=', $user->id)
                ->get();

            // Getting the current month's KPI for these members
            $currentMonth = Carbon::now()->startOfMonth();
            $kpisThisMonth = Kpi::whereIn('user_id', $members->pluck('id'))
                ->where('assessor_id', $user->id) // For Administrasi, assessor might be Kepala Departemen but they can still see it
                ->whereYear('period_date', $currentMonth->year)
                ->whereMonth('period_date', $currentMonth->month)
                ->get()
                ->keyBy('user_id');

            // If user is Administrasi but NOT Kepala Divisi, show them their own members but without giving them "assessor" powers
            // We just fetch all KPIs for this department this month
            if ($user->isAdministrasi() && !$user->isKepalaDivisi()) {
                $kpisThisMonth = Kpi::whereIn('user_id', $members->pluck('id'))
                    ->whereYear('period_date', $currentMonth->year)
                    ->whereMonth('period_date', $currentMonth->month)
                    ->get()
                    ->keyBy('user_id');
            }

            return view('kpis.index_head', compact('members', 'kpisThisMonth'));
        }
        else {
            // Normal Anggota sees their own past KPIs BUT only if signed by VPD
            $kpis = Kpi::where('user_id', $user->id)
                ->whereNotNull('vpd_signature')
                ->latest()
                ->paginate(20);

            return view('kpis.index_anggota', compact('kpis'));
        }
    }

    public function create(User $user)
    {
        $auth = auth()->user();

        if (!$auth->isKepalaDivisi() || $auth->department_id !== $user->department_id) {
            abort(403, 'Anda hanya dapat menilai anggota dari departemen Anda sendiri.');
        }

        // Check if already assessed for this month
        $currentMonth = Carbon::now()->startOfMonth();
        $existing = Kpi::where('user_id', $user->id)
            ->whereYear('period_date', $currentMonth->year)
            ->whereMonth('period_date', $currentMonth->month)
            ->first();

        if ($existing) {
            return redirect()->route('kpis.show', $existing->id)
                ->with('error', 'KPI untuk anggota ini di bulan ini sudah pernah diisi.');
        }

        return view('kpis.create', compact('user'));
    }

    public function store(Request $request, User $user)
    {
        $auth = auth()->user();

        if (!$auth->isKepalaDivisi() || $auth->department_id !== $user->department_id) {
            abort(403, 'Anda hanya dapat menilai anggota dari departemen Anda sendiri.');
        }

        $request->validate([
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

        $kpi = Kpi::create([
            'user_id' => $user->id,
            'assessor_id' => $auth->id,
            'period_date' => Carbon::now()->startOfMonth()->toDateString(),
            'behavior_scores' => $behaviorScores,
            'total_value' => number_format($totalPenilaian, 2),
            'index_score' => $indexScore,
            'head_signature' => $headSignaturePath,
        ]);

        return redirect()->route('kpis.index')->with('success', 'KPI berhasil dinilai.');
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
        elseif ($auth->isAdministrasi() && $kpi->user->department_id === $auth->department_id) {
        // allowed
        }
        elseif ($kpi->user_id === $auth->id && $kpi->vpd_signature !== null) {
        // user themselves, ONLY if signed by VPD
        }
        else {
            abort(403, 'Anda tidak memiliki akses untuk melihat KPI ini (mungkin belum disahkan oleh VPD).');
        }

        $vpd = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'vice_project_director');
        })->first();

        return view('kpis.show', compact('kpi', 'vpd'));
    }

    public function signVpd(Request $request, Kpi $kpi)
    {
        $auth = auth()->user();

        if (!in_array($auth->role->name, ['vice_project_director', 'project_director', 'admin'])) {
            abort(403, 'Hanya Vice Project Director yang dapat menandatangani ini.');
        }

        $request->validate([
            'vpd_signature' => 'required|image|max:2048',
            'vpd_notes' => 'nullable|string',
        ]);

        if ($request->hasFile('vpd_signature')) {
            $path = $request->file('vpd_signature')->store('kpi_signatures', 'public');
            $kpi->update([
                'vpd_signature' => $path,
                'vpd_notes' => $request->input('vpd_notes'),
            ]);
        }

        return redirect()->back()->with('success', 'Tanda tangan dan catatan VPD berhasil disimpan.');
    }

    public function downloadPdf(Kpi $kpi)
    {
        $auth = auth()->user();

        // ONLY Kepala Departemen and Vice Project Director can download
        $isVpdOrAdmin = in_array($auth->role->name, ['vice_project_director', 'project_director', 'admin']);
        $isHeadOfThisDept = $auth->isKepalaDivisi() && $auth->department_id === $kpi->user->department_id;

        if (!$isVpdOrAdmin && !$isHeadOfThisDept) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan KPI ini.');
        }

        $vpd = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'vice_project_director');
        })->first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kpis.pdf', compact('kpi', 'vpd'))
            ->setPaper('a4', 'portrait');

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

        $vpd = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'vice_project_director');
        })->first();

        $zip = new \ZipArchive();
        $zipFileName = 'Bulk_KPI_' . now()->format('Y-m-d_His') . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($kpis as $kpi) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kpis.pdf', compact('kpi', 'vpd'))
                    ->setPaper('a4', 'portrait');

                $pdfFileName = 'KPI_' . str_replace([' ', '/', '\\'], '_', $kpi->user->name) . '_' . Carbon::parse($kpi->period_date)->format('M_Y') . '_' . $kpi->id . '.pdf';

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
