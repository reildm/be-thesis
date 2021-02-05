<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topsis extends Model
{
    protected $table = 'riwayat_topsis';
    protected $primaryKey = 'id_topsis';
    protected $fillable = ['id_bahan_baku','id_bahan_baku','harga_topsis','stok_topsis','kebutuhan_topsis','ketersediaan_topsis','nilai_prefensi','tanggal_topsis'];
    public $timestamps = false;
}
