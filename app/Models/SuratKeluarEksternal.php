<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluarEksternal extends Model
{
    use HasFactory;

    protected $table = 'ske';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'lampiran',
        'kode_arsip'
    ];
}
