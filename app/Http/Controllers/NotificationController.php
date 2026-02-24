<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Tandai semua notifikasi dibaca
    public function readAll()
    {
        Notification::where('is_read', 0)->update([
            'is_read' => 1
        ]);

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    // Tandai satu notifikasi dibaca
    public function read($id)
    {
        $notif = Notification::find($id);

        if (!$notif) {
            return redirect()->back()->with('error', 'Notifikasi tidak ditemukan');
        }

        $notif->update([
            'is_read' => 1
        ]);

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }

    // Opsional: untuk menampilkan notifikasi di navbar
    public static function getNotifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->take(10)->get();
        $unreadCount = Notification::where('is_read', 0)->count();

        return [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ];
    }
}