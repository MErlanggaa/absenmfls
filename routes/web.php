<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Approval Requests
    Route::resource('approval-requests', \App\Http\Controllers\ApprovalRequestController::class);
    Route::post('/approval-requests/{id}/approve', [\App\Http\Controllers\ApprovalRequestController::class, 'approve'])->name('approval-requests.approve');
    Route::post('/approval-requests/{id}/reject', [\App\Http\Controllers\ApprovalRequestController::class, 'reject'])->name('approval-requests.reject');
    Route::get('/approval-requests/download/{filename}', [\App\Http\Controllers\ApprovalRequestController::class, 'download'])->name('approval-requests.download');

    // Events
    Route::resource('events', \App\Http\Controllers\EventController::class);
    Route::get('/events/{id}/qrcode', [\App\Http\Controllers\EventController::class, 'generateQr'])->name('events.qrcode');

    // Attendance
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
    Route::post('/attendance/check-in', [\App\Http\Controllers\AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [\App\Http\Controllers\AttendanceController::class, 'checkOut'])->name('attendance.check-out');

    // Notifications
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        auth()->user()->unreadNotifications->markAsRead();
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    // User Management (Admin only)
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::patch('/users/{id}/toggle-active', [\App\Http\Controllers\UserController::class, 'toggleActive'])->name('users.toggle-active');

    // Attendance Manual
    Route::post('/attendances/manual', [\App\Http\Controllers\AttendanceController::class, 'manualStore'])->name('attendances.manual-store');

    // FCM Tokens
    Route::post('/fcm-token', [App\Http\Controllers\Api\FcmTokenController::class, 'store'])->name('fcm.store');
    
    // Debug FCM Route (Temporary)
    Route::get('/test-fcm', function (Illuminate\Http\Request $request) {
        $token = $request->query('token');
        if (!$token) return 'Please provide ?token=YOUR_FCM_TOKEN';
        
        $firebase = new App\Services\FirebaseService();
        $res = $firebase->sendToDevice(
            $token, 
            'Absen Woi!', 
            'Ada Rapat Hari ini jangan lupa!.',
            ['url' => '/']
        );
        
        return $res ? 'BERHASIL DIKIRIM! Cek HP.' : 'GAGAL MENGIRIM! Cek Log Laravel (storage/logs/laravel.log)';
    });
    Route::delete('/fcm-token', [App\Http\Controllers\Api\FcmTokenController::class, 'destroy'])->name('fcm.destroy');
});

require __DIR__.'/auth.php';
