<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasukEksternal extends Model
{
    use HasFactory;

    protected $table = 'sme';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'lampiran',
        'kode_arsip'
    ];
}
