<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gg_tbloque extends Model
{
    protected $primaryKey = 'num_bloque';
    protected $table = 'gg_tbloque';
    public $incrementing = false;
    public $timestamps = false;

    public function agenda(){
        return $this->belongsTo(gg_tagenda::class, 'num_corage');
    }
}
