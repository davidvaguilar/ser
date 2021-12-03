<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gg_tsigte_observadas extends Model
{
    protected $table = 'gg_tsigte_observadas';
    protected $dates = ['fec_observada'];
    //protected $primary_key = 'num_correl';
    public $incrementing = false;
    public $timestamps = false;
}
