<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Params extends Model
{
    protected $table = 'params';
    protected  $primaryKey = 'pa_id';
    public $timestamps = true;
    protected $guarded =[];
}
