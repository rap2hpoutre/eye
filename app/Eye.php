<?php

namespace Eyewitness\Eye;

use Closure;
use ReflectionClass;
use Eyewitness\Eye\Api;
use Eyewitness\Eye\Logger;
use Eyewitness\Eye\Status;
use Eyewitness\Eye\Monitors\Log;
use Eyewitness\Eye\Monitors\Dns;
use Eyewitness\Eye\Monitors\Ssl;
use Eyewitness\Eye\Monitors\Disk;
use Eyewitness\Eye\Monitors\Debug;
use Eyewitness\Eye\Monitors\Queue;
use Eyewitness\Eye\Monitors\Server;
use Eyewitness\Eye\Monitors\Custom;
use Eyewitness\Eye\Monitors\Database;
use Eyewitness\Eye\Monitors\Composer;
use Eyewitness\Eye\Monitors\Scheduler;
use Eyewitness\Eye\Monitors\Application;
use Eyewitness\Eye\Notifications\Notifier;

class Eye
{
    const EYE_VERSION = '3.0.0';

    /**
     * A callback that can be used to authenticate users.
     *
     * @var \Closure
     */
    protected static $authUsing;

    /**
     * The Application witness.
     *
     * @var \Eyewitness\Eye\Monitors\Application
     */
    protected $application;

    /**
     * The Scheduler witness.
     *
     * @var \Eyewitness\Eye\Monitors\Scheduler
     */
    protected $scheduler;

    /**
     * The Database witness.
     *
     * @var \Eyewitness\Eye\Monitors\Database
     */
    protected $database;

    /**
     * The Composer witness.
     *
     * @var \Eyewitness\Eye\Monitors\Composer
     */
    protected $composer;

    /**
     * The Notifier manager.
     *
     * @var \Eyewitness\Eye\Notifications\Notifier
     */
    protected $notifier;

    /**
     * The Eyewitness logger.
     *
     * @var \Eyewitness\Eye\Logger
     */
    protected $logger;

    /**
     * The Eyewitness status.
     *
     * @var \Eyewitness\Eye\Status
     */
    protected $status;

    /**
     * The Server witness.
     *
     * @var \Eyewitness\Eye\Monitors\Server
     */
    protected $server;

    /**
     * The Debug wintess.
     *
     * @var \Eyewitness\Eye\Monitors\Debug
     */
    protected $debug;

    /**
     * The Queue wintess.
     *
     * @var \Eyewitness\Eye\Monitors\Queue
     */
    protected $queue;

    /**
     * The Disk witness.
     *
     * @var \Eyewitness\Eye\Monitors\Disk
     */
    protected $disk;

    /**
     * The Log witness.
     *
     * @var \Eyewitness\Eye\Monitors\Log
     */
    protected $log;

    /**
     * The Dns witness.
     *
     * @var \Eyewitness\Eye\Monitors\Dns
     */
    protected $dns;

    /**
     * The SSL witness.
     *
     * @var \Eyewitness\Eye\Monitors\Ssl
     */
    protected $ssl;

    /**
     * The Api back to Eyewitness.io server.
     *
     * @var \Eyewitness\Eye\Api
     */
    protected $api;

    /**
     * Get the version number of the package.
     *
     * @return string
     */
    public function version()
    {
        return static::EYE_VERSION;
    }

    /**
     * Check if Eyewitness appears to be installed and configured securely.
     *
     * @return bool
     */
    public function checkConfig()
    {
        return ! (empty(config('eyewitness.app_token')) || empty(config('eyewitness.secret_key')));
    }

    /**
     * Used to help spread workload for an hour slot.
     *
     * @return int
     */
    public function getHourSeed()
    {
        return (ord(config('eyewitness.app_token')) % 24);
    }

    /**
     * Used to help spread workload for a minute slot. It offsets and does not do anything in the
     * first or last 5 minutes of the hour slot, as this is usally a busy time for other functions.
     *
     * @param  int  $offset
     * @return int
     */
    public function getMinuteSeed($offset = 0)
    {
        return 5 + (($offset + ord(config('eyewitness.app_token'))) % 50);
    }

    /**
     * Get current configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        $data['eyewitness_version'] = $this->version();
        $data['eyewitness_config'] = config('eyewitness');
        $data['application_debug'] =  config('app.debug');
        $data['application_environment'] = app()->environment();

        return $data;
    }

    /**
     * Load all custom witnesses and optionally filter if they are due to run.
     *
     * @param  bool  $filterIsDue
     * @return \Illuminate\Support\Collection
     */
    public function getCustomWitnesses($filterIsDue = false)
    {
        $list = collect();

        foreach (config('eyewitness.custom_witnesses', []) as $witness) {
            if ($this->isValidWitness($witness)) {
                $instance = resolve($witness);
                if ($filterIsDue) {
                    if ($instance->isDue()) {
                        $list->push($instance);
                    }
                } else {
                    $list->push($instance);
                }
            }
        }

        return $list;
    }

