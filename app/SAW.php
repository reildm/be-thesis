<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SAW extends Model
{
    protected $table = 'riwayat_saw';
    protected $primaryKey = 'id_saw';
    protected $fillable = ['id_bahan_baku','id_supplier','id_bahan_baku','harga_saw','kualitas_saw','stok_saw','ongkos_saw','nilai_prefensi','tanggal_saw'];
    public $timestamps = false;
}
