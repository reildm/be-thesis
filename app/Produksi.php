<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksi';
    protected $primaryKey = 'id_produksi';
    protected $fillable = ['id_bahan_baku','kepentingan_bahan_baku','standart_produksi'];
    public $timestamps = false;
}
