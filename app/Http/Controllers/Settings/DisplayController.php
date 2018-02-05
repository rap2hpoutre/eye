<?php

namespace Eyewitness\Eye\Http\Controllers\Settings;

use Illuminate\Routing\Controller;
use Eyewitness\Eye\Repo\Notifications\Severity;
use Eyewitness\Eye\Repo\Notifications\Recipient;

class DisplayController extends Controller
{
    /**
     * Show the settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('eyewitness::settings.index')->withSeverities(Severity::all())
                                                 ->withRecipients(Recipient::all());
    }
}
