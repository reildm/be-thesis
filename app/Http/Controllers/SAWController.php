<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SAW;
use App\Supplier;
use App\BahanBaku;
use App\Kriteria;
use App\Penawaran;
use DB;

class SAWController extends Controller
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
            $pagination = ceil ( count(saw::all()) / 10 );
            $saw = DB::table('riwayat_saw')
            	->join('supplier', 'riwayat_saw.id_supplier', '=', 'supplier.id_supplier')
                ->join('bahan_baku', 'riwayat_saw.id_bahan_baku', '=', 'bahan_baku.id_bahan_baku')
                ->select('riwayat_saw.id_saw', 'supplier.nama_supplier', 'bahan_baku.nama_bahan_baku', 'riwayat_saw.harga_saw', 'riwayat_saw.kualitas_saw', 'riwayat_saw.stok_saw', 'riwayat_saw.ongkos_saw', 'riwayat_saw.nilai_prefensi', 'riwayat_saw.tanggal_saw')
                ->offset($offset)
                ->limit(10)
                ->get();
            $bahanbaku = BahanBaku::all();
            if(!empty($saw)) {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'data'      => $saw,
                        'page'      => $pagination,
                        'bahan_baku'=> $bahanbaku
                    ]),
                    'message'   => '',
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

    public function store($id) {
        try {
        	// get data
            $bahanbaku = DB::table('bahan_baku')
                ->select('nama_bahan_baku')
                ->where('id_bahan_baku','=',$id)->get();
        	$sawData = DB::table('penawaran')
        		->join('supplier', 'penawaran.id_supplier', '=', 'supplier.id_supplier')
    			->join('bahan_baku', 'penawaran.id_bahan_baku', '=', 'bahan_baku.id_bahan_baku')
    			->join(
    				DB::raw("(
    					SELECT id_supplier, id_bahan_baku, harga_penawaran, ongkos_penawaran, kualitas_penawaran, stok_penawaran, MAX(tanggal_penawaran) as mxdate from penawaran where id_bahan_baku = '$id' GROUP BY id_supplier) b "),
    				function($join) {
    					$join
    					->on('penawaran.id_supplier', '=', 'b.id_supplier')
    					->on('penawaran.tanggal_penawaran', '=', 'b.mxdate');
    				}
    			)
        		->select('supplier.id_supplier','bahan_baku.id_bahan_baku','supplier.nama_supplier','bahan_baku.nama_bahan_baku','penawaran.harga_penawaran','penawaran.ongkos_penawaran','penawaran.kualitas_penawaran','penawaran.stok_penawaran', 'penawaran.tanggal_penawaran')
        		->orderBy('penawaran.tanggal_penawaran', 'DESC')
        		->get();
            $kriteria = DB::table('kriteria')->where('metode_kriteria', 2)->get();
			$totalData  = $sawData->count();
			// normalisasi
			if ($totalData !== 0) {
				$arrayHarga		= [];
				$arrayOngkos	= [];
				$arrayKualitas	= [];
				$arrayStok	= [];
                for ( $i = 0; $i < $totalData; $i++ ) {
                    $dataHarga		= $sawData[$i]->harga_penawaran;
                    $dataOngkos		= $sawData[$i]->ongkos_penawaran;
                    $dataKualitas	= $sawData[$i]->kualitas_penawaran;
                    $dataStok		= $sawData[$i]->stok_penawaran;
                    array_push($arrayHarga, $dataHarga);
                    array_push($arrayOngkos, $dataOngkos);
                    array_push($arrayKualitas, $dataKualitas);
                    array_push($arrayStok, $dataStok);
                }
                $minHarga		= min($arrayHarga);
                $minOngkos		= min($arrayOngkos);
                $maxKualitas	= max($arrayKualitas);
                $maxStok		= max($arrayStok);
                for ( $i = 0; $i < $totalData; $i++ ) {
					$namaSupplierNormalisasi	= $sawData[$i]->nama_supplier;
					$namaBahanBakuNormalisasi	= $sawData[$i]->nama_bahan_baku;
					$hargaNormalisasi			= $minHarga / $sawData[$i]->harga_penawaran;
					$ongkosNormalisasi			= $minOngkos / $sawData[$i]->ongkos_penawaran;
					$kualitasNormalisasi		= $sawData[$i]->kualitas_penawaran / $maxKualitas;
					$stokNormalisasi			= $sawData[$i]->stok_penawaran / $maxStok;
					$dataNormalisasi[$i]		= [
						'nama_supplier'			=> $namaSupplierNormalisasi,
						'nama_bahan_baku'		=> $namaBahanBakuNormalisasi,
						'harga_normalisasi'		=> number_format((float)$hargaNormalisasi, 2, '.', ''),
						'ongkos_normalisasi'	=> number_format((float)$ongkosNormalisasi, 2, '.', ''),
						'kualitas_normalisasi'	=> number_format((float)$kualitasNormalisasi, 2, '.', ''),
						'stok_normalisasi'		=> number_format((float)$stokNormalisasi, 2, '.', '')
					];
                }
                // ranking
                if(!empty($dataNormalisasi)) {
	                for ( $i = 0; $i < $totalData; $i++ ) {
	            		$hargaRanking		= $dataNormalisasi[$i]['harga_normalisasi'] * $kriteria[0]->bobot_kriteria;
	            		$ongkosRanking		= $dataNormalisasi[$i]['ongkos_normalisasi'] * $kriteria[3]->bobot_kriteria;
	            		$kualitasRanking	= $dataNormalisasi[$i]['kualitas_normalisasi'] * $kriteria[1]->bobot_kriteria;
	            		$stokRanking		= $dataNormalisasi[$i]['stok_normalisasi'] * $kriteria[2]->bobot_kriteria;
	                	$dataRanking[$i]			= [
	                		'nama_supplier'			=> $sawData[$i]->nama_supplier,
	                		'nama_bahan_baku'		=> $sawData[$i]->nama_bahan_baku,
	                		'harga_ranking'		    => number_format((float)$hargaRanking, 2, '.', ''),
	                		'ongkos_ranking'		=> number_format((float)$ongkosRanking, 2, '.', ''),
	                		'kualitas_ranking'		=> number_format((float)$kualitasRanking, 2, '.', ''),
	                		'stok_ranking'		    => number_format((float)$stokRanking, 2, '.', '')
	                	];
	                }
	                // total ranking
	                if (!empty($dataRanking)) {
		                for ( $i = 0; $i < $totalData; $i++ ) {
		            		$totalRanking		= $dataRanking[$i]['harga_ranking'] + $dataRanking[$i]['ongkos_ranking'] + $dataRanking[$i]['kualitas_ranking'] + $dataRanking[$i]['stok_ranking'];
		                	$dataTotalRanking[$i]			= [
		                		'nama_supplier'			=> $sawData[$i]->nama_supplier,
		                		'nama_bahan_baku'		=> $sawData[$i]->nama_bahan_baku,
                                'harga_ranking'         => $dataRanking[$i]['harga_ranking'],
                                'ongkos_ranking'        => $dataRanking[$i]['ongkos_ranking'],
                                'kualitas_ranking'      => $dataRanking[$i]['kualitas_ranking'],
                                'stok_ranking'          => $dataRanking[$i]['stok_ranking'],
		                		'total_ranking'		    => number_format((float)$totalRanking, 2, '.', '')
		                	];
		                }
		                // hasil ranking
		                if (!empty($dataTotalRanking)) {
		                	$dataHasilRanking	= [];
		                	$rankingPilihan		= [];
                            for ( $i = 0; $i < $totalData; $i++ ) {
                                $hasilRanking = $dataTotalRanking[$i]['total_ranking'];
                                array_push($dataHasilRanking, $hasilRanking);
                            }
                            $hasil = max($dataHasilRanking);
                            for ( $i = 0; $i < $totalData; $i++ ) {
                            	if ($dataTotalRanking[$i]['total_ranking'] === $hasil) {
                            		array_push($rankingPilihan, [
				                		'nama_supplier'			=> $sawData[$i]->nama_supplier,
				                		'nama_bahan_baku'		=> $sawData[$i]->nama_bahan_baku,
				                		'hasil_ranking'		=> $dataTotalRanking[$i]['total_ranking']
                            		]);
                            		$saw = new SAW();
                            		$saw->id_supplier		= $sawData[$i]->id_supplier;
                            		$saw->id_bahan_baku		= $sawData[$i]->id_bahan_baku;
                            		$saw->harga_saw			= $sawData[$i]->harga_penawaran;
                            		$saw->kualitas_saw		= $sawData[$i]->kualitas_penawaran;
                            		$saw->stok_saw			= $sawData[$i]->stok_penawaran;
                            		$saw->ongkos_saw		= $sawData[$i]->ongkos_penawaran;
                            		$saw->nilai_prefensi	= $hasil;
                            		$saw->tanggal_penawaran	= $sawData[$i]->tanggal_penawaran;
                            		$saw->tanggal_saw		= date("Y-m-d");
                            		$saw->save();
                            	}
                            }
				            return response()->json([
				                'error'     => '',
				                'data'      => ([
				                    'dataSAW'        => ([
				                        'dataPenawaran' => $sawData,
				                        'dataKriteria'  => $kriteria
				                    ]),
				                    'dataNormalisasi'   => $dataNormalisasi,
				                    'dataRanking'		=> $dataRanking,
				                    'totalRanking'		=> $dataTotalRanking,
				                    'hasilRanking'		=> $hasil,
				                    'rankingPilihan'	=> $rankingPilihan,
                                    'jumlahData'        => $totalData
				                ]),
				                'message'   => ''
				            ], 200);
		                }
	                }
	        	}
			} else {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'jumlahData'        => $totalData
                    ]),
                    'message'   => $bahanbaku
                ], 200);
            }
    	} catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
