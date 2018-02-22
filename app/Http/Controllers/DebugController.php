<?php

namespace Eyewitness\Eye\Http\Controllers;

use Eyewitness\Eye\Eye;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;

class DebugController extends Controller
{
    /**
     * A method to help debug Eyewitness issues remotely.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! config('eyewitness.debug')) {
            return redirect(route('eyewitness.dashboard').'#overview')->withError('Sorry, but you need to enable eyewitness.debug mode to be able to view the debug page');
        }

        // Get all application config
        $config = Config::all();

        // Strip known sensitive config
        $config['services'] = null;
        $config['mail'] = null;
        $config['filesystems']['disks'] = null;

        // Now try to strip anything that might be senstive
        $this->recursive_unset($config, 'key');
        $this->recursive_unset($config, 'secret');
        $this->recursive_unset($config, 'password');

        return view('eyewitness::debug.index')->withEye(app(Eye::class))->withConfig($config);
    }

    /**
     * A method to recursively remove a given key from the array.
     * https://stackoverflow.com/a/1708914/1317935
     *
     * @param  array  &$array
     * @param  string  $unwanted_key
     * @return array
     */
    protected function recursive_unset(&$array, $unwanted_key)
    {
        unset($array[$unwanted_key]);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursive_unset($value, $unwanted_key);
            }
        }
    }
}

