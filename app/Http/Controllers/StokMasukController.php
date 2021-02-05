<?php

namespace App\Http\Controllers;
use App\StokMasuk;
use App\BahanBaku;
use App\Supplier;
use DB;

use Illuminate\Http\Request;

class StokMasukController extends Controller
{
	public function all(Request $request) {
        try {
            $page       = $request->page;
            if ($page === null || $page < 1) {
                $page   = 1;
            }
            if ($page <= 1) {
                $offset = 0;
            } else {
                $offset = ($page - 1) * 10;
            }
            $pagination = ceil ( count(StokMasuk::all()) / 10 );
            $stokmasuk = DB::table('stok_masuk')
                ->join('bahan_baku', 'stok_masuk.id_bahan_baku', '=', 'bahan_baku.id_bahan_baku')
                ->join('supplier', 'stok_masuk.id_supplier', '=', 'supplier.id_supplier')
                ->select('stok_masuk.id_stok_masuk', 'bahan_baku.nama_bahan_baku', 'supplier.nama_supplier', 'stok_masuk.harga_beli', 'stok_masuk.jumlah_stok_masuk', 'bahan_baku.stok_bahan_baku', 'stok_masuk.tanggal_masuk')
                ->offset($offset)
                ->limit(10)
                ->get();
            $bahanbaku = BahanBaku::all();
            $supplier = Supplier::all()->where('status_supplier', 1);
            if(!empty($stokmasuk)) {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'data'      => $stokmasuk,
                        'bahan'     => $bahanbaku,
                        'supplier'  => $supplier,
                        'page'      => $pagination
                    ]),
                    'message'   => ''
                ], 200);
            } else {
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Tidak ditemukan'
		    	], 400);
            }
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

    public function store(Request $request) {
        try {
            $stokmasuk = StokMasuk::create($request->all());
            $bahanbaku = BahanBaku::find($request->id_bahan_baku);
            $total = $bahanbaku->stok_bahan_baku + $request->jumlah_stok_masuk;
            $bahanbaku->update(array(
                    'harga_terakhir' => $request->harga_beli,
                    'stok_bahan_baku' => $total
                ));
            return response()->json([
                'error'		=> '',
                'data'		=> $stokmasuk,
                'message'	=> ''
            ], 200);
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

    public function detail($id) {
        try {
            $stokmasuk = StokMasuk::find($id);
            if(!empty($stokmasuk)) {
                return response()->json([
                    'error'     => '',
                    'data'      => $stokmasuk,
                    'message'   => ''
                ], 200);
            } else {
                return response()->json([
                    'error'     => '',
                    'data'      => '',
                    'message'   => 'Tidak ditemukan'
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');       
        }
    }

    public function delete($id) {
        try {
            $stokmasuk = StokMasuk::find($id);
            $bahanbaku = BahanBaku::find($stokmasuk->id_bahan_baku);
            $total = $bahanbaku->stok_bahan_baku - $stokmasuk->jumlah_stok_masuk;
            if ($total < 0 ) {
                return response()->json([
                    'error'     => '',
                    'data'      => '',
                    'message'   => 'Stok masuk tidak dapat dihapus, karena jumlah kurang'
                ], 200);
            } else {
                $bahanbaku->update(array(
                    'stok_bahan_baku' => $total
                ));
                if(!empty($stokmasuk)) {
                    $stokmasuk->delete();
                    return response()->json([
                        'error'     => '',
                        'data'      => '',
                        'message'   => 'Stok masuk telah dihapus'
                    ], 200);
                } else {
                    return response()->json([
                        'error'     => '',
                        'data'      => '',
                        'message'   => 'Stok masuk tidak ditemukan'
                    ], 400);
                }
            }
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
