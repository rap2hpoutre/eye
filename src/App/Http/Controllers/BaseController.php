<?php

namespace Eyewitness\Eye\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
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
    public function __construct(Request $request)
    {
        $this->request = $request;
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
