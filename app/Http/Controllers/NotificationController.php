<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll()
    {
        Notification::truncate();

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
