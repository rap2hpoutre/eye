<?php

namespace Eyewitness\Eye\Monitors;

use Eyewitness\Eye\Eye;

class BaseMonitor
{
    /**
     * The eye instance.
     *
     * @var \Eyewitness\Eye\Eye;
     */
    protected $eye;

   /**
    * Create a new monitor instance.
    *
    * @return void
    */
   public function __construct(Eye $eye)
   {
       $this->eye = $eye;
   }

}
