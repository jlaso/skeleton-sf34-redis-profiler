<?php

namespace AppBundle\Services\Performance;

class RedisProfileLoggerService implements ProfileLoggerInterface
{
    /** @var \Predis\Client */
    protected $redis;

    const MARK = 'EVENT';

    /**
     * ProfileAndMeasureService constructor.
     * @param \Predis\Client $redis
     */
    public function __construct(\Predis\Client $redis)
    {
        $this->redis = $redis;
    }

    protected function buildKey(Event $event)
    {
        return $this->buildKeyInt($event->getChannel(), $event->getTime());
    }

    protected function buildKeyInt($channel, $time)
    {
        return sprintf("%s:%s:%s", self::MARK, $channel, $time);
    }

    protected function writeEvent(Event $event)
    {
        $this->redis->set($this->buildKey($event), $event->serialized());
    }

    public function logStartTime($channel)
    {
        $this->writeEvent(new Event($channel, Event::EVENT_START));
    }

    public function logEndTime($channel)
    {
        $this->writeEvent(new Event($channel, Event::EVENT_END));
    }

    public function logString($channel, $msg)
    {
        $this->writeEvent(new Event($channel, Event::EVENT_LOG, $msg));
    }

    public function incAndLogTime($channel)
    {
        $this->writeEvent(new Event($channel, Event::EVENT_INC));
    }

    public function get($channel)
    {
        $result = [];
        $keys = $this->redis->keys($this->buildKeyInt($channel, '*'));
        foreach ($keys as $key) {
            $result[] = Event::fromSerialized($this->redis->get($key));
        }

        return $result;
    }

    public function clean($channel)
    {
        $keys = $this->redis->keys($this->buildKeyInt($channel, '*'));
        if (count($keys)) {
            $this->redis->del($keys);
        }
    }

    public function getLoggedChannels()
    {
        $result = [];
        $keys = $this->redis->keys(self::MARK.':*');
        foreach($keys as $key){
            if (preg_match("/:(?<channel>.*):/", $key, $matches)){
                $channel = $matches['channel'];
                if (!isset($result[$channel])) {
                    $result[$channel] = [];
                }
                $result[$channel][] = Event::fromSerialized($this->redis->get($key));
            }
        }
        return $result;
    }
}