<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $user->load(['role', 'department']);

        // Data Containers
        $pendingApprovals = collect();
        $myRequests = collect();
        $upcomingEvents = Event::where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();
        
        $attendanceStats = [
            'total_present' => 0, // Placeholder
            'percentage' => 0,    // Placeholder
        ];

        // Specific Data based on Role
        if ($user->role->name === 'admin') {
            $pendingApprovals = ApprovalRequest::whereIn('status', ['pending_review', 'pending_approval'])->latest()->get();
            $attendanceStats['total_present'] = Attendance::count(); 
        } elseif ($user->role->name === 'vice_project_director') {
            // Level 1 Approver
            $pendingApprovals = ApprovalRequest::where('status', 'pending_review')->latest()->get();
        } elseif ($user->role->name === 'project_director') {
            // Level 2 Approver
            $pendingApprovals = ApprovalRequest::where('status', 'pending_approval')->latest()->get();
        } else {
            // Regular User / Ka. Dept - See own requests
            $myRequests = ApprovalRequest::where('created_by', $user->id)->latest()->get();
        }

        // Attendance Stats for User
        $userAttendanceCount = Attendance::where('user_id', $user->id)->count();
        $totalEvents = Event::where('event_date', '<', now())->count();
        $attendanceStats['percentage'] = $totalEvents > 0 ? ($userAttendanceCount / $totalEvents) * 100 : 0;

        return view('dashboard', compact('user', 'pendingApprovals', 'myRequests', 'upcomingEvents', 'attendanceStats'));
    }
}
