<?php

namespace App\Controllers;

use App\Models\RpdModel;
use App\Models\ProgramModel; 
use App\Models\KroModel;
use App\Models\WpdpModel;
use App\Models\RoModel;
use App\Models\KegiatanModel;
use App\Models\KomponentModel;
use App\Models\AkunModel;
use App\Models\DetilModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\Controller;

class Rpd extends Controller
{
    protected $rpdModel;
    protected $programModel;
    protected $kegiatanModel;
    protected $kroModel;
    protected $roModel;
    protected $komponenModel;
    protected $akunModel; 
    protected $detilModel;
    protected $tahunAnggaran = 2025;

    public function __construct()
    {
        $this->rpdModel = new RpdModel();
        // Inisialisasi model lain jika belum
        $this->programModel = new ProgramModel(); // Pastikan model ini ada
        $this->kegiatanModel = new KegiatanModel(); // Pastikan model ini ada
        $this->kroModel = new KroModel(); // Pastikan model ini ada
        $this->roModel = new RoModel(); // Pastikan model ini ada
        // $this->komponenModel = new KomponenModel(); // Pastikan model ini ada
        $this->akunModel = new AkunModel(); // Pastikan model ini ada
        $this->detilModel = new DetilModel();   // Pastikan model ini ada
    }

    // Metode untuk menampilkan laporan RPD (sudah ada)
    public function index()
    {
        $tahun = $this->request->getGet('tahun') ?? $this->tahunAnggaran; // Ambil tahun dari GET atau default
        $searchTerm = $this->request->getGet('search'); // Ambil nilai dari input search
        $reportData = $this->rpdModel->getRpdReportData($tahun, $searchTerm);
        
        $data = [
            'reportData' => $reportData,
            'tahun' => $tahun,
            'title' => 'Laporan Ketersediaan Dana Detail TA ' . $tahun,
            'searchTerm' => $searchTerm, // Kirim kembali nilai search term ke view
            'available_years' => $this->rpdModel->getAvailableYears() // Ambil daftar tahun yang tersedia
        ];

        return view('rpd/index', $data);
    }

