<?php

namespace Eyewitness\Eye\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Eyewitness\Eye\Repo\Notifications\Severity;

class SeverityController extends Controller
{
    /**
     * Update the notifications list. It is a little ineffiecient, but
     * the reality is this will occur very infrequently.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->has('notification')) {
            foreach ($request->notification as $id => $severity) {
                if (in_array(strtolower($severity), ['low', 'medium', 'high', 'disabled'])) {
                    Severity::where('id', $id)->update(['severity' => strtolower($severity)]);
                }
            }
        }

        return redirect(route('eyewitness.settings.index').'#severity')->withSuccess('The notification severity settings have been updated.');
    }
}
