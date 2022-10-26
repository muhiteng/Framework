<?php
namespace app\app\resources;


use app\models\Role;


class RoleResource 
{
    
    public function __construct(public Role $role)
    {
       
    }
    public function toArray(): array
    {
    return[
        'name'=>$this->user->firstname,
        'display_name'=>$this->user->email,
        'description'=>$this->user->roles(),

    ];
}
}

