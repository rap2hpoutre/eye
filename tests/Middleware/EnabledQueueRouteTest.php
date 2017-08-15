<?php

use Eyewitness\Eye\App\Http\Middleware\EnabledQueueRoute;
use Illuminate\Http\Request;

class EnabledQueueRouteTest extends TestCase
{
    protected $request;
    protected $middleware;

    public function setUp()
    {
        parent::setUp();

        $this->request = new Illuminate\Http\Request();
        $this->middleware = new EnabledQueueRoute();
    }

    public function test_gives_disabled_route_when_config_does_not_allow_it()
    {
        $this->app['config']->set('eyewitness.routes_queue', false);

        $response = $this->middleware->handle($this->request, function() {});
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'The queue route is disabled on the server']), $response->getContent());

        $response = $this->middleware->handle($this->request, function() {}, 'routes_queue');
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'The queue route is disabled on the server']), $response->getContent());
    }

    public function test_allows_route_when_config_ok()
    {
        $this->app['config']->set('eyewitness.routes_queue', true);

        $response = $this->middleware->handle($this->request, function() {}, 'routes_queue');
        $this->assertNull($response);
    }
}