    // Metode untuk export laporan RPD (sudah ada)
    public function export()
    {
         $tahun = $this->request->getGet('tahun') ?? $this->tahunAnggaran; // Ambil tahun dari GET atau default

         $reportData = $this->rpdModel->getRpdReportData($tahun);

         $spreadsheet = new Spreadsheet();
         $sheet = $spreadsheet->getActiveSheet();

         // Header Instansi
         $sheet->setCellValue('A1', 'Keterangan Instansi:');
         $sheet->setCellValue('B1', 'Kementerian     : 054 BADAN PUSAT STATISTIK');
         $sheet->setCellValue('B2', 'Unit Organisasi : 01 BADAN PUSAT STATISTIK');
         $sheet->setCellValue('B3', 'Satuan Kerja    : 018712 BADAN PUSAT STATISTIK KAB. TASIKMALAYA');
         $sheet->mergeCells('A1:A3'); // Merge cell keterangan instansi

         // Header Tabel
         $headerRow = 5; // Mulai header tabel dari baris 5
         $sheet->setCellValue('A' . $headerRow, 'No');
         $sheet->setCellValue('B' . $headerRow, 'Uraian'); // Gabungan hierarki
         $sheet->setCellValue('C' . $headerRow, 'Pagu Revisi');
         $sheet->setCellValue('D' . $headerRow, 'Lock Pagu');
         $sheet->setCellValue('E' . $headerRow, 'Sisa Pagu');
         $sheet->setCellValue('F' . $headerRow, 'JANUARI');
         $sheet->setCellValue('G' . $headerRow, 'FEBRUARI');
         $sheet->setCellValue('H' . $headerRow, 'MARET');
         $sheet->setCellValue('I' . $headerRow, 'APRIL');
         $sheet->setCellValue('J' . $headerRow, 'MEI');
         $sheet->setCellValue('K' . $headerRow, 'JUNI');
         $sheet->setCellValue('L' . $headerRow, 'JULI');
         $sheet->setCellValue('M' . $headerRow, 'AGUSTUS');
         $sheet->setCellValue('N' . $headerRow, 'SEPTEMBER');
         $sheet->setCellValue('O' . $headerRow, 'OKTOBER');
         $sheet->setCellValue('P' . $headerRow, 'NOVEMBER');
         $sheet->setCellValue('Q' . $headerRow, 'DESEMBER');
         $sheet->setCellValue('R' . $headerRow, 'JUMLAH'); // Total RPD setahun
         $sheet->setCellValue('S' . $headerRow, 'Keterangan'); // Keterangan RPD

         // Data Tabel
         $row = $headerRow + 1;
         $no = 1;
         foreach ($reportData as $item) {
             // Gabungkan Uraian (hierarki)
             $uraian = //$item['nama_program'] . "\n"
                     //. $item['nama_kegiatan'] . "\n"
                     //. $item['nama_kro'] . "\n"
                     //. $item['kode_ro'] . ' ' . $item['nama_ro'] . "\n"
                       $item['kode_komponen'] . ' ' . $item['nama_komponen'] . "\n"
                     //. $item['kode_akun'] . ' ' . $item['nama_akun'] . "\n"
                     . $item['kode_detil'] . ' ' . $item['nama_detil'];

             $sheet->setCellValue('A' . $row, $no++);
             $sheet->setCellValue('B' . $row, $uraian);
             $sheet->setCellValue('C' . $row, $item['pagu_revisi'] ?? 0); // Angka murni
             $sheet->setCellValue('D' . $row, $item['lock_pagu'] ?? 0); // Angka murni
             $sheet->setCellValue('E' . $row, $item['sisa_pagu'] ?? 0); // Angka murni
             $sheet->setCellValue('F' . $row, $item['januari'] ?? 0);
             $sheet->setCellValue('G' . $row, $item['februari'] ?? 0);
             $sheet->setCellValue('H' . $row, $item['maret'] ?? 0);
             $sheet->setCellValue('I' . $row, $item['april'] ?? 0);
             $sheet->setCellValue('J' . $row, $item['mei'] ?? 0);
             $sheet->setCellValue('K' . $row, $item['juni'] ?? 0);
             $sheet->setCellValue('L' . $row, $item['juli'] ?? 0);
             $sheet->setCellValue('M' . $row, $item['agustus'] ?? 0);
             $sheet->setCellValue('N' . $row, $item['september'] ?? 0);
             $sheet->setCellValue('O' . $row, $item['oktober'] ?? 0);
             $sheet->setCellValue('P' . $row, $item['november'] ?? 0);
             $sheet->setCellValue('Q' . $row, $item['desember'] ?? 0);
             $sheet->setCellValue('R' . $row, $item['jumlah_rpd'] ?? 0); // Total RPD setahun
             $sheet->setCellValue('S' . $row, $item['keterangan_rpd_rekap'] ?? '-'); // Rekap keterangan

             // Set wrap text untuk kolom Uraian dan Keterangan
             $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);
             $sheet->getStyle('S' . $row)->getAlignment()->setWrapText(true);

             $row++;
         }

         // Format kolom angka sebagai mata uang (Opsional, bisa disesuaikan)
         $currencyColumns = ['C', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R'];
         foreach($currencyColumns as $col) {
             $sheet->getStyle($col . ($headerRow + 1) . ':' . $col . ($row - 1))->getNumberFormat()->setFormatCode('#,##0');
         }


         // Set Header untuk download
         $filename = 'laporan_rpd_detail_' . $tahun . '_' . date('Ymd_His') . '.xlsx';
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         header("Content-Disposition: attachment; filename=\"$filename\"");
         header('Cache-Control: max-age=0');

         $writer = new Xlsx($spreadsheet);
         $writer->save('php://output');
         exit();
    }

