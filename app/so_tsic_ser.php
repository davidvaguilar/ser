<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class so_tsic_ser extends Model
{
    protected $table = 'so_tsic_ser';
    protected $primaryKey = 'num_correl';
    
    public $incrementing = false;
    public $timestamps = false;
}
