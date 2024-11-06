<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasukInternal extends Model
{
    use HasFactory;

    protected $table = 'smi';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'lampiran',
        'kode_arsip'
    ];
}
