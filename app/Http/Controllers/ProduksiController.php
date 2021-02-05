<?php

namespace App\Http\Controllers;
use App\Produksi;
use DB;

use Illuminate\Http\Request;

class ProduksiController extends Controller
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
            $pagination = ceil ( count(Produksi::all()) / 10 );
            $produksi = DB::table('produksi')
                ->join('bahan_baku', 'produksi.id_bahan_baku', '=', 'bahan_baku.id_bahan_baku')
                ->select('produksi.id_produksi', 'bahan_baku.nama_bahan_baku', 'produksi.kepentingan_bahan_baku', 'produksi.standart_produksi')
                ->offset($offset)
                ->limit(10)
                ->get();
            if(!empty($produksi)) {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'data'      => $produksi,
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

	public function detail($id) {
        try {
            $produksi = DB::table('produksi')
                ->join('bahan_baku', 'produksi.id_bahan_baku', '=', 'bahan_baku.id_bahan_baku')
                ->select('produksi.id_produksi', 'bahan_baku.nama_bahan_baku as nama_bahan_baku', 'produksi.kepentingan_bahan_baku', 'produksi.standart_produksi')
                ->where('produksi.id_produksi','=', $id)
                ->first();
            if(!empty($produksi)) {
                return response()->json([
                    'error'     => '',
                    'data'      => $produksi,
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

    public function update($id, Request $request) {
        try {
			$produksi = Produksi::find($id);
	        $produksi->update($request->all());
	        return $produksi;
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
