<?php

namespace Eyewitness\Eye\App\Http\Controllers;

use Eyewitness\Eye\App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Eyewitness\Eye\Eye;
use Exception;

class LogController extends BaseController
{
    /**
     * The log witness.
     *
     * @var \Eyewitness\Eye\App\Witness\Log
     */
    protected $log;

    /**
     * Create a new LogController instance.
     *
     * @return void
     */
    public function __construct(Eye $eye, Request $request)
    {
        $this->middleware('eyewitness_log_route');

        $this->log = $eye->log();

        $this->request = $request;
    }

    /**
     * Get the index of log files and their size.
     *
     * @return json
     */
    public function index()
    {
        return $this->jsonp(['log_files' => $this->log->getLogFiles()]);
    }

    /**
     * Show a specific log file from the beginning.
     *
     * @return json
     */
    public function show()
    {
        $this->validate($this->request, [
                'filename' => 'required|string|min:3|max:60',
                'count' => 'required|integer|min:0',
                'start' => 'integer|min:0',
                'offset' => 'integer|min:0'
        ]);

        if (! in_array($this->request->filename, $this->log->getLogFilenames())) {
            return $this->jsonp(['error' => 'File not found'], 404);
        }

        return $this->jsonp($this->log->readLogFile($this->request->filename,
                                                    $this->request->count,
                                                    $this->request->offset,
                                                    $this->request->start));
    }
}
