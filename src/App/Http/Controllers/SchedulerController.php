<?php

namespace Eyewitness\Eye\App\Http\Controllers;

use Eyewitness\Eye\App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class SchedulerController extends BaseController
{
    /**
     * The cache.
     *
     * @var $cache
     */
    protected $cache;

    /**
     * Create a new SchedulerController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('eyewitness_scheduler_route');

        $this->request = $request;

        $this->cache = app()->make('cache');
    }

    /**
     * Schedule a command to run on the next run cycle.
     *
     * @return json
     */
    public function run()
    {
        $this->validate($this->request, [
                'id' => 'required|string|min:30|max:100',
        ]);

        if ($this->cache->has('eyewitness_scheduler_adhoc')) {
            $list = json_decode($this->cache->get('eyewitness_scheduler_adhoc'), true);
        } else {
            $list = [];
        }

        $list[$this->request->input('id')] = $this->request->input('id');

        $this->cache->put('eyewitness_scheduler_adhoc', json_encode($list), 3);

        return $this->jsonp(['msg' => 'Success']);
    }

}
