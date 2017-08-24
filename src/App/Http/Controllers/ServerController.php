<?php

namespace Eyewitness\Eye\App\Http\Controllers;

use Eyewitness\Eye\App\Http\Controllers\BaseController;
use Eyewitness\Eye\Eye;

class ServerController extends BaseController
{
    /**
     * Run the server ping command.
     *
     * @param \Eyewitness\Eye\Eye  $eye
     * @return json
     */
    public function ping(Eye $eye)
    {
        return response()->json($eye->runAllChecks($this->request->get('send_email', 1), 200));
    }


    /**
     * Get the current configuration.
     *
     * @param \Eyewitness\Eye\Eye  $eye
     * @return json
     */
    public function config(Eye $eye)
    {
        return response()->json($eye->getConfig(), 200);
    }

    /**
     * Handle the old depreciated route to advise incoming API calls of the new version
     * without causing any service interruptions.
     *
     * @return json
     */
    public function moved()
    {
        return $this->jsonp(['msg' => 'Moved'], 410);
    }
}
