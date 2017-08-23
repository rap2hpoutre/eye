<?php

namespace Eyewitness\Eye\App\Witness;

class Disk
{
    /**
     * Get all the disk checks.
     *
     * @return array
     */
    public function check()
    {
        $data['disk_space_total'] = $this->checkTotalDiskSpace();
        $data['disk_space_available'] = $this->checkDiskFreeSpace();

        return $data;
    }

    /**
     * Get the total disk space.
     *
     * @return int
     */
    public function checkTotalDiskSpace()
    {
        return round(disk_total_space(base_path())/1024/1024/1024, 4);
    }

    /**
     * Get the available disk space.
     *
     * @return int
     */
    public function checkDiskFreeSpace()
    {
        return round(disk_free_space(base_path())/1024/1024/1024, 4);
    }
}
