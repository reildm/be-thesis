<?php

namespace App\Http\Controllers;
use App\BahanBaku;
use App\Produksi;

use Illuminate\Http\Request;

class BahanBakuController extends Controller
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
            $pagination = ceil ( count(BahanBaku::all()) / 10 );
            $bahanbaku  = BahanBaku::offset($offset)->limit(10)->get();
            // $bahanbaku = BahanBaku::all();
            if(!empty($bahanbaku)) {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'data'      => $bahanbaku,
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
        	$bahanbaku = new BahanBaku();
        	$produksi = new Produksi();
        	$bahanbaku->nama_bahan_baku				= $request->nama_bahan_baku;
        	$bahanbaku->harga_terakhir				= $request->harga_terakhir;
        	$bahanbaku->stok_bahan_baku				= $request->stok_bahan_baku;
        	$bahanbaku->satuan_bahan_baku			= $request->satuan_bahan_baku;
        	$bahanbaku->minimal_pembelian			= $request->minimal_pembelian;
        	$bahanbaku->ketersediaan				= $request->ketersediaan;
            $bahanbaku->tipe                         = 2;
        	$bahanbaku->save();
        	$produksi->id_bahan_baku				= $bahanbaku->id_bahan_baku;
        	$produksi->kepentingan_bahan_baku		= $request->kepentingan_bahan_baku;
        	$produksi->standart_produksi			= $request->standart_produksi;
        	$produksi->save();
			$join = array_merge($bahanbaku->toArray(), $produksi->toArray());
            return response()->json([
                'error'		=> '',
                'data'		=> $join,
                'message'	=> ''
            ], 200);
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

	public function detail($id) {
        try {
			$bahanbaku = BahanBaku::find($id);
			$produksi = Produksi::where('id_bahan_baku', '=', $id)->first();
			$join = array_merge($bahanbaku->toArray(), $produksi->toArray());
            if(!empty($join)) {
                return response()->json([
                    'error'     => '',
                    'data'      => $join,
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
			$bahanbaku = BahanBaku::find($id);
	        $bahanbaku->update($request->all());
	        return $bahanbaku;
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

    public function delete($id) {
        try {
            $bahanbaku = BahanBaku::find($id);
			$produksi = Produksi::where('id_bahan_baku', '=', $id)->first();
            if(!empty($bahanbaku) && !empty($produksi)) {
                $produksi->delete();
                $bahanbaku->delete();
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Bahan baku telah dihapus'
		    	], 200);
            } else {
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Bahan baku tidak ditemukan'
		    	], 400);
            }
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
