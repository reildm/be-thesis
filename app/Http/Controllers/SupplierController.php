<?php

namespace App\Http\Controllers;
use App\Supplier;

use Illuminate\Http\Request;

class SupplierController extends Controller
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
            $pagination = ceil ( count(Supplier::all()) / 10 );
            $supplier  = Supplier::offset($offset)->where('status_supplier', 1)->limit(10)->get();
            if(!empty($supplier)) {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'data'      => $supplier,
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
            $supplier = Supplier::create($request->all());
            return response()->json([
                'error'		=> '',
                'data'		=> $supplier,
                'message'	=> ''
            ], 200);
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

	public function detail($id) {
        try {
			$supplier = Supplier::find($id);
            if(!empty($supplier)) {
                return response()->json([
                    'error'     => '',
                    'data'      => $supplier,
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
			$supplier = Supplier::find($id);
	        $supplier->update($request->all());
	        return $supplier;
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }

    public function delete($id) {
        try {
            $supplier = Supplier::find($id);
            $supplier->update(['status_supplier' => '2']);
            if(!empty($supplier)) {
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Supplier telah dihapus'
		    	], 200);
            } else {
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Supplier tidak ditemukan'
		    	], 400);
            }
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
