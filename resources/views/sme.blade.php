@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none"><i
                                class="ti ti-home me-1"></i>Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Surat Masuk Eksternal</li>
                </ol>
            </nav>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold mb-0"><i class="ti ti-mail me-2"></i>Surat Masuk Eksternal</h5>
                        <div class="d-flex">
                            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#tambahSuratModal">
                                <i class="ti ti-plus me-1"></i> Tambah Surat
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-file-export me-1"></i> Export
                                </button>
                                <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#" id="exportExcel"><i
                                                class="ti ti-file-spreadsheet me-2"></i> Export Excel</a></li>
                                    <li><a class="dropdown-item" href="#" id="exportPDF"><i
                                                class="ti ti-file-text me-2"></i> Export PDF</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="smeTable" class="table table-striped table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Surat</th>
                                    <th>Tanggal</th>
                                    <th>Tujuan</th>
                                    <th>Perihal</th>
                                    <th>Kode</th>
                                    <th>Lampiran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suratMasukEksternal as $surat)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="btn btn-sm btn-info me-1">{{ $surat->nomor_surat }}</span></td>
                                        <td>{{ date('d F Y', strtotime($surat->tanggal_surat)) }}</td>
                                        <td>{{ $surat->tujuan }}</td>
                                        <td>{{ $surat->perihal }}</td>
                                        <td><span class="btn btn-sm btn-info me-1">{{ $surat->kode_arsip }}</span></td>
                                        <td>
                                            @if ($surat->lampiran)
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" target="_blank"
                                                    class="btn btn-sm btn-info me-1">
                                                    <i class="ti ti-paperclip me-1"></i>Lihat
                                                </a>
                                            @else
                                                <span class="text-muted"><i class="ti ti-file-off me-1"></i>Tidak ada
                                                    lampiran</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal"
                                                    data-bs-target="#editSuratModal{{ $surat->id }}">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#hapusSuratModal{{ $surat->id }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>

                                                <!-- Modal Hapus Surat -->
                                                <div class="modal fade" id="hapusSuratModal{{ $surat->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="hapusSuratModalLabel{{ $surat->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title text-white"
                                                                    id="hapusSuratModalLabel{{ $surat->id }}">Konfirmasi
                                                                    Hapus Surat</h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus surat ini?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal"><i
                                                                        class="ti ti-x me-1"></i>Batal</button>
                                                                <form action="{{ route('sme.destroy', $surat->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger"><i
                                                                            class="ti ti-trash me-1"></i>Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Surat -->
    <div class="modal fade" id="tambahSuratModal" tabindex="-1" aria-labelledby="tambahSuratModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="tambahSuratModalLabel"><i class="ti ti-plus me-2"></i>Tambah
                        Surat Masuk Eksternal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('sme.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nomor_surat" class="form-label">Nomor Surat</label>
                                <input type="text" class="form-control" id="nomor_surat" name="nomor_surat"
                                    value="{{ $nomorSuratOtomatis }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                                <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="tujuan" class="form-label">Tujuan</label>
                                <input type="text" class="form-control" id="tujuan" name="tujuan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="perihal" class="form-label">Perihal</label>
                                <input type="text" class="form-control" id="perihal" name="perihal" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lampiran" class="form-label">Lampiran</label>
                                <input type="file" class="form-control" id="lampiran" name="lampiran"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, JPEG, PNG. Maks
                                    10MB.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="kode_arsip" class="form-label">Kode Arsip</label>
                                <input type="text" class="form-control" id="kode_arsip" name="kode_arsip"
                                    value="{{ $kodeArsipOtomatis }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="ti ti-x me-1"></i>Tutup</button>
                        <button type="submit" class="btn btn-primary"><i
                                class="ti ti-device-floppy me-1"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Surat -->
    @foreach ($suratMasukEksternal as $surat)
        <div class="modal fade" id="editSuratModal{{ $surat->id }}" tabindex="-1"
            aria-labelledby="editSuratModalLabel{{ $surat->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title text-white" id="editSuratModalLabel{{ $surat->id }}"><i
                                class="ti ti-edit me-2"></i>Edit Surat Masuk Eksternal</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('sme.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nomor_surat" class="form-label">Nomor Surat</label>
                                    <input type="text" class="form-control" id="nomor_surat" name="nomor_surat"
                                        value="{{ $surat->nomor_surat }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                                    <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat"
                                        value="{{ $surat->tanggal_surat }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tujuan" class="form-label">Tujuan</label>
                                    <input type="text" class="form-control" id="tujuan" name="tujuan"
                                        value="{{ $surat->tujuan }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="perihal" class="form-label">Perihal</label>
                                    <input type="text" class="form-control" id="perihal" name="perihal"
                                        value="{{ $surat->perihal }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lampiran" class="form-label">Lampiran</label>
                                    <input type="file" class="form-control" id="lampiran" name="lampiran"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, JPEG, PNG. Maks
                                        10MB.</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="kode_arsip" class="form-label">Kode Arsip</label>
                                    <input type="text" class="form-control" id="kode_arsip" name="kode_arsip"
                                        value="{{ $surat->kode_arsip }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                    class="ti ti-x me-1"></i>Tutup</button>
                            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#smeTable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ ",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ ",
                    paginate: {
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                },
                responsive: true,
                dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>'
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Ekspor ke Excel
            $('#exportExcel').on('click', function() {
                var data = [
                    ['No', 'Nomor Surat', 'Tanggal Surat', 'Tujuan', 'Perihal', 'Kode Arsip']
                ];
                $('#smeTable tbody tr').each(function() {
                    data.push([
                        $(this).find('td:eq(0)').text().trim(),
                        $(this).find('td:eq(1)').text().trim(),
                        $(this).find('td:eq(2)').text().trim(),
                        $(this).find('td:eq(3)').text().trim(),
                        $(this).find('td:eq(4)').text().trim(),
                        $(this).find('td:eq(5)').text().trim()
                    ]);
                });
                var ws = XLSX.utils.aoa_to_sheet(data);
                var wb = XLSX.utils.book_new();

                // Mengatur lebar kolom
                var wscols = [{
                        wch: 5
                    }, // No
                    {
                        wch: 30
                    }, // Nomor Surat
                    {
                        wch: 15
                    }, // Tanggal Surat
                    {
                        wch: 30
                    }, // Tujuan
                    {
                        wch: 50
                    }, // Perihal
                    {
                        wch: 5
                    } // Kode Arsip
                ];
                ws['!cols'] = wscols;

                // Menambahkan style pada header
                var range = XLSX.utils.decode_range(ws['!ref']);
                for (var C = range.s.c; C <= range.e.c; ++C) {
                    var address = XLSX.utils.encode_col(C) + "1";
                    if (!ws[address]) continue;
                    ws[address].s = {
                        font: {
                            bold: true
                        },
                        fill: {
                            fgColor: {
                                rgb: "EFEFEF"
                            }
                        },
                        alignment: {
                            horizontal: "center",
                            vertical: "center"
                        }
                    };
                }

                XLSX.utils.book_append_sheet(wb, ws, "Surat Masuk Eksternal");
                XLSX.writeFile(wb, 'surat-masuk-eksternal.xlsx');
            });

            // Ekspor ke PDF
            $('#exportPDF').on('click', function() {
                var doc = new jspdf.jsPDF();
                doc.autoTable({
                    html: '#smeTable',
                    columns: [1, 2, 3, 4, 5, 6],
                    styles: {
                        fontSize: 10,
                        halign: 'center'
                    },
                    columnStyles: {
                        0: {
                            cellWidth: 10
                        },
                        1: {
                            cellWidth: 40
                        },
                        2: {
                            cellWidth: 30
                        },
                        3: {
                            cellWidth: 25
                        },
                        4: {
                            cellWidth: 50
                        },
                        5: {
                            cellWidth: 25
                        }
                    },
                    headStyles: {
                        fillColor: [41, 128, 185],
                        textColor: 255,
                        fontSize: 9,
                        fontStyle: 'bold'
                    },
                    didDrawPage: function(data) {
                        doc.setFontSize(16);
                        doc.text("Daftar Surat Masuk Eksternal", 105, 15, {
                            align: 'center'
                        });
                        doc.setFontSize(8);
                        doc.autoTable.previous.finalY += 10; // Menambahkan jarak 10 unit
                    },
                    margin: {
                        top: 30
                    } // Menambahkan margin atas
                });
                doc.save('surat-masuk-eksternal.pdf');
            });
        });
    </script>
@endpush
