<?php

use Eyewitness\Eye\App\Http\Middleware\EnabledComposerRoute;
use Illuminate\Http\Request;

class EnabledComposerRouteTest extends TestCase
{
    protected $request;
    protected $middleware;

    public function setUp()
    {
        parent::setUp();

        $this->request = new Illuminate\Http\Request();
        $this->middleware = new EnabledComposerRoute();
    }

    public function testGivesDisabledRouteWhenConfigDoesAllowIt()
    {
        $this->app['config']->set('eyewitness.monitor_composer_lock', false);

        $response = $this->middleware->handle($this->request, function() {});
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'The composer route is disabled on the server']), $response->getContent());

        $response = $this->middleware->handle($this->request, function() {}, 'monitor_composer_lock');
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'The composer route is disabled on the server']), $response->getContent());
    }

    public function testAllowsRouteWhenConfigOk()
    {
        $this->app['config']->set('eyewitness.monitor_composer_lock', true);

        $response = $this->middleware->handle($this->request, function() {}, 'monitor_composer_lock');
        $this->assertNull($response);
    }
}
