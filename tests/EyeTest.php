<?php

use Eyewitness\Eye\Eye;

class EyeTest extends TestCase
{
    protected $eye;

    public function setUp()
    {
        parent::setUp();

        $this->eye = new Eye;
    }

    public function test_eye_is_constructed_correctly()
    {
        $this->assertInstanceOf(Eyewitness\Eye\App\Witness\Scheduler::class, $this->eye->scheduler());
        $this->assertInstanceOf(Eyewitness\Eye\App\Witness\Database::class, $this->eye->database());
        $this->assertInstanceOf(Eyewitness\Eye\App\Witness\Request::class, $this->eye->request());
        $this->assertInstanceOf(Eyewitness\Eye\App\Witness\Server::class, $this->eye->server());
        $this->assertInstanceOf(Eyewitness\Eye\App\Witness\Queue::class, $this->eye->queue());
        $this->assertInstanceOf(Eyewitness\Eye\App\Witness\Email::class, $this->eye->email());
        $this->assertInstanceOf(Eyewitness\Eye\App\Witness\Disk::class, $this->eye->disk());
        $this->assertInstanceOf(Eyewitness\Eye\App\Witness\Log::class, $this->eye->log());
        $this->assertInstanceOf(Eyewitness\Eye\App\Api\Api::class, $this->eye->api());
    }

    public function test_constants_are_set()
    {
        $this->assertEquals('QUEUE_CONNECTION_PLACEHOLDER', $this->eye::QUEUE_CONNECTION_PLACEHOLDER);
        $this->assertEquals('QUEUE_TUBE_PLACEHOLDER', $this->eye::QUEUE_TUBE_PLACEHOLDER);
        $this->assertEquals('SECRET_KEY_PLACEHOLDER', $this->eye::SECRET_KEY_PLACEHOLDER);
        $this->assertEquals('APP_TOKEN_PLACEHOLDER', $this->eye::APP_TOKEN_PLACEHOLDER);
        $this->assertTrue($this->eye::EYE_VERSION !== '');
    }

    public function test_config_correctly_detects_if_settings_are_valid()
    {
        $this->app['config']->set('eyewitness.app_token', '');
        $this->app['config']->set('eyewitness.secret_key', '');
        $this->assertFalse($this->eye->checkConfig());

        $this->app['config']->set('eyewitness.app_token', null);
        $this->app['config']->set('eyewitness.secret_key', null);
        $this->assertFalse($this->eye->checkConfig());

        $this->app['config']->set('eyewitness.app_token', 'APP_TOKEN_PLACEHOLDER');
        $this->app['config']->set('eyewitness.secret_key', 'SECRET_KEY_PLACEHOLDER');
        $this->assertFalse($this->eye->checkConfig());

        $this->app['config']->set('eyewitness.app_token', 'blah');
        $this->app['config']->set('eyewitness.secret_key', 'blah');
        $this->assertTrue($this->eye->checkConfig());
    }
}
