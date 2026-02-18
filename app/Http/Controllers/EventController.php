<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventLocation;
use App\Models\EventQrcode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private function isAdministrasi()
    {
        return auth()->user()->canManageEvents();
    }

    public function index()
    {
        $user = auth()->user();
        $query = Event::with(['creator', 'location'])->latest();

        if (!$user->canViewAllAttendance()) {
            // Filter events aimed at ALL departments OR user's specific department
            $query->where(function($q) use ($user) {
                $q->whereNull('target_departments')
                  ->orWhere('target_departments', 'like', '%"' . $user->department_id . '"%');
            })->where('is_active', true);
        }

        $events = $query->get();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        if (!$this->isAdministrasi()) {
            abort(403, 'Anda tidak memiliki akses untuk membuat agenda.');
        }
        $departments = \App\Models\Department::all();
        return view('events.create', compact('departments'));
    }

    public function store(Request $request)
    {
        if (!$this->isAdministrasi()) {
            abort(403, 'Anda tidak memiliki akses untuk membuat agenda.');
        }
        $request->validate([
            'name'               => 'required|string|max:200',
            'description'        => 'required|string',
            'event_date'         => 'required|date',
            'duration_hours'     => 'required|numeric|min:1',
            'location_name'      => 'required|string|max:255',
            'latitude'           => 'required|numeric',
            'longitude'          => 'required|numeric',
            'radius'             => 'required|numeric|min:10',
            'target_departments' => 'nullable|array',
            'target_departments.*' => 'exists:departments,id',
        ]);

        $event = Event::create([
            'name'               => $request->name,
            'description'        => $request->description,
            'event_date'         => $request->event_date,
            'end_date'           => Carbon::parse($request->event_date)->addHours((float)$request->duration_hours),
            'department_id'      => auth()->user()->department_id,
            'target_departments' => $request->target_departments,
            'created_by'         => auth()->id(),
            'reminder_enabled'   => true,
            'is_active'          => true,
        ]);

        EventLocation::create([
            'event_id'  => $event->id,
            'name'      => $request->location_name,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius,
        ]);

        // Notify targeted users
        $notifQuery = \App\Models\User::where('is_active', true);
        if ($request->filled('target_departments')) {
            $notifQuery->whereIn('department_id', $request->target_departments);
        }
        
        $targetUsers = $notifQuery->get();
        foreach ($targetUsers as $u) {
            $u->notify(new \App\Notifications\NewEventPublished($event));
        }

        return redirect()->route('events.index')->with('success', 'Agenda berhasil diterbitkan dan notifikasi telah dikirim ke seluruh anggota.');
    }

    public function show(string $id)
    {
        $event = Event::with(['creator', 'location', 'attendances.user'])->findOrFail($id);
        
        $users = null;
        if (auth()->user()->canViewAllAttendance()) {
            $userQuery = \App\Models\User::where('is_active', true);
            if (!empty($event->target_departments)) {
                $userQuery->whereIn('department_id', $event->target_departments);
            }
            $users = $userQuery->orderBy('name')->get();
        }

        return view('events.show', compact('event', 'users'));
    }

    public function edit(string $id)
    {
        $event = Event::with('location')->findOrFail($id);
        
        // Only creator or admin can edit
        if (auth()->user()->role->name !== 'admin' && auth()->id() !== $event->created_by) {
            abort(403);
        }

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);
        
        if (auth()->user()->role->name !== 'admin' && auth()->id() !== $event->created_by) {
            abort(403);
        }

        $request->validate([
            'name'           => 'required|string|max:200',
            'description'    => 'required|string',
            'event_date'     => 'required|date',
            'duration_hours' => 'required|numeric|min:1',
            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',
            'radius'         => 'required|numeric|min:10',
        ]);

        $event->update([
            'name'        => $request->name,
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'end_date'    => Carbon::parse($request->event_date)->addHours($request->duration_hours),
        ]);

        $event->location()->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius,
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Event berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        
        if (auth()->user()->role->name !== 'admin' && auth()->id() !== $event->created_by) {
            abort(403);
        }

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function generateQr($id)
    {
        $event = Event::findOrFail($id);
        
        if (!$this->isAdministrasi()) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        $code = 'MEETING-' . $event->id . '-' . strtoupper(uniqid());
        
        $qr = EventQrcode::create([
            'event_id'   => $event->id,
            'code'       => $code,
            'expired_at' => now()->addMinutes(60),
            'is_active'  => true,
        ]);

        return response()->json(['code' => $code, 'expired_at' => $qr->expired_at]);
    }


}
