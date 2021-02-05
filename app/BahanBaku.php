<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    protected $primaryKey = 'id_bahan_baku';
    protected $fillable = ['nama_bahan_baku','harga_terakhir','stok_bahan_baku', 'satuan_bahan_baku','minimal_pembelian','ketersediaan','tipe'];
    public $timestamps = false;
}
