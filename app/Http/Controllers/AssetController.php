<?php

namespace Eyewitness\Eye\Http\Controllers;

use Illuminate\Routing\Controller;

class AssetController extends Controller
{
    /**
     * Get the Eyewitness CSS. This allows us to keep the assets inside
     * the package without needing to publish them, whilst giving a "public"
     * link and still allows browsers to cache them as normal. We also
     * retain the ability to cache bust using file stamp times.
     *
     * @return \Illuminate\Http\Response
     */
    public function css()
    {
        return response()->make(file_get_contents(__DIR__.'/../../../resources/assets/compiled/eyewitness.css'), 200)
                         ->header('Content-Type', 'text/css');
    }

    /**
     * Get the Eyewitness JS. This allows us to keep the assets inside
     * the package without needing to publish them, whilst giving a "public"
     * link and still allows browsers to cache them as normal. We also
     * retain the ability to cache bust using file stamp times.
     *
     * @return \Illuminate\Http\Response
     */
    public function js()
    {
        return response()->make(file_get_contents(__DIR__.'/../../../resources/assets/compiled/eyewitness.js'), 200)
                         ->header('Content-Type', 'application/javascript');
    }
}
