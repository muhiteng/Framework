<?php
namespace app\app\Events;

use app\app\Events\Event;
use app\app\Events\EventManagerInterface;

class EventManager implements EventManagerInterface
{
  
    private $listeners=[];
    public function attach($event, $callback, $priority = 0)
    {
        $this->listeners[$event][]=[
            'callback'=>$callback,
            'priority'=>$priority
        ];
        return true;
        
    }

    /**
     * Detaches a listener from an event
     *
     * @param string $event the event to attach too
     * @param callable $callback a callable function
     * @return bool true on success false on failure
     */
    public function detach($event, $callback){
        $this->listeners[$event]=array_filter($this->listeners[$event],function($listner) use ($callback){
            return $listner['callback'] !== $callback;
        });
        return true;
    }

    /**
     * Clear all listeners for a given event
     *
     * @param  string $event
     * @return void
     */
    public function clearListeners($event){
        $listeners[$event]=[];
    }

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param  string|EventInterface $event
     * @param  object|string $target
     * @param  array|object $argv
     * @return mixed
     */
    public function trigger($event, $target = null, $argv = array())
    {
        if(is_string($event))
        {
           $event= $this->makeEvent($event, $target, $argv);
        }
        if(isset($this->listeners[$event->getName()]))
        {
            $listeners=$this->listeners[$event->getName()];
            usort($listeners,function($listenerA,$listenerB)
            {
                return $listenerB-$listenerA;
            });
            
            foreach($listeners as ['callback'=>$callback])
            {
                if($event->isPropagationStopped())
                {
                        break;
                }
                    call_user_func($callback,$event);
            }
        }

    }
    private function makeEvent(string $eventName, $target = null ,array  $argv=[]):EventInterface
    {
        $event= new Event();
        $event->setName($eventName);
        $event->setTarget($target);
        $event->setParams($argv);

        return $event;
    }
}