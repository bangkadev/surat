<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluarInternal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratKeluarInternalController extends Controller
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
        $lastSurat = SuratKeluarInternal::orderBy('id', 'desc')->first();
        $newNumber = $lastSurat ? intval(substr($lastSurat->nomor_surat, 0, 3)) + 1 : 1;

        $bulan = Carbon::now()->month;
        $romawi = $this->angkaKeRomawi($bulan);

        return sprintf("%03d/%s/YPIA/KBIHU-A/%d.%d", $newNumber, $romawi, $tahun_hijriah, $tahun_masehi);
    }

    private function generateKodeArsip()
    {
        $lastKode = SuratKeluarInternal::orderBy('id', 'desc')->first();
        $newNumber = $lastKode ? intval(substr($lastKode->kode_arsip, 4)) + 1 : 1;

        return sprintf("SKI-%d", $newNumber);
    }

    public function index()
    {
        $suratKeluarInternal = SuratKeluarInternal::orderBy('created_at', 'asc')->get();
        $tahun_hijriah = 1446; // Nilai default, bisa diubah di view
        $tahun_masehi = Carbon::now()->year;
        $nomorSuratOtomatis = $this->generateNomorSurat($tahun_hijriah, $tahun_masehi);
        $kodeArsipOtomatis = $this->generateKodeArsip();

        return view('ski', compact('suratKeluarInternal', 'nomorSuratOtomatis', 'kodeArsipOtomatis', 'tahun_hijriah', 'tahun_masehi'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_surat' => 'required|unique:ski,nomor_surat',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'kode_arsip' => 'required|unique:ski,kode_arsip'
        ]);

        $suratKeluarInternal = new SuratKeluarInternal($validatedData);

        if ($request->hasFile('lampiran')) {
            $suratKeluarInternal->lampiran = $this->uploadLampiran($request->file('lampiran'), $request->nomor_surat);
        }

        $suratKeluarInternal->save();

        return redirect()->route('ski')->with('success', 'Surat Masuk Eksternal berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $suratKeluarInternal = SuratKeluarInternal::findOrFail($id);

        $validatedData = $request->validate([
            'nomor_surat' => 'required|unique:ski,nomor_surat,' . $id,
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'kode_arsip' => 'required|unique:ski,kode_arsip,' . $id
        ]);

        $suratKeluarInternal->fill($validatedData);

        if ($request->hasFile('lampiran')) {
            $this->deleteLampiran($suratKeluarInternal->lampiran);
            $suratKeluarInternal->lampiran = $this->uploadLampiran($request->file('lampiran'), $request->nomor_surat);
        } elseif ($request->has('hapus_lampiran')) {
            $this->deleteLampiran($suratKeluarInternal->lampiran);
            $suratKeluarInternal->lampiran = null;
        }

        $suratKeluarInternal->save();

        return redirect()->route('ski')->with('success', 'Surat Masuk Eksternal berhasil diperbarui');
    }

    public function destroy($id)
    {
        $suratKeluarInternal = SuratKeluarInternal::findOrFail($id);
        $this->deleteLampiran($suratKeluarInternal->lampiran);
        $suratKeluarInternal->delete();

        return redirect()->route('ski')->with('success', 'Surat Masuk Eksternal berhasil dihapus');
    }

    private function uploadLampiran($file, $nomorSurat)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::slug($nomorSurat) . '_' . time() . '.' . $extension;
        $path = $file->storeAs('ski', $fileName, 'public');
        return $path;
    }

    private function deleteLampiran($path)
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
