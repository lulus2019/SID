<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pendatang;
use App\Penduduk;
use App\Wilayah;
use DateTime;

class PendatangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pendatang = Pendatang::join('penduduk', 'penduduk.penduduk_id', '=', 'pendatang.penduduk_id')
        ->select('pendatang.*','penduduk.nik','penduduk.full_name')
        ->get();
        return view('pages.kependudukan.pendatang.index',['pendatang' => $pendatang]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dusun =  Wilayah::where('wilayah_part',1)->get();
        return view('pages.kependudukan.pendatang.form',['dusun'=> $dusun]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $penduduk = new Penduduk;
        $pendatang = new Pendatang;
        DB::transaction(function () use ($penduduk,$pendatang,$request) {

           // Simpan data pendudduk
    
            $penduduk->nik = $request->nik;
            $penduduk->full_name = $request->full_name;
            $penduduk->no_kk = $request->no_kk;
            $penduduk->tempat_lahir = $request->tempat_lahir;
            $penduduk->tanggal_lahir = $request->tanggal_lahir;
            $penduduk->jekel = $request->jekel;
            $penduduk->agama = $request->agama;
            $penduduk->pendidikan = $request->pendidikan;
            $penduduk->pekerjaan = $request->pekerjaan;
            $penduduk->status_perkawinan = $request->status_perkawinan;
            $penduduk->golongan_darah = $request->golongan_darah;
            $penduduk->status_kependudukan = $request->status_kependudukan;
            $penduduk->keluarga_id = null;
            $penduduk->hubungan_keluarga = null;
            $penduduk->wilayah_dusun = $request->wilayah_dusun;
            $penduduk->wilayah_rw = $request->wilayah_rw;
            $penduduk->wilayah_rt = $request->wilayah_rt;
            $penduduk->created_at = Date("Y-m-d h:i:s");
            $penduduk->updated_at = Date("Y-m-d h:i:s");
            $penduduk->save();

            // Simpan Data Pendududk datang

            $pendatang->tgl_datang    = $request->tgl_datang;
            $pendatang->alamat_datang = $request->alamat_datang;
            $pendatang->alasan_datang = $request->alasan_datang;
            $pendatang->penduduk_id	  = $penduduk->penduduk_id;
            $pendatang->created_at	  = Date("Y-m-d h:i:s");
            $pendatang->updated_at    = Date("Y-m-d h:i:s");

            $pendatang->save();
        });

         return redirect()->action('PendatangController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pendatang =  Pendatang::join('penduduk','penduduk.penduduk_id','=','pendatang.penduduk_id')
        ->join('wilayah as dusun', 'dusun.wilayah_id', '=', 'penduduk.wilayah_dusun')
        ->join('wilayah as  rw', 'rw.wilayah_id', '=', 'penduduk.wilayah_rw')
        ->join('wilayah as  rt', 'rt.wilayah_id', '=', 'penduduk.wilayah_rt')
        ->select('penduduk.*','pendatang.*', 'dusun.wilayah_nama as DUSUN','rw.wilayah_nama as RW','rt.wilayah_nama as RT')
        ->where('pendatang.pendatang_id',$id)->first();
        return view('pages.kependudukan.pendatang.view',['pendatang' => $pendatang ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
        {
        $pendatang = Pendatang::join('penduduk', 'penduduk.penduduk_id', '=', 'pendatang.penduduk_id')
        ->where('pendatang_id',$id)
        ->select('pendatang.*','penduduk.*')
        ->first();
        $dusun = Wilayah::where('wilayah_part',1)->get();
        $rw =  Wilayah::where('wilayah_part',2)->where('wilayah_dusun',$pendatang->wilayah_dusun)->get();
        $rt = Wilayah::where('wilayah_part',3)->where('wilayah_rw',$pendatang->wilayah_rw)->get();

        return view('pages.kependudukan.pendatang.edit',['pendatang'=> $pendatang, 'dusun' => $dusun,'rw' => $rw,'rt' => $rt]);
        }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pendatang = Pendatang::find($id);
        $penduduk = Penduduk::find($pendatang->penduduk_id);
        
        DB::transaction(function () use ($penduduk,$pendatang,$request) {

            $penduduk->nik  = $request->nik;
            $penduduk->wilayah_dusun   = $request->wilayah_dusun;
            $penduduk->wilayah_rw   = $request->wilayah_rw;
            $penduduk->wilayah_rt   = $request->wilayah_rt;
            $penduduk->full_name   = $request->full_name;
            $penduduk->tempat_lahir   = $request->tempat_lahir;
            $penduduk->tanggal_lahir   = $request->tanggal_lahir;
            $penduduk->jekel   = $request->jekel;
            $penduduk->agama   = $request->agama;
            $penduduk->pendidikan   = $request->pendidikan;
            $penduduk->pekerjaan   = $request->pekerjaan;
            $penduduk->status_perkawinan   = $request->status_perkawinan;
            $penduduk->golongan_darah   = $request->golongan_darah;
            $penduduk->status_kependudukan   = $request->status_kependudukan;
            $penduduk->updated_at    = Date("Y-m-d h:i:s");
            $penduduk->save();

            $pendatang->tgl_datang    = $request->tgl_datang;
            $pendatang->alamat_datang = $request->alamat_datang;
            $pendatang->alasan_datang = $request->alasan_datang;
            $pendatang->updated_at    = Date("Y-m-d h:i:s");

            $pendatang->save();


        });
         return redirect()->action('PendatangController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penduduk = new Penduduk;
        $pendatang = Pendatang::find($id);
        DB::transaction(function () use ($penduduk,$pendatang) {
            $penduduk->deletePendudukWithRelasion($pendatang->penduduk_id);
        });
        return redirect()->back();
    }
}
