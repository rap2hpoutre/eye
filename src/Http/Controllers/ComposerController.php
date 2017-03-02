<?php

namespace Eyewitness\Eye\Http\Controllers;

use Illuminate\Routing\Controller;
use Eyewitness\Eye\Eye;

class ComposerController extends Controller
{
    /**
     * Create a new ComposerController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('eyewitness_enabled_route:monitor_composer_lock');
    }

    /**
     * Run the commposer.lock check and return the results.
     *
     * @return json
     */
    public function ping(Eye $eye)
    {
        $result = $eye->api()->runComposerLockCheck();

        if (is_null($result)) {
            return response()->json(['error' => 'Could not run composer.lock check'], 500);
        }

        return response()->json($result, 200);        
    }
}
