<?php

namespace AppBundle\DataCollector;

use AppBundle\Services\Performance\ProfileLoggerInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileLoggerCollector extends DataCollector
{
    /** @var ProfileLoggerInterface */
    protected $profileLogger;

    /**
     * ProfileCollector constructor.
     * @param ProfileLoggerInterface $profileLogger
     */
    public function __construct(ProfileLoggerInterface $profileLogger)
    {
        $this->profileLogger = $profileLogger;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
            'channels' => $this->profileLogger->getLoggedChannels(),
            'info' => [
                'AJAX' => 'None',
            ],
        );
    }

    public function reset()
    {
        // $this->data = array();
    }

    public function getName()
    {
        return 'app.profile_logger_collector';
    }

    public function getChannels()
    {
        return $this->data['channels'];
    }

    public function getInfo()
    {
        return $this->data['info'];
    }
}