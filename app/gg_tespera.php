<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gg_tespera extends Model
{
    protected $table = 'gg_tespera';
    protected $primaryKey = 'num_correl';
    
    public $incrementing = false;
    public $timestamps = false;
}
