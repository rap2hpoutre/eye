<?php

namespace Eyewitness\Eye;

use Eyewitness\Eye\Http\Middleware\CaptureRequest;
use Eyewitness\Eye\Http\Middleware\EnabledRoute;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Eyewitness\Eye\Commands\ScheduleRunCommand;
use Eyewitness\Eye\Http\Middleware\AuthRoute;
use Illuminate\Console\Scheduling\Schedule;
use Eyewitness\Eye\Commands\InstallCommand;
use Eyewitness\Eye\Commands\WorkCommand;
use Eyewitness\Eye\Commands\DownCommand;
use Illuminate\Support\ServiceProvider;
use Eyewitness\Eye\Commands\UpCommand;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\QueueManager;
use Eyewitness\Eye\Queue\Worker;
use Illuminate\Routing\Router;
use Eyewitness\Eye\Eye;


class EyeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the package.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->registerHelpers();

        $this->loadRoutes();
        $this->loadViews();
        $this->loadLogTracking();
        $this->loadMiddleware($router);

        if ($this->app->runningInConsole()) {
            $this->loadConsole();
        } else {
            $this->loadRequestTracking();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/eyewitness.php', 'eyewitness');

        $this->app->singleton(Eyewitness\Eye\Eye::class);
    }

    /**
     * Load the package routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes/api.php';
        }
    }

    /**
     * Load the package views.
     *
     * @return void
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'eyewitness');
    }

    /**
     * Load the console.
     *
     * @return void
     */
    protected function loadConsole()
    {
        $this->publishes([__DIR__.'/config/eyewitness.php' => config_path('eyewitness.php')]);

        $this->commands([InstallCommand::class]);

        $this->loadSchedulerMonitor();
        $this->loadMaintenanceMonitor();
        $this->loadQueueMonitor();
    }

    /**
     * Load the request tracking.
     *
     * @return void
     */
    protected function loadRequestTracking()
    {
        if (config('eyewitness.monitor_request')) {
            $this->app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware(CaptureRequest::class);
        }
    }

    /**
     * Load the log tracking.
     *
     * @return void
     */
    protected function loadLogTracking()
    {
        if (config('eyewitness.monitor_log')) {
            app('log')->listen(function ($level, $message = null, $context = null) {
                app(Eye::class)->log()->sendExceptionAlert($level, $message);
            });
        }
    }

    /**
     * Load the middleware. We have to take into account the current Laravel version
     * as Laravel >=5.4 changed the signature to register middleware compared
     * to <=5.3.
     *
     * @param  string  $router
     * @return void
     */
    protected function loadMiddleware($router)
    {
        if (laravel_version_greater_than_or_equal_to(5.4)) {
            $router->aliasMiddleware('eyewitness_enabled_route', EnabledRoute::class);
            $router->aliasMiddleware('eyewitness_auth', AuthRoute::class);
        } else {
            $router->middleware('eyewitness_enabled_route', EnabledRoute::class);
            $router->middleware('eyewitness_auth', AuthRoute::class);
        }
    }

    /**
     * Load the queue monitor.
     *
     * @return void
     */
    protected function loadQueueMonitor()
    {
        if (config('eyewitness.monitor_queue')) {
            $this->registerFailingQueueHandler();
            $this->registerQueueWorker();
            $this->registerWorkCommand();
        }
    }

    /**
     * Load the scheduler monitor. This extends the default schedule:run command
     * and extends it with our version, providing the ability to automatically
     * ping and heartbeat as it is running.
     *
     * @return void
     */
    protected function loadSchedulerMonitor()
    {
        if (config('eyewitness.monitor_scheduler')) {
            $this->app->extend('Illuminate\Console\Scheduling\ScheduleRunCommand', function () {
                return new ScheduleRunCommand(app('Illuminate\Console\Scheduling\Schedule'));
            });
        }
    }

    /**
     * Load the maintenance monitor. This allows us to ping when
     * the application goes up or down.
     *
     * @return void
     */
    protected function loadMaintenanceMonitor()
    {
        if (config('eyewitness.monitor_maintenance_mode')) {
            $this->app->extend('command.down', function () {
                return new DownCommand();
            });

            $this->app->extend('command.up', function() {
                return new UpCommand();
            });
        }
    }

    /**
     * Register a failing queue hander. We need to take the Laravel version into
     * account, because <=5.1 fires a different failing event to >=5.2.
     *
     * @return void
     */
    protected function registerFailingQueueHandler()
    {
        if (laravel_version_less_than_or_equal_to(5.1)) {
            app(QueueManager::class)->failing(function ($connection, $job, $data) {
                app(Eye::class)->api()->sendQueueFailingPing($connection, get_class(unserialize($data['data']['command'])), $job->getQueue());
            });
        } else {
            app(QueueManager::class)->failing(function (JobFailed $e) {
                app(Eye::class)->api()->sendQueueFailingPing($e->connectionName, $e->job->resolveName(), $e->job->getQueue());
            });
        }
    }

    /**
     * Register a new queue worker. This allows us to capture heartbeats of
     * the queue actually working. We need to take the Laravel version into
     * account, because <=5.2 uses a different worker construct compared
     * to >=5.3.
     *
     * @return void
     */
    protected function registerQueueWorker()
    {
        $this->app->singleton('queue.worker', function () {
            if (laravel_version_greater_than_or_equal_to(5.3)) {
                return new Worker($this->app['queue'], $this->app['events'], $this->app[ExceptionHandler::class]);    
            } else {
                return new Worker($this->app['queue'], $this->app['queue.failer'], $this->app['events']);
            }
        });
    }

    /**
     * Register a new queue:work command. This is only for Laravel versions
     * that are <=5.2. This is to backport a feature from >=5.3 where
     * cache is enabled on all workers (not just daemons) and improves
     * default queue detection.
     *
     * @return void
     */
    protected function registerWorkCommand()
    {
        if (laravel_version_less_than_or_equal_to(5.2)) {
            $this->app->extend('command.queue.work', function () {
                return new WorkCommand($this->app['queue.worker']);
            });
        }
    }

    /**
     * Register the helpers file. This provides an easy and simple way to
     * view and compare Laravel versions throughout the page.
     *
     * @return void
     */
    protected function registerHelpers()
    {
        require 'helpers/versioning.php';
    }
}
