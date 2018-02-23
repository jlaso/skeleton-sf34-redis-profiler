<?php

namespace AppBundle\Services\Performance;


interface ProfileLoggerInterface
{
    public function logStartTime($channel);
    public function logEndTime($channel);
    public function logString($channel, $msg);
    public function incAndLogTime($channel);

    public function get($channel);
    public function clean($channel);

    public function getLoggedChannels();
}