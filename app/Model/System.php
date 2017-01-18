<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $table = 'system';
    protected  $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded =[];
}