    // Metode untuk menampilkan form input RPD
    public function formRpd()
    {
        // Mendapatkan daftar tahun yang tersedia (bisa dari data RPD atau range tahun)
        $availableYears = $this->rpdModel->getAvailableYears();
        if (empty($availableYears)) {
            // Jika belum ada data RPD, tampilkan tahun saat ini dan beberapa tahun ke depan/belakang
            $currentYear = date('Y');
            $years = range($currentYear - 1, $currentYear + 1); // Contoh range
            rsort($years);
            $availableYears = array_map(function($y) { return ['tahun' => $y]; }, $years);
        } else {
             $years = array_column($availableYears, 'tahun');
             rsort($years);
             $availableYears = array_map(function($y) { return ['tahun' => $y]; }, $years);
        }


        // Daftar bulan
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Mendapatkan daftar Program untuk dropdown pertama
        $programs = $this->programModel->findAll(); // Asumsi ProgramModel punya method findAll()

        $data = [
            'title' => 'Form Input Rencana Penarikan Dana',
            'available_years' => $availableYears,
            'months' => $months,
            'programs' => $programs,
        ];

        return view('masterdata/index', $data);
    }

    // Metode AJAX untuk mengambil data RPD yang sudah ada
    public function getExistingRpd()
    {
        $detilId = $this->request->getPost('detil_id');
        $tahun = $this->request->getPost('tahun');
        $bulan = $this->request->getPost('bulan');

        if (!$detilId || !$tahun || !$bulan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak lengkap.']);
        }

        // Cari data RPD berdasarkan detil_id, tahun, dan bulan
        $rpdData = $this->rpdModel->where('detil_id', $detilId)
                                  ->where('tahun', $tahun)
                                  ->where('bulan', $bulan)
                                  ->first(); // Ambil satu baris

        if ($rpdData) {
            return $this->response->setJSON(['status' => 'success', 'data' => $rpdData]);
        } else {
            return $this->response->setJSON(['status' => 'not_found', 'message' => 'Data RPD belum ada untuk kombinasi ini.']);
        }
    }

        // Metode AJAX BARU untuk mendapatkan Pagu Revisi Tersedia
        public function getAvailablePaguRpd()
        {
            $detilId = $this->request->getPost('detil_id');
            $tahun = $this->request->getPost('tahun');
            $bulan = $this->request->getPost('bulan'); // Bulan yang dipilih di form
    
            if (!$detilId || !$tahun || !$bulan) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter (detil_id, tahun, bulan) tidak lengkap.']);
            }
    
            // 1. Ambil Pagu Revisi Terakhir untuk Detil ini
            $paguRevisiTerakhir = $this->detilModel->getPaguRevisiTerakhirByDetilId($detilId);
    
            // 2. Hitung Total RPD yang sudah direncanakan di bulan-bulan SEBELUM bulan yang dipilih
            $totalRpdSebelumnya = $this->rpdModel->getTotalRpdSebelumnya($detilId, $tahun, $bulan);
    
            // 3. Hitung Pagu Revisi Tersedia
            $availablePagu = $paguRevisiTerakhir - $totalRpdSebelumnya;
    
            // Pastikan nilai tidak negatif (meskipun seharusnya tidak terjadi jika data konsisten)
            $availablePagu = max(0, $availablePagu);
    
