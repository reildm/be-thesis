<?php

namespace App\Http\Controllers;
use App\Topsis;
use App\Produksi;
use App\BahanBaku;
use App\Kriteria;
use Maatwebsite\Excel\Facades\Excel;
use DB;

use Illuminate\Http\Request;

class TopsisController extends Controller
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
            $pagination = ceil ( count(Topsis::all()) / 10 );
            $topsis = DB::table('riwayat_topsis')
                ->join('bahan_baku', 'riwayat_topsis.id_bahan_baku', '=', 'bahan_baku.id_bahan_baku')
                ->select('riwayat_topsis.id_topsis', 'bahan_baku.nama_bahan_baku', 'riwayat_topsis.harga_topsis', 'riwayat_topsis.stok_topsis', 'riwayat_topsis.kebutuhan_topsis', 'riwayat_topsis.ketersediaan_topsis', 'riwayat_topsis.nilai_prefensi', 'riwayat_topsis.tanggal_topsis')
                ->orderBy('riwayat_topsis.tanggal_topsis','DESC')
                ->offset($offset)
                ->limit(10)
                ->get();
            if(!empty($topsis)) {
                return response()->json([
                    'error'     => '',
                    'data'      => ([
                        'data'      => $topsis,
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
        	// get data
        	$topsisData = DB::table('bahan_baku')
        		->join('produksi', 'bahan_baku.id_bahan_baku','=','produksi.id_bahan_baku')
        		->select('bahan_baku.id_bahan_baku','bahan_baku.nama_bahan_baku','produksi.kepentingan_bahan_baku','bahan_baku.ketersediaan','bahan_baku.harga_terakhir as harga_terakhir','bahan_baku.stok_bahan_baku as stok_bahan_baku')
	        	->selectRaw('bahan_baku.harga_terakhir * bahan_baku.minimal_pembelian as kriteria_harga')
	        	->selectRaw('(bahan_baku.stok_bahan_baku-produksi.standart_produksi)/produksi.standart_produksi as presentase_stok')
	        	// ->selectRaw('sqrt(16) as test')
        		->where('tipe',2)
        		->get();
            $kriteria = DB::table('kriteria')->where('metode_kriteria', 1)->get();
			$totalData  = $topsisData->count();
			// normalisasi
    		if(!empty($topsisData)) {
				$hargaPangkat = 0;
				$stokPangkat = 0;
				$kepentinganPangkat = 0;
				$ketersediaanPangkat = 0;
				for ( $i = 0; $i < $totalData; $i++ ) {
					$hargaPangkat = $hargaPangkat + pow($topsisData[$i]->kriteria_harga, 2);
					$stokPangkat = $stokPangkat + pow($topsisData[$i]->presentase_stok, 2);
					$kepentinganPangkat = $kepentinganPangkat + pow($topsisData[$i]->kepentingan_bahan_baku, 2);
					$ketersediaanPangkat = $ketersediaanPangkat + pow($topsisData[$i]->ketersediaan, 2);
				}
    			for ( $i = 0; $i < $totalData; $i++ ) {
    				$namaNormalisasi 			= $topsisData[$i]->nama_bahan_baku;
    				$hargaNormalisasi 			= $topsisData[$i]->kriteria_harga / (sqrt($hargaPangkat));
    				$stokNormalisasi 			= $topsisData[$i]->presentase_stok / (sqrt($stokPangkat));
    				$kepentinganNormalisasi 	= $topsisData[$i]->kepentingan_bahan_baku / (sqrt($kepentinganPangkat));
    				$ketersediaanNormalisasi 	= $topsisData[$i]->ketersediaan / (sqrt($ketersediaanPangkat));
    				$dataNormalisasi[$i] = [
    					'nama_bahan_baku'			=> $namaNormalisasi,
    					'harga_normalisasi'			=> number_format((float)$hargaNormalisasi, 2, '.', ''),
    					'stok_normalisasi'			=> number_format((float)$stokNormalisasi, 2, '.', ''),
    					'kepentingan_normalisasi'	=> number_format((float)$kepentinganNormalisasi, 2, '.', ''),
    					'ketersediaan_normalisasi'	=> number_format((float)$ketersediaanNormalisasi, 2, '.', '')
    				];
    			}
    			// normalisasi terbobot
    			if(!empty($dataNormalisasi)) {
					for ( $i = 0; $i < $totalData; $i++ ) {
    					$namaNormalisasi 		= $topsisData[$i]->nama_bahan_baku;
						$hargaTerbobot 			= $dataNormalisasi[$i]['harga_normalisasi'] * $kriteria[0]->bobot_kriteria;
						$stokTerbobot 			= $dataNormalisasi[$i]['stok_normalisasi'] * $kriteria[1]->bobot_kriteria;
						$kepentinganTerbobot 	= $dataNormalisasi[$i]['kepentingan_normalisasi'] * $kriteria[2]->bobot_kriteria;
						$ketersediaanTerbobot 	= $dataNormalisasi[$i]['ketersediaan_normalisasi'] * $kriteria[3]->bobot_kriteria;
	    				$dataTerbobot[$i]  = [
	    					'nama_bahan_baku'             => $namaNormalisasi,
	    					'harga_terbobot'              => number_format((float)$hargaTerbobot, 2, '.', ''),
	    					'stok_terbobot'               => number_format((float)$stokTerbobot, 2, '.', ''),
	    					'kepentingan_terbobot'        => number_format((float)$kepentinganTerbobot, 2, '.', ''),
	    					'ketersediaan_terbobot'       => number_format((float)$ketersediaanTerbobot, 2, '.', '')
	    				];
					}
					// solusi ideal
    				if(!empty($dataTerbobot)) {
                        $dataHargaIdeal         = [];
                        $dataStokIdeal          = [];
                        $dataKepentinganIdeal   = [];
                        $dataKetersediaanIdeal  = [];
                        $solusiIdealPositif     = [];
                        $solusiIdealNegatif     = [];
						for ( $i = 0; $i < $totalData; $i++ ) {
							$hargaIdeal         = $dataTerbobot[$i]['harga_terbobot'];
                            $stokIdeal          = $dataTerbobot[$i]['stok_terbobot'];
                            $kepentinganIdeal   = $dataTerbobot[$i]['kepentingan_terbobot'];
                            $ketersediaanIdeal  = $dataTerbobot[$i]['ketersediaan_terbobot'];
                            array_push($dataHargaIdeal, $hargaIdeal);
                            array_push($dataStokIdeal, $stokIdeal);
                            array_push($dataKepentinganIdeal, $kepentinganIdeal);
                            array_push($dataKetersediaanIdeal, $ketersediaanIdeal);
						}
                        $solusiIdealPositif[0] = [
                            'harga_positif'         => min($dataHargaIdeal),
                            'stok_positif'          => min($dataStokIdeal),
                            'kepentingan_positif'   => max($dataKepentinganIdeal),
                            'ketersediaan_positif'  => max($dataKetersediaanIdeal),
                        ];
                        $solusiIdealNegatif[0] = [
                            'harga_negatif'         => max($dataHargaIdeal),
                            'stok_negatif'          => max($dataStokIdeal),
                            'kepentingan_negatif'   => min($dataKepentinganIdeal),
                            'ketersediaan_negatif'  => min($dataKetersediaanIdeal),
                        ];
                        // jarak
                        if(!empty($solusiIdealPositif[0]) && !empty($solusiIdealNegatif[0])) {
                            for ( $i = 0; $i < $totalData; $i++ ) {
                                $namaJarak          = $topsisData[$i]->nama_bahan_baku;
                                $jarakPositifHarga          = pow($dataTerbobot[$i]['harga_terbobot'] -  $solusiIdealPositif[0]['harga_positif'], 2);
                                $jarakPositifStok           = pow($dataTerbobot[$i]['stok_terbobot'] -  $solusiIdealPositif[0]['stok_positif'], 2);
                                $jarakPositifKepentingan    = pow($dataTerbobot[$i]['kepentingan_terbobot'] -  $solusiIdealPositif[0]['kepentingan_positif'], 2);
                                $jarakPositifKetersediaan   = pow($dataTerbobot[$i]['ketersediaan_terbobot'] -  $solusiIdealPositif[0]['ketersediaan_positif'], 2);
                                $jarakPositif               = sqrt(($jarakPositifHarga + $jarakPositifStok + $jarakPositifKepentingan + $jarakPositifKetersediaan));
                                $jarakNegatifHarga          = pow($dataTerbobot[$i]['harga_terbobot'] -  $solusiIdealNegatif[0]['harga_negatif'], 2);
                                $jarakNegatifStok           = pow($dataTerbobot[$i]['stok_terbobot'] -  $solusiIdealNegatif[0]['stok_negatif'], 2);
                                $jarakNegatifKepentingan    = pow($dataTerbobot[$i]['kepentingan_terbobot'] -  $solusiIdealNegatif[0]['kepentingan_negatif'], 2);
                                $jarakNegatifKetersediaan   = pow($dataTerbobot[$i]['ketersediaan_terbobot'] -  $solusiIdealNegatif[0]['ketersediaan_negatif'], 2);
                                $jarakNegatif               = sqrt(($jarakNegatifHarga + $jarakNegatifStok + $jarakNegatifKepentingan + $jarakNegatifKetersediaan));
                                $nilaiPrefensi             = $jarakNegatif / ($jarakPositif + $jarakNegatif);
                                $dataJarak[$i]      = [
                                    'nama_bahan_baku'   => $namaJarak,
                                    'jarak_positif'     => number_format((float)$jarakPositif, 2, '.', ''),
                                    'jarak_negatif'     => number_format((float)$jarakNegatif, 2, '.', ''),
                                    'nilai_prefensi'    => number_format((float)$nilaiPrefensi, 2, '.', '')
                                ];
                            }
                            if(!empty($dataJarak)) {
                                $dataPrefensi   = [];
                                $alternatifPilihan = [];
                                for ( $i = 0; $i < $totalData; $i++ ) {
                                    $prefensi = $dataJarak[$i]['nilai_prefensi'];
                                    array_push($dataPrefensi, $prefensi);
                                }
                                $prefensi = max($dataPrefensi);
                                for ( $i = 0; $i < $totalData; $i++ ) {
                                    if ($dataJarak[$i]['nilai_prefensi'] === $prefensi) {
                                        array_push($alternatifPilihan, [
                                            'id_bahan_baku'     => $topsisData[$i]->id_bahan_baku,
                                            'nama_bahan_baku'   => $topsisData[$i]->nama_bahan_baku,
                                            'prefensi'          => $dataJarak[$i]['nilai_prefensi']
                                        ]);
                                        $topsis = new Topsis();
                                        $topsis->id_bahan_baku          = $topsisData[$i]->id_bahan_baku;
                                        $topsis->harga_topsis           = $topsisData[$i]->harga_terakhir;
                                        $topsis->stok_topsis            = $topsisData[$i]->stok_bahan_baku;
                                        $topsis->kebutuhan_topsis       = $topsisData[$i]->kepentingan_bahan_baku;
                                        $topsis->ketersediaan_topsis    = $topsisData[$i]->ketersediaan;
                                        $topsis->nilai_prefensi         = $prefensi;
                                        $topsis->tanggal_topsis         = date("Y-m-d");
                                        $topsis->save();
                                    }
                                }
                                if (!empty($alternatifPilihan)) {
                                    // return Excel::download(BahanBaku::all(), 'test.xlsx');
                                    return response()->json([
                                        'error'     => '',
                                        'data'      => ([
                                            'dataTopsis'        => ([
                                                'dataBarang'    => $topsisData,
                                                'dataKriteria'  => $kriteria
                                            ]),
                                            'dataNormalisasi'   => $dataNormalisasi,
                                            'dataTerbobot'      => $dataTerbobot,
                                            'dataIdeal'         => ([
                                                'idealPositif'  => $solusiIdealPositif,
                                                'idealNegatif'  => $solusiIdealNegatif
                                            ]),
                                            'jarak'             => $dataJarak,
                                            'alternatifPilihan' => $alternatifPilihan
                                        ]),
                                        'message'   => ''
                                    ], 200);
                                }
                            }
                        }
    				}
    			}
    		}
        } catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
