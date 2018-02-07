<?php

namespace Eyewitness\Eye\Monitors;

use Exception;
use Carbon\Carbon;
use Eyewitness\Eye\Repo\History\Dns as History;
use Eyewitness\Eye\Notifications\Messages\Dns\Change;

class Dns extends BaseMonitor
{
    /**
     * Poll the DNS for its checks.
     *
     * @return void
     */
    public function poll()
    {
        if (! count(config('eyewitness.application_domains'))) {
            $this->eye->logger()->debug('No application domain set for DNS witness');
            return;
        }

        foreach (config('eyewitness.application_domains') as $domain) {
            $record = $this->getDnsRecord($domain);

            if ($record && $this->hasDnsRecordChanged($domain, $record)) {
                if ($this->checkDnsChangeThreshold($domain)) {
                    $this->saveDnsRecord($domain, $record);
                    $this->eye->notifier()->alert(new Change(['domain' => $domain]));
                }
            }
        }
    }

    /**
     * Poll the DNS for its checks.
     *
     * @param  string  $domain
     * @return array|bool
     */
    protected function getDnsRecord($domain)
    {
        if (filter_var($domain, FILTER_VALIDATE_URL) === false) {
            $this->eye->logger()->debug('DNS URL not valid', $domain);
            return false;
        }

        try {
            $dns = $this->pollDns($this->parseUrl($domain));
        } catch (Exception $e) {
            $this->eye->logger()->debug('DNS lookup failed', ['domain' => $domain,
                                                              'exception' => $e->getMessage()]);
            return false;
        }

        return $this->stripTtlFromDns($dns);
    }

    /**
     * Check if the supplied record is different from the most recently stored
     * DNS record available.
     *
     * @param  string  $domain
     * @param  array  $record
     * @return bool
     */
    protected function hasDnsRecordChanged($domain, $record)
    {
        if (! $dns_history = History::where('meta', $domain)->orderBy('created_at', 'desc')->first()) {
            $this->saveDnsRecord($domain, $record);
            return false;
        }

        $differences = $this->compareDnsRecords($dns_history->record, $record);

        return (count($differences['original']) && count($differences['new']));
    }

    /**
     * Save the DNS record to the database.
     *
     * @param  string  $domain
     * @param  array  $record
     * @return void
     */
    protected function saveDnsRecord($domain, $record)
    {
        $this->sortArray($record);

        try {
            History::create(['type' => 'dns',
                             'meta' => $domain,
                             'record' => $this->utf8_converter($record)]);
        } catch (Exception $e) {
            $this->eye->logger()->error('DNS record save failed', $e, $domain);
            throw $e;
        }
    }

    /**
     * Compare two DNS record arrays. We can not just do a normal array
     * compare - because the arrays can appear to be different, even
     * after sorting.
     *
     * The most reliable method turns out to be a simple reduction of
     * arrays through individual compararions.
     *
     * @param  array  $original
     * @param  array  $new
     * @return array
     */
    protected function compareDnsRecords($original, $new)
    {
        $differences['original'] = $original;
        $differences['new'] = $new;

        foreach ($original as $original_id => $original_record) {
            foreach ($new as $new_id => $new_record) {
                if ($original_record == $new_record) {
                    unset($differences['original'][$original_id]);
                    unset($differences['new'][$new_id]);
                }
            }
        }

        return $differences;
    }

    /**
     * Because "get_dns_records()" can sometimes be unreliable (at times a certain
     * record will fail to be retrieved with no error, thus only giving a partial
     * result), we run the check extra times to be sure the results are valid.
     *
     * This significantly reduces the levels of "false positives" generated based
     * upon thousands of real life monitoring tests run.
     *
     * @param  string  $domain
     * @return bool
     */
    protected function checkDnsChangeThreshold($domain)
    {
        for ($i=0; $i<5; $i++) {
            $record = $this->getDnsRecord($domain);
            if (! $this->hasDnsRecordChanged($domain, $record)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Parse the supplied URL to make sure it is valid.
     * See here: http://stackoverflow.com/q/14065946/1317935
     *
     * @param  string  $url
     * @return string
     */
    protected function parseUrl($url)
    {
        return parse_url($url, PHP_URL_HOST).'.';
    }

    /**
     * Remove the remaining TTL from the results, as that will be
     * different each cycle - i.e. it is not static and so will break
     * our array comparing because no too results will ever be the
     * same if it remained in.
     *
     * @param  array  $dns
     * @return array
     */
    protected function stripTtlFromDns($dns)
    {
        foreach ($dns as $key => $subArr) {
            unset($dns[$key]['ttl']);
        }

        return $dns;
    }

    /**
     * A special way to convert UTF8 encoding into JSON. Useful for dns
     * provides like Google who sometimes return records with special
     * chars in the records.
     *
     * See: https://stackoverflow.com/questions/12236459/convert-arrays-into-utf-8-php-json
     *
     * @param  array  $array
     * @return array
     */
    protected function utf8_converter($array)
    {
        array_walk_recursive($array, function(&$item, $key){
            if(! mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });

        return $array;
    }

    /**
     * Recursive ksort function to sort array.
     *
     * See: https://gist.github.com/cdzombak/601849
     *
     * @param  array  $array
     * @return void
     */
    protected function sortArray(&$array, $sort_flags = SORT_REGULAR)
    {
        if (! is_array($array)) {
            return;
        }

        ksort($array, $sort_flags);

        foreach ($array as &$arr) {
            $this->sortArray($arr, $sort_flags);
        }
    }

    /**
     * Poll DNS.
     *
     * @param  string  $domain
     * @return array
     */
    protected function pollDns($domain)
    {
        return dns_get_record($domain, DNS_ALL);
    }
}
