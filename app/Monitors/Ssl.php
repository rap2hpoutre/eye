<?php

namespace Eyewitness\Eye\Monitors;

use Exception;
use Eyewitness\Eye\Repo\History\Ssl as History;
use Eyewitness\Eye\Notifications\Messages\Ssl\Invalid;
use Eyewitness\Eye\Notifications\Messages\Ssl\Revoked;
use Eyewitness\Eye\Notifications\Messages\Ssl\Expiring;
use Eyewitness\Eye\Notifications\Messages\Ssl\GradeChange;

class Ssl extends BaseMonitor
{
    /**
     * Poll the SSL for its checks.
     *
     * @return void
     */
    public function poll()
    {
        if (! count(config('eyewitness.application_domains'))) {
            $this->eye->logger()->debug('No application domain set for SSL witness');
            return;
        }

        foreach (config('eyewitness.application_domains') as $domain) {
            $result = $this->getResults($domain);

            if ($result) {
                $this->checkForSslRecordChanges($domain, $result);
                $this->saveSslRecord($domain, $result);
            }
        }
    }

    /**
     * Check the given domain for a valid SSL certificate against the API.
     *
     * @param  string  $domain
     * @return void
     */
    protected function getResults($domain)
    {
        $result = $this->eye->api()->ssl($domain);

        if (isset($result['multiple_ips']) && isset($result['token'])) {
            $result = $this->eye->api()->ssl($domain, $result['multiple_ips'], $result['token']);
        }

        if (isset($result['error'])) {
            $this->eye->logger()->error('SSL API error', $result['error'], $domain);
            return null;
        }

        if (! isset($result['results'])) {
            return null;
        }

        return $result;
    }

    /**
     * Check if the supplied result is different from the most recently stored
     * SSL record available.
     *
     * @param  string  $domain
     * @param  array  $result
     * @return void
     */
    protected function checkForSslRecordChanges($domain, $result)
    {
        if (! $ssl_history = History::where('meta', $domain)->first()) {
            return;
        }

        if ($ssl_history->record['valid'] !== $result['certificates']['information'][0]['valid_now']) {
            if (! $result['certificates']['information'][0]['valid_now']) {
                $this->eye->status()->setSick('ssl_'.$domain);
                return $this->eye->notifier()->alert(new Invalid(['domain' => $domain]));
            }
        }

        if ($ssl_history->record['revoked'] !== $result['certificates']['information'][0]['revoked']) {
            if ($result['certificates']['information'][0]['revoked']) {
                $this->eye->status()->setSick('ssl_'.$domain);
                return $this->eye->notifier()->alert(new Revoked(['domain' => $domain]));
            }
        }

        if ($ssl_history->record['expires_soon'] !== $result['certificates']['information'][0]['expires_soon']) {
            if ($result['certificates']['information'][0]['expires_soon']) {
                $this->eye->status()->setSick('ssl_'.$domain);
                return $this->eye->notifier()->alert(new Expiring(['domain' => $domain,
                                                                   'valid_to' => $result['certificates']['information'][0]['valid_to']]));
            }
        }

        if ($ssl_history->record['grade'] !== $result['results']['grade']) {
            return $this->eye->notifier()->alert(new GradeChange(['domain' => $domain,
                                                                  'old_grade' => $ssl_history->record['grade'],
                                                                  'new_grade' => $result['results']['grade']]));
        }

        $this->eye->status()->setHealthy('ssl_'.$domain);
    }

    /**
     * Save the SSL record to the database.
     *
     * @param  string  $domain
     * @param  array  $result
     * @return void
     */
    protected function saveSslRecord($domain, $result)
    {
        try {
            History::where('meta', $domain)->delete();
            History::create(['type' => 'ssl',
                             'meta' => $domain,
                             'record' => ['grade' => $result['results']['grade'],
                                          'results_url' => $result['internals']['alternate_url'],
                                          'valid' => $result['certificates']['information'][0]['valid_now'],
                                          'valid_from' => $result['certificates']['information'][0]['valid_from'],
                                          'valid_to' => $result['certificates']['information'][0]['valid_to'],
                                          'revoked' => $result['certificates']['information'][0]['revoked'],
                                          'expires_soon' => $result['certificates']['information'][0]['expires_soon'],
                                          'issuer' => $result['certificates']['information'][0]['issuer_cn'],
                             ]]);
        } catch (Exception $e) {
            $this->eye->logger()->error('Ssl record save failed', $e, $domain);
            throw $e;
        }
    }
}
