<?php

use Eyewitness\Eye\Http\Middleware\AuthRoute;
use Illuminate\Http\Request;

class AuthRouteTest extends TestCase
{
    protected $request;

    protected $ar;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('eyewitness.app_token', 'app_correct');
        $this->app['config']->set('eyewitness.secret_key', 'secret_correct');

        $this->request = new Illuminate\Http\Request();
        $this->ar = new AuthRoute();
    }

    public function testPreventsUnauthorizedAccess()
    {
        $response = $this->ar->handle($this->request, function() {});
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'Unauthorized']), $response->getContent());

        $this->request->replace([
            'app_token' => 'app_wrong',
            'secret_key' => 'secret_wrong',
        ]);

        $response = $this->ar->handle($this->request, function() {});
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'Unauthorized']), $response->getContent());
    }

    public function testAllowsAuthorizedAccessWhenCredentialsAreCorrect()
    {
        $this->request->replace([
            'app_token' => 'app_correct',
            'secret_key' => 'secret_correct',
        ]);

        $response = $this->ar->handle($this->request, function() {});
        $this->assertNull($response);
    }
}
