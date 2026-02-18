<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EventQrcode;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::where('user_id', auth()->id())->with('event')->latest()->get();
        return view('attendances.index', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'qr_code'   => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $qr = EventQrcode::where('code', $request->qr_code)
            ->where('is_active', true)
            ->where('expired_at', '>', now())
            ->first();

        if (!$qr) {
            return back()->with('error', 'QR Code tidak valid atau sudah kadaluarsa.');
        }

        $event    = $qr->event;
        $location = $event->location;

        // Check Department Invitation
        if (!empty($event->target_departments)) {
            if (!in_array(auth()->user()->department_id, $event->target_departments)) {
                return back()->with('error', 'Punten Bos, agenda rapat ini bukan untuk departemen lo!');
            }
        }

        if (!$location) {
            return back()->with('error', 'Lokasi event belum dikonfigurasi.');
        }

        // Calculate Distance
        $distance = $this->calculateDistance(
            $request->latitude, $request->longitude,
            $location->latitude, $location->longitude
        );

        if ($distance > $location->radius) {
            return back()->with('error', 'Anda berada di luar jangkauan lokasi event (' . round($distance) . 'm). Radius: ' . $location->radius . 'm.');
        }

        // Check if already checked in
        $existing = Attendance::where('event_id', $event->id)->where('user_id', auth()->id())->first();
        if ($existing) {
            return back()->with('message', 'Anda sudah melakukan absen untuk event ini.');
        }

        Attendance::create([
            'event_id'       => $event->id,
            'user_id'        => auth()->id(),
            'qr_id'          => $qr->id,
            'check_in'       => now(),
            'status'         => 'hadir',
            'user_latitude'  => $request->latitude,
            'user_longitude' => $request->longitude,
            'distance_meter' => $distance,
            'device_info'    => $request->userAgent(),
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Absensi berhasil! Selamat datang.');
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $attendance = Attendance::where('event_id', $request->event_id)
            ->where('user_id', auth()->id())
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Tidak ditemukan data check-in untuk event ini, atau sudah check-out.');
        }

        $attendance->update([
            'check_out' => now(),
        ]);

        return back()->with('success', 'Check-out berhasil. Terima kasih atas kehadiran Anda!');
    }

    public function manualStore(Request $request)
    {
        if (!auth()->user()->canViewAllAttendance()) {
            abort(403);
        }

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id'  => 'required|exists:users,id',
            'status'   => 'required|in:hadir,sakit,izin',
        ]);

        // Check if already checked in
        $existing = Attendance::where('event_id', $request->event_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existing) {
            $existing->update([
                'status' => $request->status,
                'check_in' => $existing->check_in ?? now(),
            ]);
            $msg = 'Status absensi berhasil diperbarui.';
        } else {
            Attendance::create([
                'event_id' => $request->event_id,
                'user_id'  => $request->user_id,
                'check_in' => now(),
                'status'   => $request->status,
                'distance_meter' => 0, // Manual check-in bypasses distance
                'device_info'    => 'Manual by ' . auth()->user()->name,
            ]);
            $msg = 'Absensi manual berhasil dicatat.';
        }

        return back()->with('success', $msg);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2))
               + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist  = acos(max(-1, min(1, $dist))); // clamp to avoid NaN
        $dist  = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344) * 1000; // Meters
    }
}
