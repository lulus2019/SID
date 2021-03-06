<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';
    protected $primaryKey ='surat_id';
    protected $timestam = true;
    Protected $fillable = [
                "nama_surat",
                "tanggal",
                "hal",
                "surat_filename",
                "penduduk_id",
                "staff_id",
                "created_at",
                "updated_at"
    ];

    public $format_surat = array(
            array(
                "kode" => "S01",
                "title" => "Surat Keterangan Kelahiran",
                "template" => "surat_ket_kelahiran.rtf"
            ),
            array(
                "kode" => "S02",
                "title" => "Surat Keterangan Kematian",
                "template" => "surat_ket_kematian.rtf"
            ),
            array(
                "kode" => "S03",
                "title" => "Surat Keterangan Kurang Mampu",
                "template" => "surat_ket_kurang_mampu.rtf"
            ),
            array(
                "kode" => "S04",
                "title" => "Surat Pengantar ",
                "template" => "surat_ket_pengantar.rtf"
            ),
            array(
                "kode" => "S05",
                "title" => "Surat Keterangan Pindah Penduduk",
                "template" => "surat_ket_pindah_penduduk.rtf"
            )
    );

    public function getNameFile($kode)
    {
        foreach ($this->format_surat as $key => $val) {
            if ($val['kode'] === $kode) {
                return $val['template'];
            }
        }
        return null;
    }
}
