<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class so_tsic_eventos extends Model
{
    protected $primaryKey = 'num_correl';
    protected $table = 'so_tsic_eventos';
   
    protected $dates = ['fec_evento', 'fec_usrcrea'];
   
    public $incrementing = false;
    public $timestamps = false;
    //protected $connection = 'nombre-conexion';

    public function sic(){
        return $this->belongsTo(so_tsic::class, 'num_sic');
    }
}
