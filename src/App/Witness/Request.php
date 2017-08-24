<?php

namespace Eyewitness\Eye\App\Witness;

use Illuminate\Support\Facades\Cache;

class Request
{
    /**
     * Get all the request checks.
     *
     * @return array
     */
    public function check()
    {
        $data['history'] = $this->getRequestHistory();

        return $data;
    }

    /**
     * Get the request history for the server since the last poll.
     *
     * @return array
     */
    public function getRequestHistory()
    {
        $history['request_count'] = Cache::pull('eyewitness_request_count');
        $history['total_execution_time'] = Cache::pull('eyewitness_total_execution_time');

        return $history;
    }
}
