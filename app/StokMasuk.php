<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    protected $table = 'stok_masuk';
    protected $primaryKey = 'id_stok_masuk';
    protected $fillable = ['id_bahan_baku','id_supplier','harga_beli', 'jumlah_stok_masuk','tanggal_masuk'];
    public $timestamps = false;
}
