<?php
namespace app\app\Events;

use app\app\Events\EventInterface;
use app\models\User;
class DeletePostEvent extends Event
{

    protected $name="database.delete.post";
    public function __construct(User $user)
    {
        $this->setName($this->name);
        $this->setTarget($user);
        
    }
    public function grtTarget():User
    {
        return parent::getTarget();
    }
}