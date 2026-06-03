<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function read(
        DatabaseNotification $notification
    ): RedirectResponse {

        $notification->markAsRead();

        return redirect()->route(
            'admin.complaints.show',
            $notification->data['complaint_id']
        );
    }

    public function readAll(): RedirectResponse
    {
        auth()
            ->user()
            ->unreadNotifications
            ->markAsRead();

        return back();
    }
}