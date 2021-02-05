<?php

namespace App\Http\Controllers;
use App\Kriteria;

use Illuminate\Http\Request;

class KriteriaController extends Controller
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
            $pagination = ceil ( count(Kriteria::all()) / 10 );
            $kriteria  = Kriteria::offset($offset)->limit(10)->get();
            if(!empty($kriteria)) {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'data'      => $kriteria,
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
            $kriteria = Kriteria::create($request->all());
            return response()->json([
                'error'		=> '',
                'data'		=> $kriteria,
                'message'	=> ''
            ], 200);
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

	public function detail($id) {
        try {
			$kriteria = Kriteria::find($id);
            if(!empty($kriteria)) {
                return response()->json([
                    'error'     => '',
                    'data'      => $kriteria,
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
			$kriteria = Kriteria::find($id);
	        $kriteria->update($request->all());
	        return $kriteria;
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

    public function delete($id) {
        try {
            $kriteria = Kriteria::find($id);
            if(!empty($kriteria)) {
                $kriteria->delete();
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Kriteria telah dihapus'
		    	], 200);
            } else {
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Kriteria tidak ditemukan'
		    	], 400);
            }
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
