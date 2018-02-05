<?php

namespace Eyewitness\Eye;

use Exception;
use Illuminate\Support\Facades\Log;

class Logger
{
    /**
     * Capture an error relating to Eyewitness.
     *
     * @param  string  $message
     * @param  string|\Exception  $e
     * @param  string|null  $data
     * @return void
     */
    public function error($message, $e, $data = null)
    {
        if ($e instanceOf Exception) {
            $e = $e->getMessage();
        }

        Log::error('Eyewitness: '.$message, ['exception' => $e,
                                             'data' => $data]);
    }

    /**
     * Capture a debug issue relating to Eyewitness.
     *
     * @param  string  $message
     * @param  string|null  $data
     * @return void
     */
    public function debug($message, $data = null)
    {
        if (config('eyewitness.debug', false)) {
            Log::debug('Eyewitness: '.$message, ['data' => $data]);
        }
    }
}
