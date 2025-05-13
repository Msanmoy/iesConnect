<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function destroy(\Illuminate\Notifications\DatabaseNotification $notification)
    {
        if ($notification->notifiable_id === auth()->id()) {
            $notification->delete();
            return back()->with('success', 'Notificación eliminada.');
        }

        abort(403);
    }

}