    /**
     * Return the Application instance.
     *
     * @return \Eyewitness\Eye\Monitors\Application
     */
    public function application()
    {
        if (is_null($this->application)) {
            $this->application = app(Application::class);
        }

        return $this->application;
    }

    /**
     * Return the Scheduler instance.
     *
     * @return \Eyewitness\Eye\Monitors\Scheduler
     */
    public function scheduler()
    {
        if (is_null($this->scheduler)) {
            $this->scheduler = app(Scheduler::class);
        }

        return $this->scheduler;
    }

    /**
     * Return the Database instance.
     *
     * @return \Eyewitness\Eye\Monitors\Database
     */
    public function database()
    {
        if (is_null($this->database)) {
            $this->database = app(Database::class);
        }

        return $this->database;
    }

    /**
     * Return the Composer instance.
     *
     * @return \Eyewitness\Eye\Monitors\Composer
     */
    public function composer()
    {
        if (is_null($this->composer)) {
            $this->composer = app(Composer::class);
        }

        return $this->composer;
    }

    /**
     * Return the Notifier instance.
     *
     * @return \Eyewitness\Eye\Notifications\Notifier
     */
    public function notifier()
    {
        if (is_null($this->notifier)) {
            $this->notifier = app(Notifier::class);
        }

        return $this->notifier;
    }

    /**
     * Return the Eyewitness status instance.
     *
     * @return \Eyewitness\Eye\Status
     */
    public function status()
    {
        if (is_null($this->status)) {
            $this->status = app(Status::class);
        }

        return $this->status;
    }

    /**
     * Return the Eyewitness logger instance.
     *
     * @return \Eyewitness\Eye\Log
     */
    public function logger()
    {
        if (is_null($this->logger)) {
            $this->logger = app(Logger::class);
        }

        return $this->logger;
    }

    /**
     * Return the Server instance.
     *
     * @return \Eyewitness\Eye\Monitors\Server
     */
    public function server()
    {
        if (is_null($this->server)) {
            $this->server = app(Server::class);
        }

        return $this->server;
    }

    /**
     * Return the Debug instance.
     *
     * @return \Eyewitness\Eye\Monitors\Debug
     */
    public function debug()
    {
        if (is_null($this->debug)) {
            $this->debug = app(Debug::class);
        }

        return $this->debug;
    }

    /**
     * Return the Queue instance.
     *
     * @return \Eyewitness\Eye\Monitors\Queue
     */
    public function queue()
    {
        if (is_null($this->queue)) {
            $this->queue = app(Queue::class);
        }

        return $this->queue;
    }

    /**
     * Return the Disk instance.
     *
     * @return \Eyewitness\Eye\Monitors\Disk
     */
    public function disk()
    {
        if (is_null($this->disk)) {
            $this->disk = app(Disk::class);
        }

        return $this->disk;
    }

    /**
     * Return the Dns instance.
     *
     * @return \Eyewitness\Eye\Monitors\Dns
     */
    public function dns()
    {
        if (is_null($this->dns)) {
            $this->dns = app(Dns::class);
        }

        return $this->dns;
    }

    /**
     * Return the Ssl instance.
     *
     * @return \Eyewitness\Eye\Monitors\Ssl
     */
    public function ssl()
    {
        if (is_null($this->ssl)) {
            $this->ssl = app(Ssl::class);
        }

        return $this->ssl;
    }

    /**
     * Return the Log instance.
     *
     * @return \Eyewitness\Eye\Monitors\Log
     */
    public function log()
    {
        if (is_null($this->log)) {
            $this->log = app(Log::class);
        }

        return $this->log;
    }

    /**
     * Return the Api instance.
     *
     * @return \Eyewitness\Eye\Api
     */
    public function api()
    {
        if (is_null($this->api)) {
            $this->api = app(Api::class);
        }

        return $this->api;
    }

    /**
     * Is the file a valid Custom Witness.
     *
     * @param  string  $witness
     * @return bool
     */
    protected function isValidWitness($witness)
    {
        return is_subclass_of($witness, Custom::class) &&
               ! (new ReflectionClass($witness))->isAbstract();
    }

    /**
     * Compare Laravel 5.x version to the variable given.
     *
     * @param  double  $version
     * @return boolean
     */
    public static function laravelVersionIs($operator, $version, $laravel = null)
    {
        if (is_null($laravel)) {
            $laravel = app()->version();
        }

        $laravel = strtok($laravel, ' ');

        return version_compare($laravel, $version, $operator);
    }

    /**
     * Set an optional callback that can be used to authenticate users.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;
    }

    /**
     * Determine if the given request can access Eyewitness.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return false;
        })($request);
    }
}
