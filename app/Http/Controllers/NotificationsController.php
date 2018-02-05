<?php

namespace Eyewitness\Eye\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Eyewitness\Eye\Repo\Notifications\History;

class NotificationsController extends Controller
{
    /**
     * Show the given notification.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $notification = History::findOrFail($id);

        return view('eyewitness::notifications.show')->withNotification($notification);
    }

    /**
     * Acknowledge the given notification.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $notification = History::findOrFail($id);

        $notification->acknowledged = true;
        $notification->save();

        return redirect(route('eyewitness.dashboard').'#notifications')->withSuccess('The notification has been acknowledged.');
    }
}
