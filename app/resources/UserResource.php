<?php
namespace app\app\resources;


use app\models\User;


class UserResource 
{
    public  $user;
    public function __construct(User $user)
    {
        $this->user= $user;
    }
    public function toArray(): array
    {
    return[
        'username'=>$this->user->firstname,
        'email'=>$this->user->email,
        'roles'=>$this->user->roles(),
        'permissions'=>$this->user->permissions()

    ];
}
}

