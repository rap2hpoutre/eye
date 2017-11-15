<?php

namespace Eyewitness\Eye\App\Commands;

use Illuminate\Console\Command;
use Eyewitness\Eye\Eye;
use Exception;

class InstallCommand extends Command
{
    /**
     * The eye instance.
     *
     * @var \Eyewitness\Eye\Eye;
     */
    protected $eye;

    /**
     * Regenerate new keys.
     *
     * @var bool;
     */
    protected $regenerate = true;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eyewitness:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eyewitness.io - initial package installation and configuration';

    /**
     * Create the Install command.
     *
     * @param  \Eyewitness\Eye\Eye  $eye
     * @return void
     */
    public function __construct(Eye $eye)
    {
        parent::__construct();

        $this->eye = $eye;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->eye->checkConfig()) {
            if (! $this->handleUpgrade()) {
                return;
            }
        }

        $this->displayInitialInstallMessage();

        $this->callSilent('vendor:publish', ['--provider' => 'Eyewitness\Eye\EyeServiceProvider', '--force' => true]);

        try {
            $this->setDefaultConfig();
        } catch (Exception $e) {
            return $this->failedInstallation('queue configuration', $e);
        }

        try {
            $app = $this->eye->runAllChecks(false);
        } catch (Exception $e) {
            return $this->failedInstallation('application data', $e);
        }

        try {
            if ($this->regenerate) {
                $keys = $this->eye->api()->install($app);
            } else {
                $keys = ['app_token' => $this->laravel['config']['eyewitness.app_token'],
                         'secret_key' => $this->laravel['config']['eyewitness.secret_key']];
            }
        } catch (Exception $e) {
            return $this->failedInstallation('API installation', $e);
        }

        try {
            $this->setTokenAndKey($keys);
        } catch (Exception $e) {
            return $this->failedInstallation('set Token & Keys', $e);
        }

        $this->displayOutcome();
    }

    /**
     * Set the application token and the secret key in the config file.
     *
     * @param  array  $keys
     * @return void
     */
    protected function setTokenAndKey($keys)
    {
        $this->modifyConfigFile(Eye::APP_TOKEN_PLACEHOLDER, $keys['app_token']);
        $this->modifyConfigFile(Eye::SECRET_KEY_PLACEHOLDER, $keys['secret_key']);

        $this->laravel['config']['eyewitness.app_token'] = $keys['app_token'];
        $this->laravel['config']['eyewitness.secret_key'] = $keys['secret_key'];
    }

    /**
     * Set the default config options.
     *
     * @return void
     */
    protected function setDefaultConfig()
    {
        $offset = rand(0,4);

        $this->modifyConfigFile(Eye::QUEUE_CONNECTION_PLACEHOLDER, config('queue.default'));
        $this->modifyConfigFile(Eye::QUEUE_TUBE_PLACEHOLDER, $this->getDefaultQueue());
        $this->modifyConfigFile(Eye::CRON_OFFSET_PLACEHOLDER, $offset);

        $this->laravel['config']['eyewitness.queue_tube_list'] = [config('queue.default') => [$this->getDefaultQueue()]];
        $this->laravel['config']['eyewitness.cron_offset'] = $offset;
    }

    /**
     * Display the initial installation message.
     *
     * @return void
     */
    protected function displayInitialInstallMessage()
    {
        $this->info('______________________________________');
        $this->info(' ');
        $this->info('Installing and configuring package for Eyewitness.io....');
        $this->info(' ');
    }

    /**
     * Display the outcome of the installation.
     *
     * @return void
     */
    protected function displayOutcome()
    {
        $this->info('______________________________________');
        $this->info(' ');
        $this->info('Success! Your Laravel application has been configured and is now being monitored by Eyewitness.io!');
        $this->info(' ');
        $this->info('   App Token: '.config('eyewitness.app_token'));
        $this->info('   Secret Key: '.config('eyewitness.secret_key'));
        $this->info(' ');
        $this->info('Optional: You can provide your email address now, and we will send you an email with the "app_token" and "secret_key" included. This email will be sent by our email server, so it is ok if you do not have email configured on this server. No spam, we promise!');
        $this->info(' ');

        $this->handleOptionalEmailRequest();
    }

    /**
     * Handle the optional email question.
     *
     * @return void
     */
    protected function handleOptionalEmailRequest()
    {
        while (true) {
            $email = $this->ask('Your email address (optional - leave blank if you do not want an email):', false);

            if ($email === false) {
                $this->info('______________________________________');
                $this->info(' ');
                $this->info('Now that your package is installed - please head to https://eyewitness.io and login to view your application monitor. You will need your "app_token" and "secret_key" to access your server on the website.');
                $this->info(' ');
                $this->info('You can copy and paste these from above. Or you can get them from your '.config_path('eyewitness.php').' file as well.');
                $this->info(' ');
                return;
            }

            if ($this->validateEmail($email)) {
                $this->eye->api()->sendInstallEmail($email);
                $this->info(' ');
                $this->info('Email sent via Eyewitness.io to "'.$email.'". Please check your inbox for a copy of your "app_token" and "secret_key"');
                $this->info(' ');
                return;
            }

            $this->error('Sorry - that email address does not seem to be valid. Please try again.');
        }
    }

    /**
     * Validate that valuie is a valid e-mail address.
     *
     * @param  string   $value
     * @return bool
     */
    protected function validateEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Get the default tube from the config.
     *
     * @return string
     */
    protected function getDefaultQueue()
    {
        return config('queue.connections.'.config('queue.default').'.queue') ?: 'default';
    }

    /**
     * Write directly to the newly generated config file.
     *
     * @param  string  $placeholder
     * @param  string  $config
     * @return void
     */
    protected function modifyConfigFile($placeholder, $config)
    {
        $config_file = file_get_contents(config_path('eyewitness.php'));
        $config_file = str_replace($placeholder, $config, $config_file);
        file_put_contents(config_path('eyewitness.php'), $config_file);
    }

    /**
     * Display the results of a failed installation.
     *
     * @param  string  $type
     * @param  string  $error
     * @return void
     */
    protected function failedInstallation($type, $exception)
    {
        $this->error('______________________________________');
        $this->error(' ');
        $this->error('There was an error when trying to install your application during the '.$type.' process.');
        $this->error('   Exception: '.$exception->getMessage());
        $this->error('   Line: '.$exception->getLine());
        $this->error('   File: '.$exception->getFile());
        $this->error(' ');
        $this->error('Please try again or contact us at: "support@eyewitness.io" with a copy of the exception data above and we will get back to you ASAP with a solution.');
    }

    /**
     * Display the upgrade steps.
     *
     * @return bool
     */
    protected function handleUpgrade()
    {
        $this->error('______________________________________');
        $this->error('It appears that the Eyewitness package has already been installed. You only need to run the installer once per application.');
        $this->error('If you continue, this command will overwrite your eyewitness config file with the latest version.');
        $this->error('______________________________________');

        if (! $this->confirm('Do you wish to continue?')) {
            $this->info('Aborted. No changes were made. If you need assistance please contact us anytime at: support@eyewitness.io');
            return false;
        }

        $this->info('Do you want to generate a new "app_token" and "secret_key"?');
        $this->info('If you choose "no" your current keys will be kept.');
        $this->info('If you choose "yes" you will have new keys generated (and you will need to add these to the Eyewitness website).');
        $this->regenerate = $this->confirm('Generate new keys?');

        return true;
    }
}
