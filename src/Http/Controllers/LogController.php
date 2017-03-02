<?php

namespace Eyewitness\Eye\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Eyewitness\Eye\Eye;
use Exception;

class LogController extends Controller
{
    use ValidatesRequests;

    /**
     * The log witness.
     *
     * @var \Eyewitness\Eye\Witness\Log
     */
    protected $log;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new LogController instance.
     *
     * @return void
     */
    public function __construct(Eye $eye, Request $request)
    {
        $this->middleware('eyewitness_enabled_route:routes_log');
        
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
                'filename' => 'required|string|min:0|max:60',
                'count' => 'required|integer|min:0',
                'start' => 'integer|min:0',
                'offset' => 'integer|min:0'
        ]);

        if ( ! in_array($this->request->filename, $this->log->getLogFilenames())) {
            return $this->jsonp(['error' => 'File not found'], 404);
        }

        return $this->jsonp($this->log->readLogFile($this->request->filename,
                                                    $this->request->count,
                                                    $this->request->offset,
                                                    $this->request->start));
    }

    /**
     * Delete the log file.
     *
     * @return json
     */
    public function delete()
    {
        $this->validate($this->request, [
                'filename' => 'required|string|min:0|max:60'
        ]);

        try {
            unlink(storage_path("logs/".$this->request->filename));
        } catch (Exception $e) {
            return $this->jsonp(['error' => 'Log failed to delete: '.$e->getMessage()], 404);
        }

        return $this->jsonp(['msg' => 'Log deleted']);
    }

    /**
     * Return an optional JSONP response.
     *
     * @param  array   $data
     * @param  string  $status_code
     * @return json
     */
    protected function jsonp($data, $status_code = 200)
    {
        return response()->json($data, $status_code)->setCallback($this->request->input('callback'));
    }
}
