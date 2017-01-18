<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pros extends Model
{
    protected $table = 'pros';
    protected  $primaryKey = 'pros_id';
    public $timestamps = true;
    protected $guarded =[];
}
