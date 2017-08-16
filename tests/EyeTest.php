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
        $this->assertEquals('CRON_OFFSET_PLACEHOLDER', $this->eye::CRON_OFFSET_PLACEHOLDER);
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

    public function test_get_poll_schedule_handles_bad_config()
    {
        $this->app['config']->set('eyewitness.cron_offset', $this->eye::CRON_OFFSET_PLACEHOLDER);
        $this->assertEquals('1,7,13,19,25,31,37,43,49,55 * * * * *', $this->eye->getPollSchedule());

        $this->app['config']->set('eyewitness.cron_offset', null);
        $this->assertEquals('1,7,13,19,25,31,37,43,49,55 * * * * *', $this->eye->getPollSchedule());

        $this->app['config']->set('eyewitness.cron_offset', '');
        $this->assertEquals('1,7,13,19,25,31,37,43,49,55 * * * * *', $this->eye->getPollSchedule());
    }

    public function test_get_poll_schedule_returns_expected_schedules()
    {
        $this->app['config']->set('eyewitness.cron_offset', '0');
        $this->assertEquals('1,7,13,19,25,31,37,43,49,55 * * * * *', $this->eye->getPollSchedule());

        $this->app['config']->set('eyewitness.cron_offset', '1');
        $this->assertEquals('2,8,14,20,26,32,38,44,50,56 * * * * *', $this->eye->getPollSchedule());

        $this->app['config']->set('eyewitness.cron_offset', '2');
        $this->assertEquals('3,9,15,21,27,33,39,45,51,57 * * * * *', $this->eye->getPollSchedule());

        $this->app['config']->set('eyewitness.cron_offset', '3');
        $this->assertEquals('4,10,16,22,28,34,40,46,52,58 * * * * *', $this->eye->getPollSchedule());

        $this->app['config']->set('eyewitness.cron_offset', '4');
        $this->assertEquals('5,11,17,23,29,35,41,47,53,59 * * * * *', $this->eye->getPollSchedule());
    }
}
