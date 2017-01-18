<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    static function areRole($name,$role)
    {
        $user = User::where('user_name',$name)->first();
        return $user->hasRole($role);
    }

    public function isRole($name,$role)
    {
        $user = User::where('user_name',$name)->first();
        return $user->hasRole($role);
    }
}
