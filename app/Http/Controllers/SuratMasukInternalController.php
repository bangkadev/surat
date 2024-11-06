<?php

namespace App\Http\Controllers;

use App\Models\SuratMasukInternal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratMasukInternalController extends Controller
{
    private function angkaKeRomawi($angka)
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return $romawi[$angka] ?? '';
    }

    private function generateNomorSurat($tahun_hijriah, $tahun_masehi)
    {
        $lastSurat = SuratMasukInternal::orderBy('id', 'desc')->first();
        $newNumber = $lastSurat ? intval(substr($lastSurat->nomor_surat, 0, 3)) + 1 : 1;

        $bulan = Carbon::now()->month;
        $romawi = $this->angkaKeRomawi($bulan);

        return sprintf("%03d/%s/YPIA/KBIHU-A/%d.%d", $newNumber, $romawi, $tahun_hijriah, $tahun_masehi);
    }

    private function generateKodeArsip()
    {
        $lastKode = SuratMasukInternal::orderBy('id', 'desc')->first();
        $newNumber = $lastKode ? intval(substr($lastKode->kode_arsip, 4)) + 1 : 1;

        return sprintf("SMI-%d", $newNumber);
    }

    public function index()
    {
        $suratMasukInternal = SuratMasukInternal::orderBy('created_at', 'asc')->get();
        $tahun_hijriah = 1446; // Nilai default, bisa diubah di view
        $tahun_masehi = Carbon::now()->year;
        $nomorSuratOtomatis = $this->generateNomorSurat($tahun_hijriah, $tahun_masehi);
        $kodeArsipOtomatis = $this->generateKodeArsip();

        return view('smi', compact('suratMasukInternal', 'nomorSuratOtomatis', 'kodeArsipOtomatis', 'tahun_hijriah', 'tahun_masehi'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_surat' => 'required|unique:smi,nomor_surat',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'kode_arsip' => 'required|unique:smi,kode_arsip'
        ]);

        $suratMasukInternal = new SuratMasukInternal($validatedData);

        if ($request->hasFile('lampiran')) {
            $suratMasukInternal->lampiran = $this->uploadLampiran($request->file('lampiran'), $request->nomor_surat);
        }

        $suratMasukInternal->save();

        return redirect()->route('smi')->with('success', 'Surat Masuk Internal berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $suratMasukInternal = SuratMasukInternal::findOrFail($id);

        $validatedData = $request->validate([
            'nomor_surat' => 'required|unique:smi,nomor_surat,' . $id,
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'kode_arsip' => 'required|unique:smi,kode_arsip,' . $id
        ]);

        $suratMasukInternal->fill($validatedData);

        if ($request->hasFile('lampiran')) {
            $this->deleteLampiran($suratMasukInternal->lampiran);
            $suratMasukInternal->lampiran = $this->uploadLampiran($request->file('lampiran'), $request->nomor_surat);
        } elseif ($request->has('hapus_lampiran')) {
            $this->deleteLampiran($suratMasukInternal->lampiran);
            $suratMasukInternal->lampiran = null;
        }

        $suratMasukInternal->save();

        return redirect()->route('smi')->with('success', 'Surat Masuk Internal berhasil diperbarui');
    }

    public function destroy($id)
    {
        $suratMasukInternal = SuratMasukInternal::findOrFail($id);
        $this->deleteLampiran($suratMasukInternal->lampiran);
        $suratMasukInternal->delete();

        return redirect()->route('smi')->with('success', 'Surat Masuk Internal berhasil dihapus');
    }

    private function uploadLampiran($file, $nomorSurat)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::slug($nomorSurat) . '_' . time() . '.' . $extension;
        $path = $file->storeAs('smi', $fileName, 'public');
        return $path;
    }

    private function deleteLampiran($path)
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
