<?php

namespace App\Http\Controllers;

use App\Models\ApprovalLevel;
use App\Models\ApprovalLog;
use App\Models\ApprovalRequest;
use App\Models\User;
use App\Notifications\ApprovalStatusUpdated;
use App\Notifications\NewApprovalRequest;
use Illuminate\Http\Request;

class ApprovalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdminIT() || $user->isAdministrasi() || $user->role->name === 'project_director' || $user->role->name === 'vice_project_director') {
            $requests = \App\Models\ApprovalRequest::with(['user', 'department'])->latest()->get();
        } else {
            $requests = \App\Models\ApprovalRequest::with(['user', 'department'])->where('created_by', $user->id)->latest()->get();
        }

        return view('approval_requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = \App\Models\Department::all();
        return view('approval_requests.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'type' => 'required|string|max:100',
            'description' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120', // 5MB
        ]);

        $path = null;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents');
        }

        $approvalRequest = ApprovalRequest::create([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'department_id' => auth()->user()->department_id,
            'document_path' => $path,
            'created_by' => auth()->id(),
            'current_level' => 1,
            'status' => 'pending_review',
        ]);

        // Notify Vice Project Directors & Superadmin (Level 1 approvers)
        $approvers = User::where(function($q) {
                $q->whereHas('role', fn($rq) => $rq->where('name', 'vice_project_director'))
                  ->orWhere('email', 'admin@mfls.com');
            })
            ->where('is_active', true)->get();
            
        foreach ($approvers as $approver) {
            $approver->notify(new NewApprovalRequest($approvalRequest));
        }

        return redirect()->route('approval-requests.index')->with('success', 'Pengajuan berhasil dibuat dan notifikasi telah dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $approvalRequest = \App\Models\ApprovalRequest::with(['user', 'department', 'logs.approver.role'])->findOrFail($id);
        return view('approval_requests.show', compact('approvalRequest'));
    }

    public function approve(Request $request, string $id)
    {
        $approvalRequest = \App\Models\ApprovalRequest::findOrFail($id);
        $user = auth()->user();

        // Validate permission (Simplified logic for role based stats)
        // Level 1: VPD (Approves pending_review -> pending_approval)
        // Level 2: PD (Approves pending_approval -> approved)

        $level = 0;
        $nextStatus = '';

        // Determine Stage
        if ($approvalRequest->status === 'pending_review' && ($user->role->name === 'vice_project_director' || $user->isSuperAdmin())) {
            $level = 1;
            $nextStatus = 'pending_approval';
            $approvalRequest->current_level = 2;
        } elseif ($approvalRequest->status === 'pending_approval' && ($user->role->name === 'project_director' || $user->isSuperAdmin())) {
            $level = 2;
            $nextStatus = 'approved';
            $approvalRequest->current_level = 3; 
        } else {
             return back()->with('error', 'Stage approval tidak valid atau Bos nggak punya akses buat Approve di tahap ini.');
        }

        $approvalRequest->status = $nextStatus;
        $approvalRequest->save();

        // Log
        ApprovalLog::create([
            'approval_request_id' => $approvalRequest->id,
            'approved_by' => $user->id,
            'level_order' => $level,
            'status' => 'approved',
            'note' => $request->note,
            'approved_at' => now(),
        ]);

        // Notify requester
        $requester = User::find($approvalRequest->created_by);
        if ($requester) {
            $requester->notify(new ApprovalStatusUpdated($approvalRequest, $nextStatus));
        }

        // Notify PD if moving to Level 2
        if ($nextStatus === 'pending_approval') {
            $pdApprovers = User::whereHas('role', fn($q) => $q->where('name', 'project_director'))
                ->orWhere('email', 'admin@mfls.com')
                ->where('is_active', true)->get();
            foreach ($pdApprovers as $pd) {
                $pd->notify(new NewApprovalRequest($approvalRequest));
            }
        }

        return back()->with('success', 'Pengajuan disetujui.');
    }

    public function reject(Request $request, string $id)
    {
        $approvalRequest = \App\Models\ApprovalRequest::findOrFail($id);
        $user = auth()->user();

        // Allow rejection at any stage by approvers or superadmin
        if (!in_array($user->role->name, ['vice_project_director', 'project_director']) && !$user->isSuperAdmin()) {
             return back()->with('error', 'Unauthorized, Bos!');
        }

        $approvalRequest->status = 'rejected';
        $approvalRequest->rejected_reason = $request->note;
        $approvalRequest->save();

        // Log
        ApprovalLog::create([
            'approval_request_id' => $approvalRequest->id,
            'approved_by' => $user->id,
            'level_order' => $approvalRequest->current_level,
            'status' => 'rejected',
            'note' => $request->note,
            'approved_at' => now(),
        ]);

        // Notify requester
        $requester = User::find($approvalRequest->created_by);
        if ($requester) {
            $requester->notify(new ApprovalStatusUpdated($approvalRequest, 'rejected'));
        }

        return back()->with('success', 'Pengajuan ditolak.');
    }

    public function download($filename)
    {
        return response()->download(storage_path('app/documents/' . $filename));
    }
}
