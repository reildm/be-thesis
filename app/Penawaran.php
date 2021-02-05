<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    protected $table = 'penawaran';
    protected $primaryKey = 'id_penawaran';
    protected $fillable = ['id_bahan_baku','id_supplier','harga_penawaran','ongkos_penawaran','kualitas_penawaran','stok_penawaran','tanggal_penawaran'];
    public $timestamps = false;
}
