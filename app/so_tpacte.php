<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class so_tpacte extends Model
{
   // protected $primaryKey = 'num_corpac';
    protected $table = 'so_tpacte';

    protected $dates = ['fec_nacimi'];

    public $incrementing = false;
    public $timestamps = false;
}
