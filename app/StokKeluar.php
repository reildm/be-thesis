<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    protected $table = 'stok_keluar';
    protected $primaryKey = 'id_stok_keluar';
    protected $fillable = ['id_bahan_baku','jumlah_stok_keluar','tanggal_keluar'];
    public $timestamps = false;
}
