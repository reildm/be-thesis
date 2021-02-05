<?php

namespace App\Http\Controllers;
use App\StokKeluar;
use App\BahanBaku;
use DB;

use Illuminate\Http\Request;

class StokKeluarController extends Controller
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
            $pagination = ceil ( count(StokKeluar::all()) / 10 );
            $stokkeluar = DB::table('stok_keluar')
                ->join('bahan_baku', 'stok_keluar.id_bahan_baku', '=', 'bahan_baku.id_bahan_baku')
                ->select('stok_keluar.id_stok_keluar', 'bahan_baku.nama_bahan_baku', 'stok_keluar.jumlah_stok_keluar', 'bahan_baku.stok_bahan_baku', 'stok_keluar.tanggal_keluar')
                ->offset($offset)
                ->limit(10)
                ->get();
            $bahanbaku = BahanBaku::all();
            if(!empty($stokkeluar)) {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'data'      => $stokkeluar,
                        'bahan'     => $bahanbaku,
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
            $stokkeluar = StokKeluar::create($request->all());
            $bahanbaku = BahanBaku::find($request->id_bahan_baku);
            $total = $bahanbaku->stok_bahan_baku - $request->jumlah_stok_keluar;
            $bahanbaku->update(array(
                    'stok_bahan_baku' => $total
                ));
            return response()->json([
                'error'		=> '',
                'data'		=> $stokkeluar,
                'message'	=> ''
            ], 200);
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

    public function detail($id) {
        try {
            $stokkeluar = StokKeluar::find($id);
            if(!empty($stokkeluar)) {
                return response()->json([
                    'error'     => '',
                    'data'      => $stokkeluar,
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
            $stokkeluar = StokKeluar::find($id);
            $bahanbaku = BahanBaku::find($stokkeluar->id_bahan_baku);
            $total = $bahanbaku->stok_bahan_baku + $stokkeluar->jumlah_stok_keluar;
            $bahanbaku->update(array(
                    'stok_bahan_baku' => $total
                ));
            if(!empty($stokkeluar)) {
                $stokkeluar->delete();
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Stok keluar telah dihapus'
		    	], 200);
            } else {
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Stok keluar tidak ditemukan'
		    	], 400);
            }
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
