<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wilayah;
use Illuminate\Support\Facades\DB;
use App\Penduduk;
use DateTime;

class PendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penduduk = Penduduk::where('status_kependudukan','!=',"Meninggal")->where('status_kependudukan','!=',"Pindah")->orWhereNull('status_kependudukan')->get();
        return view('pages.kependudukan.penduduk.index',['penduduk' => $penduduk]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dusun =  Wilayah::where('wilayah_part',1)->get();
        return view('pages.kependudukan.penduduk.form',['dusun'=> $dusun]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Penduduk::create([ 
        "nik" => $request->nik,
        "full_name" => $request->full_name,
        "no_kk" => $request->no_kk,
        "tempat_lahir" => $request->tempat_lahir,
        "tanggal_lahir" => $request->tanggal_lahir,
        "jekel" => $request->jekel,
        "agama" => $request->agama,
        "pendidikan" => $request->pendidikan,
        "pekerjaan" => $request->pekerjaan,
        "status_perkawinan" => $request->status_perkawinan,
        "golongan_darah" => $request->golongan_darah,
        "status_kependudukan" => $request->status_kependudukan,
        "keluarga_id" => null,
        "hubungan_keluarga" => null,
        "wilayah_dusun" => $request->wilayah_dusun,
        "wilayah_rw" => $request->wilayah_rw,
        "wilayah_rt" => $request->wilayah_rt,
        "created_at" => Date("Y-m-d h:i:s"),
        "updated_at" => Date("Y-m-d h:i:s")
        ]);

        return redirect()->action('PendudukController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $penduduk =  Penduduk::join('wilayah as dusun', 'dusun.wilayah_id', '=', 'penduduk.wilayah_dusun')
        ->join('wilayah as  rw', 'rw.wilayah_id', '=', 'penduduk.wilayah_rw')
        ->join('wilayah as  rt', 'rt.wilayah_id', '=', 'penduduk.wilayah_rt')
        ->select('penduduk.*', 'dusun.wilayah_nama as DUSUN','rw.wilayah_nama as RW','rt.wilayah_nama as RT')
        ->where('penduduk.penduduk_id',$id)->first();
        return view('pages.kependudukan.penduduk.view',['penduduk' => $penduduk ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
        {
            $penduduk = Penduduk::find($id);
            $dusun = Wilayah::where('wilayah_part',1)->get();
            $rw =  Wilayah::where('wilayah_part',2)->where('wilayah_dusun',$penduduk->wilayah_dusun)->get();
            $rt = Wilayah::where('wilayah_part',3)->where('wilayah_rw',$penduduk->wilayah_rw)->get();

            return view('pages.kependudukan.penduduk.edit',['penduduk' => $penduduk, 'dusun' => $dusun,'rw' => $rw,'rt' => $rt]);
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
        $penduduk = Penduduk::find($id);
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
        return redirect()->action('PendudukController@index');
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
        DB::transaction(function () use ($penduduk,$id) {
            $penduduk->deletePendudukWithRelasion($id);
        });
        return redirect()->back();
    }
    public function get_wilayah($id,$part)
    {
        $dusun = array();
        if($part =="rw")
        {
            $dusun = Wilayah::where('wilayah_dusun',$id)
            ->where('wilayah_part',2)->get();
        }

        if($part =="rt")
        {
            $dusun = Wilayah::where('wilayah_rw',$id)
            ->where('wilayah_part',3)->get();
        }
         
       return $dusun;
    }
}
