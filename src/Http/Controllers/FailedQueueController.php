<?php

namespace Eyewitness\Eye\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Eyewitness\Eye\Eye;
use Exception;

class FailedQueueController extends Controller
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new FailedQueueController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('eyewitness_queue_route');

        $this->request = $request;
    }

    /**
     * Get the index of failed jobs.
     *
     * @param \Eyewitness\Eye\Eye  $eye
     * @return json
     */
    public function index(Eye $eye)
    {
        return $this->jsonp(['data' => $eye->queue()->getFailedJobs()]);
    }

    /**
     * Delete a specific failed job.
     *
     * @param  string  $id
     * @return json
     */
    public function delete($id)
    {
        try {
            if (app('queue.failer')->forget($id)) {
                return $this->jsonp(['msg' => 'Success']);
            }
        } catch (Exception $e) {
            return $this->jsonp(['error' => $e->getMessage()], 500);
        }

        return $this->jsonp(['error' => 'Could not find that log id to delete'], 404);
    }

    /**
     * Delete all failed jobs.
     *
     * @return json
     */
    public function delete_all()
    {
        try {
            app('queue.failer')->flush();
        } catch (Exception $e) {
            return $this->jsonp(['error' => $e->getMessage()], 500);
        }

        return $this->jsonp(['msg' => 'Success']);
    }

    /**
     * Retry a failed job.
     *
     * @param  string  $id
     * @return json
     */
    public function retry($id)
    {
        try {
            Artisan::call('queue:retry', ['id' => [$id]]);
        } catch (Exception $e) {
            return $this->jsonp(['error' => $e->getMessage()], 500);
        }

        return $this->jsonp(['msg' => 'Success']);
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
