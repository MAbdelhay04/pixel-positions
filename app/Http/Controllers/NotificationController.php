<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function dropdown(Request $request): View
    {
        if (! isAjax()) {
            abort(404);
        }

        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->limit(10)
            ->get();

        return view('notifications._dropdown', compact('notifications'));
    }

    public function markAsRead(Request $request, string $notification): JsonResponse
    {
        $record = $this->findNotification($request, $notification);

        if (is_null($record->read_at)) {
            $record->markAsRead();
        }

        return response()->json([
            'unread_count' => $request->user()->unreadNotifications()->count(),
            'redirect_url' => notification_redirect_url($record),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'unread_count' => 0,
        ]);
    }

    private function findNotification(Request $request, string $notification): DatabaseNotification
    {
        return $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();
    }
}
