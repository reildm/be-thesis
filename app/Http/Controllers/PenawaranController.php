<?php

namespace App\Http\Controllers;
use App\Penawaran;
use App\BahanBaku;
use App\Supplier;
use DB;

use Illuminate\Http\Request;

class PenawaranController extends Controller
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
            $pagination = ceil ( count(Penawaran::all()) / 10 );
            $penawaran = DB::table('penawaran')
                ->join('bahan_baku', 'penawaran.id_bahan_baku', '=', 'bahan_baku.id_bahan_baku')    
                ->join('supplier', 'penawaran.id_supplier', '=', 'supplier.id_supplier')
                ->select('penawaran.id_penawaran', 'bahan_baku.nama_bahan_baku', 'supplier.nama_supplier', 'penawaran.harga_penawaran', 'penawaran.ongkos_penawaran', 'penawaran.kualitas_penawaran', 'penawaran.stok_penawaran', 'penawaran.tanggal_penawaran')
                ->orderBy('tanggal_penawaran', 'desc')
                ->offset($offset)
                ->limit(10)
                ->get();
            $bahanbaku = BahanBaku::all();
            $supplier = Supplier::all()->where('status_supplier', 1);
            if(!empty($penawaran)) {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'data'      => $penawaran,
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
            $penawaran = Penawaran::create($request->all());
            return response()->json([
                'error'		=> '',
                'data'		=> $penawaran,
                'message'	=> ''
            ], 200);
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

	public function detail($id) {
        try {
            $penawaran = DB::table('penawaran')
                ->join('bahan_baku', 'penawaran.id_bahan_baku', '=', 'bahan_baku.id_bahan_baku')    
                ->join('supplier', 'penawaran.id_supplier', '=', 'supplier.id_supplier')
                ->select('penawaran.id_penawaran', 'bahan_baku.nama_bahan_baku', 'supplier.nama_supplier', 'penawaran.harga_penawaran', 'penawaran.ongkos_penawaran', 'penawaran.kualitas_penawaran', 'penawaran.stok_penawaran', 'penawaran.tanggal_penawaran')
                ->where('penawaran.id_penawaran', '=', $id)
                ->get();
			// $penawaran = Penawaran::find($id);
            if(!empty($penawaran)) {
                return response()->json([
                    'error'     => '',
                    'data'      => $penawaran,
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
			$penawaran = Penawaran::find($id);
	        $penawaran->update($request->all());
	        return $penawaran;
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

    public function delete($id) {
        try {
            $penawaran = Penawaran::find($id);
            if(!empty($penawaran)) {
                $penawaran->delete();
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Penawaran telah dihapus'
		    	], 200);
            } else {
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Penawaran tidak ditemukan'
		    	], 400);
            }
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
