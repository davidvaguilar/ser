<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class so_tsic extends Model
{
    protected $primaryKey = 'num_sic';
    protected $table = 'so_tsic';

    public $incrementing = false;
    public $timestamps = false;

    protected $dates = ['fec_solic'];


    public function sic_eventos(){
        return $this->hasMany(so_tsic_eventos::class, 'num_sic');
    }
}
