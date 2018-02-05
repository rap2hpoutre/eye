<?php

namespace Eyewitness\Eye;

use Exception;
use GuzzleHttp\Client;
use Eyewitness\Eye\Eye;

class Api
{
    /**
     * The eye instance.
     *
     * @var \Eyewitness\Eye\Eye;
     */
    protected $eye;

    /**
     * The GuzzleHttp client.
     *
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * The Eyewitness.io API url.
     *
     * @var string
     */
    protected $api;

    /**
     * The default headers.
     *
     * @var array
     */
    protected $headers;

    /**
     * Create a new Api instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = app(Client::class);

        $this->eye = app(Eye::class);

        $this->api = config('eyewitness.api_url');

        $this->headers = ['connect_timeout' => 15,
                          'timeout' => 15,
                          'debug' => false];

        if (config('eyewitness.api_proxy')) {
            $this->headers['proxy'] = config('eyewitness.api_proxy');
        }
    }

    /**
     * Send a test ping to the API server.
     *
     * @return array
     */
    public function sendTestPing()
    {
        $results = $this->ping('test/ping');

        return [
            'pass' => $results[0] == "200",
            'code' => $results[0],
            'message' => $results[1],
        ];
    }

    /**
     * Send a test ping to the API server.
     *
     * @return array
     */
    public function sendTestAuthenticate()
    {
        $results = $this->ping('test/authenticate');

        return [
            'pass' => $results[0] == "200",
            'code' => $results[0],
            'message' => $results[1],
        ];
    }

    /**
     * Send an SSL API check to the wonderful people at htbridge.com and their API.
     *
     * @param  string  $domain
     * @param  string|null  $ip
     * @param  string|null  $token
     * @return mixed
     */
    public function ssl($domain, $ip = null, $token = null)
    {
        $headers = [
            'connect_timeout' => 180,
            'timeout' => 180,
            'debug' => false,
            'form_params' => [
                'domain' => $domain.':443',
                'show_test_results' => 'false',
                'recheck' => 'true',
                'verbosity' => '0'
            ]
        ];

        if ((! is_null($ip)) && (! is_null($token))) {
            $headers['form_params']['choosen_ip'] = $ip[0];
            $headers['form_params']['token'] = $token;
        }

        try {
            $response = $this->client->post('https://www.htbridge.com/ssl/api/v1/check/'.time().'.html', $headers);
            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            app(Eye::class)->logger()->error('Unable to check SSL', $e, $domain);
        }

        return null;
    }

    /**
     * Run a check for the composer lock against the SensioLabs API.
     *
     * @return json
     */
    public function composer()
    {
        $this->headers['headers'] = ['Accept' => 'application/json'];

        try {
            if ($this->isRunningGuzzle5()) {
                $this->headers['body'] = ['lock' => fopen(config('eyewitness.composer_lock_file_location'), 'r')];
            } else {
                $this->headers['multipart'] = [['name' => 'lock', 'contents' => fopen(config('eyewitness.composer_lock_file_location'), 'r')]];
            }
            $response = $this->client->post('https://security.sensiolabs.org/check_lock', $this->headers);
            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            app(Eye::class)->logger()->error('SensioLabs Composer Lock check failed', $e);
        }

        return null;
    }

    /**
     * Send the ping notification to the Eyewitness.io API. Includes a 'backoff' retry
     * attempt if the inital pings fails.
     *
     * @param  string  $route
     * @param  array   $data
     * @return mixed
     */
    protected function ping($route, $data = [])
    {
        if (! config('eyewitness.api_enabled', false)) {
            return [false, 'Api Disabled'];
        }

        $data['app_token'] = config('eyewitness.app_token');
        $data['secret_key'] = config('eyewitness.secret_key');
        $data['application_environment'] = app()->environment();
        $data['eyewitness_version'] = $this->eye->version();

        $this->headers['json'] = $data;

        for ($i=0; $i<3; $i++) {
            sleep($i);
            try {
                $response = $this->client->post($this->api."/$route", $this->headers);
                $result = [$response->getStatusCode(), $response->getReasonPhrase()];
                if ($result[0] == "200") {
                    return $result;
                }
            } catch (Exception $e) {
                $result = [false, $e->getMessage()];
            }
        }

        return $result;
    }

    /**
     * Determine if we are runing Guzzle 5 or 6 to provide dual support in the one package.
     *
     * @return bool
     */
    protected function isRunningGuzzle5()
    {
        $client = get_class($this->client);
        return version_compare($client::VERSION, '6.0.0', "<");
    }
}
