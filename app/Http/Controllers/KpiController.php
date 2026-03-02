<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->canViewAllKPI()) {
            // Admin/Project Director sees all KPIs
            $kpis = Kpi::with(['user.department', 'assessor'])->latest()->paginate(20);
            return view('kpis.index_admin', compact('kpis'));
        }
        elseif ($user->isKepalaDivisi()) {
            // Kepala Departemen sees their own department members
            $members = User::where('department_id', $user->department_id)
                ->where('id', '!=', $user->id)
                ->get();

            // Getting the current month's KPI for these members
            $currentMonth = Carbon::now()->startOfMonth();
            $kpisThisMonth = Kpi::whereIn('user_id', $members->pluck('id'))
                ->where('assessor_id', $user->id)
                ->whereYear('period_date', $currentMonth->year)
                ->whereMonth('period_date', $currentMonth->month)
                ->get()
                ->keyBy('user_id');

            return view('kpis.index_head', compact('members', 'kpisThisMonth'));
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
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

        $kpi = Kpi::create([
            'user_id' => $user->id,
            'assessor_id' => $auth->id,
            'period_date' => Carbon::now()->startOfMonth()->toDateString(),
            'behavior_scores' => $behaviorScores,
            'total_value' => number_format($totalPenilaian, 2),
            'index_score' => $indexScore,
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
        elseif ($kpi->user_id === $auth->id) {
        // user themselves
        }
        else {
            abort(403, 'Anda tidak memiliki akses untuk melihat KPI ini.');
        }

        return view('kpis.show', compact('kpi'));
    }
}
