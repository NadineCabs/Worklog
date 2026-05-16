<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\UserNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $layout = $user->role === 'employee' ? 'layouts.employee' : 'layouts.app';
        if (!Schema::hasTable('user_notifications')) {
            $notifications = collect();
            return view('notifications.index', compact('notifications', 'layout'));
        }

        $notifications = UserNotification::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications', 'layout'));
    }

    public function poll()
    {
        $user = auth()->user();
        if (!Schema::hasTable('user_notifications')) {
            return response()->json([
                'unread_count' => 0,
                'notifications' => [],
            ]);
        }

        $notifications = UserNotification::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'unread_count' => UserNotification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count(),
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->message ?? 'Notification',
                    'url' => $notification->url ?? '#',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            }),
        ]);
    }

    public function markRead(string $notificationId)
    {
        $user = auth()->user();
        if (!Schema::hasTable('user_notifications')) {
            return response()->json(['ok' => true]);
        }
        $notification = UserNotification::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->first();

        if ($notification && $notification->read_at === null) {
            $notification->markAsRead();
        }

        return response()->json(['ok' => true]);
    }

    public function markAllRead()
    {
        $user = auth()->user();
        if (!Schema::hasTable('user_notifications')) {
            return response()->json(['ok' => true]);
        }
        UserNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
