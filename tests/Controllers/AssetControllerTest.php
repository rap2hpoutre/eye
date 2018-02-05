<?php

namespace Eyewitness\Eye\Test\Controllers;

use Eyewitness\Eye\Test\TestCase;

class AssetControllerTest extends TestCase
{

    public function test_css_page_loads()
    {
        $response = $this->get($this->home.'/asset/12345/eyewitness.css');

        $response->assertStatus(200);
    }

    public function test_js_page_loads()
    {
        $response = $this->get($this->home.'/asset/12345/eyewitness.js');

        $response->assertStatus(200);
    }
}
