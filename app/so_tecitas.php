<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class so_tecitas extends Model
{
    protected $table = 'so_tecitas';
    public $incrementing = false;
    public $timestamps = false;   


    public function bloque(){
        return $this->belongsTo(gg_tbloque::class, 'num_bloque');
    }
}
