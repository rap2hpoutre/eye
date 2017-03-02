<?php

namespace Eyewitness\Eye\Http\Controllers;

use Illuminate\Routing\Controller;
use Eyewitness\Eye\Eye;

class ServerController extends Controller
{
    /**
     * Run the server ping command.
     *
     * @param \Eyewitness\Eye\Eye  $eye
     * @return json
     */
    public function ping(Eye $eye)
    {
        return response()->json($eye->runAllChecks(), 200);
    }
}
