<?php

namespace AppBundle\Services\Performance;


class Event
{
    const EVENT_START = 0;
    const EVENT_END = 1;
    const EVENT_INC = 2;
    const EVENT_LOG = 3;

    /** @var string */
    protected $channel;
    /** @var integer */
    protected $time;
    /** @var string */
    protected $msg;
    /** @var integer */
    protected $event;

    /**
     * Event constructor.
     * @param string $channel
     * @param string $msg
     * @param int $event
     * @param int $time
     */
    public function __construct($channel, $event, $msg = '', $time = null)
    {
        $this->channel = $channel;
        $this->msg = $msg;
        $this->event = $event;
        $this->time = $time ?: intval(date('U'));
    }

    public function serialized()
    {
        $data = [
            'a' => $this->channel,
            'b' => $this->event,
            'c' => $this->msg,
            'd' => $this->time,
        ];

        return serialize($data);
    }

    public static function fromSerialized($text)
    {
        $data = unserialize($text);

        return new Event($data['a'], $data['b'], $data['c'], $data['d']);
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @return int
     */
    public function getEvent()
    {
        return $this->event;
    }
}