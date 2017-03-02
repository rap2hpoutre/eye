<?php

use Eyewitness\Eye\Http\Middleware\EnabledRoute;
use Illuminate\Http\Request;

class EnabledRouteTest extends TestCase
{
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new Illuminate\Http\Request();
        $this->er = new EnabledRoute();
    }

    public function testGivesDisabledRouteWhenConfigDoesAllowIt()
    {
        $response = $this->er->handle($this->request, function() {}, 'wrong_config');
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'The route is disabled on the server']), $response->getContent());

        $this->app['config']->set('eyewitness.routes_queue', false);

        $response = $this->er->handle($this->request, function() {}, 'routes_queue');
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'The route is disabled on the server']), $response->getContent());
    }

    public function testAllowsRouteWhenConfigOk()
    {
        $this->app['config']->set('eyewitness.routes_queue', true);

        $response = $this->er->handle($this->request, function() {}, 'routes_queue');
        $this->assertNull($response);
    }
}
