<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read and redirect to appropriate page
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();

            // If there's a specific URL to redirect to based on notification type
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }

        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }

    public function fetch(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(10);

        // Format the data for easier use in Alpine.js
        $formattedNotifications = $notifications->map(function ($notification) {
            $sharedBy = null;
            if (isset($notification->data['shared_by_id'])) {
                $sharedBy = \App\Models\User::find($notification->data['shared_by_id']);
            }

            return [
                'id' => $notification->id,
                'read_at' => $notification->read_at,
                'created_at_formatted' => $notification->created_at->diffForHumans(),
                'data' => $notification->data,
                'shared_by_name' => $sharedBy ? $sharedBy->name : config('app.name')
            ];
        });

        return response()->json([
            'notifications' => $formattedNotifications,
            'current_page' => $notifications->currentPage(),
            'last_page' => $notifications->lastPage(),
            'total' => $notifications->total(),
        ]);
    }
}
