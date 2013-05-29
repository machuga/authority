<?php
namespace Authority\Events;

use Illuminate\Events\Dispatcher as IlluminateDispatcher;
use DateTime;

class Dispatcher extends IlluminateDispatcher
{
    /**
     * Fire an event and call the listeners.
     *
     * @param  string  $eventName
     * @param  mixed   $payload
     * @return Authority\Event
     */
    public function fire($eventName, $payload = array(), $halt = false)
    {
        if (is_array($payload))
        {
            $payload['timestamp'] = new DateTime;
            $payload = new Event($payload);
        }

        return parent::fire($eventName, array($payload), $halt);
    }

}
