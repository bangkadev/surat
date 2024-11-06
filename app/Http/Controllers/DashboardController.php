<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluarEksternal;
use App\Models\SuratKeluarInternal;
use App\Models\SuratMasukEksternal;
use App\Models\SuratMasukInternal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = [
            'smi' => SuratMasukInternal::count(),
            'ski' => SuratKeluarInternal::count(),
            'sme' => SuratMasukEksternal::count(),
            'ske' => SuratKeluarEksternal::count()
        ];

        return view('dashboard', $data);
    }
}
