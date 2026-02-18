<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    /**
     * Save or update FCM device token for the authenticated user.
     * Called from the frontend after Firebase SDK registers the device.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token'       => 'required|string',
            'device_type' => 'required|in:android,ios,web',
        ]);

        // Upsert: update if token exists, create if not
        DeviceToken::updateOrCreate(
            [
                'token'   => $request->token,
            ],
            [
                'user_id'     => auth()->id(),
                'device_type' => $request->device_type,
                'last_used_at'=> now(),
            ]
        );

        return response()->json(['message' => 'FCM token saved successfully.']);
    }

    /**
     * Remove FCM token (e.g., on logout).
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        DeviceToken::where('token', $request->token)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json(['message' => 'FCM token removed.']);
    }
}