            return $this->response->setJSON(['status' => 'success', 'available_pagu' => $availablePagu]);
        }

    // Metode untuk menyimpan data RPD (Insert atau Update)
    public function simpanRpd()
    {
        $rules = [
            'tahun' => 'required|integer',
            'bulan' => 'required|string',
            'detil_id' => 'required|integer',
            'rencana_penarikan_dana' => 'required|numeric', // Bisa 0
            'keterangan' => 'permit_empty|string', // Keterangan boleh kosong
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembalikan ke form dengan error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'detil_id' => $this->request->getPost('detil_id'),
            'tahun' => $this->request->getPost('tahun'),
            'bulan' => $this->request->getPost('bulan'),
            'rencana_penarikan_dana' => $this->request->getPost('rencana_penarikan_dana'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        // --- VALIDASI SERVER-SIDE TERHADAP PAGU REVISI TERSEDIA ---

        // 1. Ambil Pagu Revisi Terakhir untuk Detil ini
        $paguRevisiTerakhir = $this->detilModel->getPaguRevisiTerakhirByDetilId($detilId);

        // 2. Hitung Total RPD yang sudah direncanakan di bulan-bulan SEBELUM bulan yang dipilih
        $totalRpdSebelumnya = $this->rpdModel->getTotalRpdSebelumnya($detilId, $tahun, $bulan);

        // 3. Hitung Pagu Revisi Tersedia saat ini (sebelum input data bulan ini)
        $availablePaguSaatIni = $paguRevisiTerakhir - $totalRpdSebelumnya;

        // 4. Jika ini adalah update data RPD yang sudah ada untuk bulan ini,
        //    kita perlu mengurangkan nilai RPD lama dari total RPD sebelumnya
        //    untuk mendapatkan pagu yang benar-benar tersedia untuk nilai BARU bulan ini.
        $existingRpdForThisMonth = $this->rpdModel->where('detil_id', $detilId)
                                                   ->where('tahun', $tahun)
                                                   ->where('bulan', $bulan)
                                                   ->first();
        $oldRpdValueForThisMonth = $existingRpdForThisMonth ? ($existingRpdForThisMonth['rencana_penarikan_dana'] ?? 0) : 0;

        // Pagu yang benar-benar tersedia untuk nilai BARU bulan ini
        $trulyAvailablePagu = $availablePaguSaatIni + $oldRpdValueForThisMonth;


        // 5. Lakukan validasi: Rencana Penarikan Dana yang diinput TIDAK BOLEH melebihi pagu yang benar-benar tersedia
        if ($rencanaPenarikanDana > $trulyAvailablePagu) {
            // Jika melebihi, tambahkan error manual
            $errors = $this->validator->getErrors(); // Ambil error validasi standar jika ada
            $errors['rencana_penarikan_dana'] = 'Rencana Penarikan Dana (Rp ' . number_format($rencanaPenarikanDana, 0, ',', '.') . ') melebihi pagu yang tersedia (Rp ' . number_format($trulyAvailablePagu, 0, ',', '.') . ') untuk bulan ini setelah memperhitungkan rencana bulan sebelumnya.';
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // --- AKHIR VALIDASI SERVER-SIDE ---
        
        // Cek apakah data RPD sudah ada untuk detil, tahun, dan bulan ini
        $existingRpd = $this->rpdModel->where('detil_id', $data['detil_id'])
                                      ->where('tahun', $data['tahun'])
                                      ->where('bulan', $data['bulan'])
                                      ->first();

        if ($existingRpd) {
            // Jika sudah ada, lakukan update
            $updateResult = $this->rpdModel->update($existingRpd['id'], $data);
            $message = 'Data RPD berhasil diperbarui.';
            $status = 'success';
        } else {
            // Jika belum ada, lakukan insert
            $insertResult = $this->rpdModel->insert($data);
             if ($insertResult === false) {
                 // Handle insert error if necessary
                 $message = 'Gagal menyimpan data RPD.';
                 $status = 'error';
                 log_message('error', 'Failed to insert RPD data: ' . json_encode($this->rpdModel->errors()));
             } else {
                 $message = 'Data RPD berhasil disimpan.';
                 $status = 'success';
             }
        }

        // Redirect kembali ke form dengan pesan sukses atau error
        return redirect()->to(base_url('rpd/form'))->with($status, $message);
    }
}
