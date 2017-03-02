<?php

namespace Eyewitness\Eye\Witness;

class Server
{
    /**
     * Get all the server checks.
     *
     * @return array
     */
    public function check()
    {
        $data['version_php'] = phpversion();
        $data['version_laravel'] = app()->version();
        $data['reboot_required'] = $this->checkServerForReboot();
        $data['timezone'] = config('app.timezone');

        return $data;
    }

    /**
     * Check if the server reporting if it needs a reboot.
     *
     * @return boolean
     */
    public function checkServerForReboot()
    {
        return file_exists('/var/run/reboot-required');
    }
}
