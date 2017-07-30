<?php

namespace Eyewitness\Eye\App\Http\Controllers;

use Eyewitness\Eye\App\Http\Controllers\BaseController;
use Eyewitness\Eye\Eye;

class ComposerController extends BaseController
{
    /**
     * Create a new ComposerController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('eyewitness_composer_route');
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
