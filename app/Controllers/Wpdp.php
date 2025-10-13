<?php

namespace App\Controllers;

use App\Models\ProgramModel;
use App\Models\KegiatanModel;
use App\Models\KroModel;
use App\Models\RoModel;
use App\Models\KomponenModel;
use App\Models\AkunModel;
use App\Models\DetilModel;
use App\Models\WpdpModel;
use App\Models\SolusiModel;
use App\Models\KendalaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Wpdp extends BaseController
{
    protected $programModel;
    protected $kegiatanModel;
    protected $kroModel;
    protected $roModel;
    protected $komponenModel;
    protected $akunModel;
    protected $detilModel;
    protected $wpdpModel;
    protected $solusiModel;
    protected $kendalaModel;
    protected $db;

    // Hardcode Tahun Anggaran
    private $tahunAnggaran = 2025;

    public function __construct()
    {
        $this->programModel = new ProgramModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->kroModel = new KroModel();
        $this->roModel = new RoModel();
        $this->komponenModel = new KomponenModel();
        $this->akunModel = new AkunModel();
        $this->detilModel = new DetilModel();
        $this->wpdpModel = new WpdpModel();
        $this->solusiModel = new SolusiModel();
        $this->kendalaModel = new KendalaModel();
        $this->db = \Config\Database::connect();
    }

    // Metode untuk menampilkan form (tambah dan edit)
    public function form($id = null)
    {
        // Mapping nama bulan ke angka untuk perbandingan urutan (digunakan di mode edit)
        $monthMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3,
            'April' => 4, 'Mei' => 5, 'Juni' => 6,
            'Juli' => 7, 'Agustus' => 8, 'September' => 9,
            'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];

        $data = [
            'programs' => $this->programModel->findAll(), // Tetap muat semua program untuk mode tambah
            'selected' => null,
            'wpdp' => null,
            'kendala_list' => [''], // Default 1 baris kosong
            'solusi_list' => [''],  // Default 1 baris kosong
            'kegiatans' => [], // Kosongkan, akan diisi JS atau dari selected
            'kros' => [],      // Kosongkan
            'ros' => [],       // Kosongkan
            'komponens' => [], // Kosongkan
            'akuns' => [],
            'detils' => [],
            'is_editing_existing' => false,
            'is_creating_from_ro' => false,
            'initial_anggaran' => null // Tambahkan ini untuk mengirim nilai anggaran awal ke view
        ];

        // Jika mode edit
        if ($id !== null) {
            $wpdp = $this->wpdpModel->find($id);

            if (!$wpdp) {
                throw new PageNotFoundException("Data WPDP dengan ID $id tidak ditemukan.");
            }

            $data['wpdp'] = $wpdp;
            $data['selected'] = [ // Ambil ID hierarki dari record WPDP
                'program_id' => $wpdp['program_id'],
                'kegiatan_id' => $wpdp['kegiatan_id'],
                'kro_id' => $wpdp['kro_id'],
                'ro_id' => $wpdp['ro_id'],
                'komponen_id' => $wpdp['komponen_id'],
                'akun_id' => $wpdp['akun_id'], // Pastikan ini ada di tabel wpdp_bulanan jika disimpan langsung
                'detil_id' => $wpdp['detil_id'] // Pastikan ini ada dan terisi
            ];

            // Muat Kendala dan Solusi terkait untuk form edit
            $kendala = $this->kendalaModel->where('wpdp_bulanan_id', $id)->findAll();
            $solusi = $this->solusiModel->where('wpdp_bulanan_id', $id)->findAll();

            // Bentuk menjadi array sederhana untuk view
            $data['kendala_list'] = array_column($kendala, 'kendala');
            $data['solusi_list'] = array_column($solusi, 'solusi');

            // Jika tidak ada kendala/solusi tersimpan, pastikan tetap ada 1 baris kosong di form
            if (empty($data['kendala_list']))
                $data['kendala_list'] = [''];
            if (empty($data['solusi_list']))
                $data['solusi_list'] = [''];

            // Untuk dropdown yang disabled saat edit, kita perlu memastikan opsi yang terpilih ada.
            // PHP view kita sudah menangani ini dengan mem-foreach $kegiatans, $kros, dll.
            // Kita perlu memuat hanya item yang terpilih di sini, BUKAN findAll(), agar efisien.
            // Atau, biarkan view memuat semua (kurang efisien) atau modifikasi view untuk hanya menampilkan selected item.
            // Dengan desain view yang disempurnakan dan JS, kita bisa *tidak* perlu memuat ini di controller edit,
            // karena view akan mendapatkan ID selected dari $wpdp dan JS akan memuat Komponen.
            // Namun, agar opsi terpilih (disabled) di dropdown Program s/d RO muncul, view perlu data tersebut.
            // Mari kita muat hanya item yang terpilih untuk dropdown yang disabled:
            if (!empty($wpdp['program_id'])) {
                 $program = $this->programModel->find($wpdp['program_id']);
                 if ($program) $data['programs'] = [$program];
            }
            if (!empty($wpdp['kegiatan_id'])) {
                 $kegiatan = $this->kegiatanModel->find($wpdp['kegiatan_id']);
                 if ($kegiatan) $data['kegiatans'] = [$kegiatan];
            }
            if (!empty($wpdp['kro_id'])) {
                 $kro = $this->kroModel->find($wpdp['kro_id']);
                 if ($kro) $data['kros'] = [$kro];
            }
            if (!empty($wpdp['ro_id'])) {
                 $ro = $this->roModel->find($wpdp['ro_id']);
                 if ($ro) $data['ros'] = [$ro];
            }

             // Muat item terpilih untuk dropdown yang tidak disabled (Komponen, Akun, Detil)
             // Ini akan di-override oleh JS loadOptions saat halaman dimuat, tapi baik untuk fallback
             if (!empty($wpdp['komponen_id'])) {
                 $komponen = $this->komponenModel->find($wpdp['komponen_id']);
                 if ($komponen) $data['komponens'] = [$komponen];
             }
             if (!empty($wpdp['akun_id'])) {
                 $akun = $this->akunModel->find($wpdp['akun_id']);
                 if ($akun) $data['akuns'] = [$akun];
             }
             if (!empty($wpdp['detil_id'])) {
                 $detil = $this->detilModel->find($wpdp['detil_id']);
                 if ($detil) $data['detils'] = [$detil];
             }


             // Hitung initial_anggaran untuk mode edit
             // Menggunakan $this->tahunAnggaran (2025)
             if (!empty($wpdp['detil_id']) && !empty($wpdp['bulan'])) {
                 $detil = $this->detilModel->find($wpdp['detil_id']);
                 $paguAwal = $detil['pagu_anggaran_awal'] ?? 0;

                 if ($wpdp['bulan'] === 'Januari') {
                     $data['initial_anggaran'] = $paguAwal;
                 } else {
                     $currentMonthNumber = $monthMap[$wpdp['bulan']]; // Gunakan monthMap yang sama

                     $subQuery = $this->db->table('wpdp_bulanan wb_prev')
                         ->select('SUM(wb_prev.realisasi_dp)')
                         ->where('wb_prev.detil_id', $wpdp['detil_id'])
                         ->where('wb_prev.tahun', $this->tahunAnggaran) // Hardcode tahun 2025
                         ->where("
                             CASE wb_prev.bulan
                                 WHEN 'Januari' THEN 1 WHEN 'Februari' THEN 2 WHEN 'Maret' THEN 3
                                 WHEN 'April' THEN 4 WHEN 'Mei' THEN 5 WHEN 'Juni' THEN 6
                                 WHEN 'Juli' THEN 7 WHEN 'Agustus' THEN 8 WHEN 'September' THEN 9
                                 WHEN 'Oktober' THEN 10 WHEN 'November' THEN 11 WHEN 'Desember' THEN 12
                             END < " . $currentMonthNumber
                         )
                         ->getCompiledSelect();

                     $query = $this->db->query($subQuery);
                     $result = $query->getRow();
                     $totalRealisasiSebelumnya = $result ? ($result->{'SUM(wb_prev.realisasi_dp)'} ?? 0) : 0;

                     $data['initial_anggaran'] = $paguAwal - $totalRealisasiSebelumnya;
                 }
             }


            // Muat Kendala dan Solusi terkait
            $kendala = $this->kendalaModel->where('wpdp_bulanan_id', $id)->findAll();
            $solusi = $this->solusiModel->where('wpdp_bulanan_id', $id)->findAll();
            $data['kendala_list'] = array_column($kendala, 'kendala');
            $data['solusi_list'] = array_column($solusi, 'solusi');
            if (empty($data['kendala_list'])) $data['kendala_list'] = [''];
            if (empty($data['solusi_list'])) $data['solusi_list'] = [''];

            $data['is_editing_existing'] = true;
        } else {
             // Mode tambah baru, set default selected IDs ke null
             $data['selected'] = [
                 'program_id' => $this->request->getGet('program_id'), // Ambil dari GET jika ada (dari createFromRo)
                 'kegiatan_id' => $this->request->getGet('kegiatan_id'), // Ambil dari GET jika ada
                 'kro_id' => $this->request->getGet('kro_id'), // Ambil dari GET jika ada
                 'ro_id' => $this->request->getGet('ro_id'), // Ambil dari GET jika ada
                 'komponen_id' => null,
                 'akun_id' => null,
                 'detil_id' => null,
                 'bulan' => null,
             ];
             $data['is_editing_existing'] = false;
             $data['is_creating_from_ro'] = $this->request->getGet('from_ro') === 'true'; // Set flag jika dari RO
             $data['initial_anggaran'] = null; // Tidak ada anggaran awal di mode tambah

             // --- LOGIKA BARU: Muat data master terpilih jika is_creating_from_ro true ---
             // Ini agar dropdown disabled di view terisi dengan nama yang benar
             if ($data['is_creating_from_ro']) {
                 if (!empty($data['selected']['program_id'])) {
                     $program = $this->programModel->find($data['selected']['program_id']);
                     if ($program) {
                         $data['programs'] = [$program]; // Hanya muat program terpilih
                     }
                 }
                 if (!empty($data['selected']['kegiatan_id'])) {
                     $kegiatan = $this->kegiatanModel->find($data['selected']['kegiatan_id']);
                     if ($kegiatan) {
                         $data['kegiatans'] = [$kegiatan]; // Hanya muat kegiatan terpilih
                     }
                 }
                 if (!empty($data['selected']['kro_id'])) {
                     $kro = $this->kroModel->find($data['selected']['kro_id']);
                     if ($kro) {
                         $data['kros'] = [$kro]; // Hanya muat kro terpilih
                     }
                 }
                 if (!empty($data['selected']['ro_id'])) {
                     $ro = $this->roModel->find($data['selected']['ro_id']);
                     if ($ro) {
                         $data['ros'] = [$ro]; // Hanya muat ro terpilih
                     }
                 }
             }
             // --- AKHIR LOGIKA BARU ---
        }


        return view('wpdp/form', $data);
    }

    // Metode untuk menampilkan form tambah WPDP baru berdasarkan ID RO
    // Metode ini hanya melakukan redirect ke form() dengan parameter GET
    public function createFromRo($roId)
    {
        // 1. Cari data RO berdasarkan ID yang diterima
        $ro = $this->roModel->find($roId);

        // Jika RO tidak ditemukan, tampilkan error 404
        if (!$ro) {
            throw new PageNotFoundException("RO dengan ID $roId tidak ditemukan.");
        }

        // 2. Ambil data hierarki di atas RO tersebut
        // Asumsi: RO punya kro_id, KRO punya kegiatan_id, Kegiatan punya program_id
        $kro = $this->kroModel->find($ro['kro_id']);
        if (!$kro) { // Seharusnya tidak terjadi jika relasi data konsisten
            throw new \Exception("KRO untuk RO ID $roId tidak ditemukan.");
        }

        $kegiatan = $this->kegiatanModel->find($kro['kegiatan_id']);
        if (!$kegiatan) { // Seharusnya tidak terjadi jika relasi data konsisten
            throw new \Exception("Kegiatan untuk KRO ID " . $kro['id'] . " tidak ditemukan.");
        }

        $program = $this->programModel->find($kegiatan['program_id']);
        if (!$program) { // Seharusnya tidak terjadi jika relasi data konsisten
            throw new \Exception("Program untuk Kegiatan ID " . $kegiatan['id'] . " tidak ditemukan.");
        }

        // Redirect ke metode form dengan parameter GET
        // Mengirim ID Program, Kegiatan, KRO, RO, dan flag from_ro
        return redirect()->to(base_url('wpdp/form') . '?from_ro=true&program_id=' . $program['id'] . '&kegiatan_id=' . $kegiatan['id'] . '&kro_id=' . $kro['id'] . '&ro_id=' . $ro['id']);
    }


    // Metode untuk endpoint AJAX (di controller ini atau controller terpisah)
    // Sesuaikan URL di JS jika ini dipindah ke controller lain

    public function getKegiatanByProgram($programId)
    {
        $data = $this->kegiatanModel->where('program_id', $programId)->findAll();
        return $this->response->setJSON($data);
    }

    public function getKroByKegiatan($kegiatanId)
    {
        // PERBAIKI: Nama kolom di tabel kro yang terhubung ke kegiatan adalah kegiatan_id
        $data = $this->kroModel->where('kegiatan_id', $kegiatanId)->findAll();
        return $this->response->setJSON($data);
    }

    public function getRoByKro($kroId)
    {
        $data = $this->roModel->where('kro_id', $kroId)->findAll();
        return $this->response->setJSON($data);
    }

    public function getKomponenByRo($roId)
    {
        $data = $this->komponenModel->where('ro_id', $roId)->findAll();
        return $this->response->setJSON($data);
    }

    public function getAkunByKomponen($komponenId)
    {
        $akuns = $this->akunModel->where('komponen_id', $komponenId)->findAll();
        return $this->response->setJSON($akuns);
    }

    public function getDetilByAkun($akunId)
    {
        $detils = $this->detilModel->where('akun_id', $akunId)->findAll();
        return $this->response->setJSON($detils);
    }

    // Metode untuk menghitung pagu tersisa berdasarkan Detil dan Bulan (Tahun hardcode 2025)
    public function getAnggaranByDetilMonthYear($detilId = null, $month = null)
    {
        // Pastikan parameter diterima
        if (empty($detilId) || empty($month)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Parameter detil_id dan bulan wajib diisi.']);
        }

        // Mapping nama bulan ke angka untuk perbandingan urutan
        $monthMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3,
            'April' => 4, 'Mei' => 5, 'Juni' => 6,
            'Juli' => 7, 'Agustus' => 8, 'September' => 9,
            'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];

        // Validasi bulan
        if (!isset($monthMap[$month])) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Nama bulan tidak valid.']);
        }

        // 1. Ambil Pagu Awal Detil
        $detil = $this->detilModel->find($detilId);
        if (!$detil) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Detil dengan ID ' . $detilId . ' tidak ditemukan.']);
        }
        $paguAwal = $detil['pagu_revisi_terakhir'] ?? 0; // Ambil pagu awal, default 0 jika NULL

        // 2. Jika bulan Januari, pagu yang ditampilkan adalah pagu awal
        if ($month === 'Januari') {
            return $this->response->setJSON(['anggaran' => $paguAwal]);
        }

        // 3. Jika bulan selain Januari, hitung total realisasi bulan sebelumnya
        $currentMonthNumber = $monthMap[$month];

        // Subquery untuk menjumlahkan realisasi DP untuk detil dan tahun 2025 di bulan-bulan sebelumnya
        $subQuery = $this->db->table('wpdp_bulanan wb_prev')
            ->select('SUM(wb_prev.realisasi_dp)')
            ->where('wb_prev.detil_id', $detilId)
            ->where('wb_prev.tahun', $this->tahunAnggaran) // Hardcode tahun 2025
            // Menggunakan mapping bulan ke angka untuk perbandingan
            ->where("
                CASE wb_prev.bulan
                    WHEN 'Januari' THEN 1 WHEN 'Februari' THEN 2 WHEN 'Maret' THEN 3
                    WHEN 'April' THEN 4 WHEN 'Mei' THEN 5 WHEN 'Juni' THEN 6
                    WHEN 'Juli' THEN 7 WHEN 'Agustus' THEN 8 WHEN 'September' THEN 9
                    WHEN 'Oktober' THEN 10 WHEN 'November' THEN 11 WHEN 'Desember' THEN 12
                END < " . $currentMonthNumber
            )
            ->getCompiledSelect(); // Dapatkan string SQL dari subquery

        // Jalankan subquery untuk mendapatkan total realisasi sebelumnya
        $query = $this->db->query($subQuery);
        $result = $query->getRow();
        $totalRealisasiSebelumnya = $result ? ($result->{'SUM(wb_prev.realisasi_dp)'} ?? 0) : 0;

        // 4. Hitung pagu tersisa
        $paguTersisa = $paguAwal - $totalRealisasiSebelumnya;

        // Kembalikan nilai pagu tersisa
        return $this->response->setJSON(['anggaran' => $paguTersisa]);
    }


    // Metode simpan (untuk data baru)
    public function simpan()
    {
        // 1. Definisi Validasi
        $rules = [
            'program_id' => 'required',
            'kegiatan_id' => 'required',
            'kro_id' => 'required',
            'ro_id' => 'required',
            'komponen_id' => 'required',
            'akun_id' => 'required',
            'detil_id' => 'required',
            'bulan' => 'required',
            'usulan_wp' => 'required|numeric',
            'realisasi_dp' => 'required|numeric',
            'memo' => 'permit_empty|min_length[1]'
        ];

        // 2. Jalankan Validasi
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Ambil Data Utama dari POST
        $mainData = [
            'program_id' => $this->request->getPost('program_id'),
            'kegiatan_id' => $this->request->getPost('kegiatan_id'),
            'kro_id' => $this->request->getPost('kro_id'),
            'ro_id' => $this->request->getPost('ro_id'),
            'komponen_id' => $this->request->getPost('komponen_id'),
            'akun_id' => $this->request->getPost('akun_id'),
            'detil_id' => $this->request->getPost('detil_id'),
            'bulan' => $this->request->getPost('bulan'),
            'tahun' => $this->tahunAnggaran, // Hardcode tahun 2025
            'usulan_wp' => $this->request->getPost('usulan_wp'),
            'realisasi_dp' => $this->request->getPost('realisasi_dp'),
            'memo' => $this->request->getPost('memo')
        ];

        $this->db->transBegin();

        try {
            $this->wpdpModel->insert($mainData);
            $wpdpId = $this->wpdpModel->getInsertID();

            // Simpan Kendala
            $kendalaArray = $this->request->getPost('kendala');
        if (is_array($kendalaArray)) {
            $kendalaData = [];
            foreach ($kendalaArray as $kendala) {
                $kendala = trim($kendala);
                if (empty($kendala)) {
                    $kendala = '-'; // Isi default jika kosong
                }
                $kendalaData[] = [
                    'wpdp_bulanan_id' => $wpdpId,
                    'kendala' => $kendala
                ];
            }
            $this->kendalaModel->insertBatch($kendalaData);
        }

            // Simpan Solusi
            $solusiArray = $this->request->getPost('solusi');
        if (is_array($solusiArray)) {
            $solusiData = [];
            foreach ($solusiArray as $solusi) {
                $solusi = trim($solusi);
                if (empty($solusi)) {
                    $solusi = '-'; // Isi default jika kosong
                }
                $solusiData[] = [
                    'wpdp_bulanan_id' => $wpdpId,
                    'solusi' => $solusi
                ];
            }
            $this->solusiModel->insertBatch($solusiData);
        }

            $this->db->transCommit();

            return redirect()->to('/wpdp')->with('success', 'Data WPDP Bulanan berhasil disimpan.');

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Simpan WPDP failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data WPDP Bulanan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $wpdpModel = new \App\Models\WpdpModel();
        $programModel = new \App\Models\ProgramModel();
        $kegiatanModel = new \App\Models\KegiatanModel();
        $kroModel = new \App\Models\KroModel();
        $roModel = new \App\Models\RoModel();
        $komponenModel = new \App\Models\KomponenModel();
        $akunModel = new \App\Models\AkunModel();
        $detilModel = new \App\Models\DetilModel();
        $solusiModel = new \App\Models\SolusiModel();
        $kendalaModel = new \App\Models\KendalaModel();


        $wpdp = $wpdpModel->find($id);

        if (!$wpdp) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data tidak ditemukan");
        }

        // Ambil data relasi berdasarkan ID dari wpdp
        $selectedProgram = $programModel->find($wpdp['program_id']);
        $selectedKegiatan = $kegiatanModel->find($wpdp['kegiatan_id']);
        $selectedKRO = $kroModel->find($wpdp['kro_id']);
        $selectedRO = $roModel->find($wpdp['ro_id']);
        $selectedKomponen = $komponenModel->find($wpdp['komponen_id']);
        $selectedAkun = $akunModel->find($wpdp['akun_id']);
        $selectedDetil = $detilModel->find($wpdp['detil_id']);
        // Ambil data kendala dan solusi
        $kendala = $kendalaModel->where('wpdp_bulanan_id', $id)->findAll();
        $solusi = $solusiModel->where('wpdp_bulanan_id', $id)->findAll();
        // Bentuk array sederhana untuk view
        $kendalaList = array_column($kendala, 'kendala');
        $solusiList = array_column($solusi, 'solusi');
        // Jika tidak ada kendala/solusi, tambahkan elemen kosong
        if (empty($kendalaList)) {
            $kendalaList = [''];
        }
        if (empty($solusiList)) {
            $solusiList = [''];
        }

        return view('wpdp/edit', [
            'wpdp' => $wpdp,
            'selectedProgram' => $selectedProgram,
            'selectedKegiatan' => $selectedKegiatan,
            'selectedKRO' => $selectedKRO,
            'selectedRO' => $selectedRO,
            'selectedKomponen' => $selectedKomponen,
            'selectedAkun' => $selectedAkun,
            'selectedDetil' => $detilModel->find($wpdp['detil_id']),
            'kendala_list' => array_column($kendala, 'kendala'),
            'solusi_list' => array_column($solusi, 'solusi'),
        ]);
    }

    // Metode update (untuk edit data)

public function update($id)
{
    // 1. Validasi
    $rules = [
        'program_id' => 'required',
        'kegiatan_id' => 'required',
        'kro_id' => 'required',
        'ro_id' => 'required',
        'komponen_id' => 'required',
        'akun_id' => 'required',
        'detil_id' => 'required',
        'bulan' => 'required',
        'usulan_wp' => 'required|numeric',
        'realisasi_dp' => 'required|numeric',
        'memo' => 'permit_empty|min_length[1]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // 2. Ambil Data Utama dari POST
    $mainData = [
        'program_id' => $this->request->getPost('program_id'),
        'kegiatan_id' => $this->request->getPost('kegiatan_id'),
        'kro_id' => $this->request->getPost('kro_id'),
        'ro_id' => $this->request->getPost('ro_id'),
        'komponen_id' => $this->request->getPost('komponen_id'),
        'akun_id' => $this->request->getPost('akun_id'),
        'detil_id' => $this->request->getPost('detil_id'),
        'bulan' => $this->request->getPost('bulan'),
        'tahun' => $this->tahunAnggaran, // Hardcode tahun 2025
        'usulan_wp' => $this->request->getPost('usulan_wp'),
        'realisasi_dp' => $this->request->getPost('realisasi_dp'),
        'memo' => $this->request->getPost('memo')
    ];

    $this->db->transBegin();

    try {
        // Update data utama
        $this->wpdpModel->update($id, $mainData);

        // Hapus data kendala dan solusi lama
        $this->kendalaModel->where('wpdp_bulanan_id', $id)->delete();
        $this->solusiModel->where('wpdp_bulanan_id', $id)->delete();

        // Simpan data kendala baru
        $kendalaArray = $this->request->getPost('kendala');
        if (is_array($kendalaArray)) {
            $kendalaData = [];
            foreach ($kendalaArray as $kendala) {
                $kendala = trim($kendala);
                if (empty($kendala)) {
                    $kendala = '-'; // Isi default jika kosong
                }
                $kendalaData[] = [
                    'wpdp_bulanan_id' => $id,
                    'kendala' => $kendala
                ];
            }
            $this->kendalaModel->insertBatch($kendalaData);
        }

        // Simpan data solusi baru
        $solusiArray = $this->request->getPost('solusi');
        if (is_array($solusiArray)) {
            $solusiData = [];
            foreach ($solusiArray as $solusi) {
                $solusi = trim($solusi);
                if (empty($solusi)) {
                    $solusi = '-'; // Isi default jika kosong
                }
                $solusiData[] = [
                    'wpdp_bulanan_id' => $id,
                    'solusi' => $solusi
                ];
            }
            $this->solusiModel->insertBatch($solusiData);
        }

        $this->db->transCommit();

        return redirect()->to('/wpdp')->with('success', 'Data WPDP Bulanan berhasil diperbarui.');

    } catch (\Exception $e) {
        $this->db->transRollback();
        log_message('error', 'Update WPDP failed for ID ' . $id . ': ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data WPDP Bulanan: ' . $e->getMessage());
    }
}

    public function index()
    {
        // Mapping nama bulan ke angka untuk perbandingan urutan
        $monthMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3,
            'April' => 4, 'Mei' => 5, 'Juni' => 6,
            'Juli' => 7, 'Agustus' => 8, 'September' => 9,
            'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];

        // Subquery untuk menghitung total realisasi kumulatif per detil dan tahun 2025 sebelumnya
        $subQuery = $this->db->table('wpdp_bulanan wb_prev')
            ->select('SUM(wb_prev.realisasi_dp)')
            ->where('wb_prev.detil_id = wpdp_bulanan.detil_id')
             ->where('wb_prev.tahun', $this->tahunAnggaran) // Hardcode tahun 2025
            // Menggunakan mapping bulan ke angka untuk perbandingan
            ->where("
                CASE wb_prev.bulan
                    WHEN 'Januari' THEN 1 WHEN 'Februari' THEN 2 WHEN 'Maret' THEN 3
                    WHEN 'April' THEN 4 WHEN 'Mei' THEN 5 WHEN 'Juni' THEN 6
                    WHEN 'Juli' THEN 7 WHEN 'Agustus' THEN 8 WHEN 'September' THEN 9
                    WHEN 'Oktober' THEN 10 WHEN 'November' THEN 11 WHEN 'Desember' THEN 12
                END <
                CASE wpdp_bulanan.bulan
                    WHEN 'Januari' THEN 1 WHEN 'Februari' THEN 2 WHEN 'Maret' THEN 3
                    WHEN 'April' THEN 4 WHEN 'Mei' THEN 5 WHEN 'Juni' THEN 6
                    WHEN 'Juli' THEN 7 WHEN 'Agustus' THEN 8 WHEN 'September' THEN 9
                    WHEN 'Oktober' THEN 10 WHEN 'November' THEN 11 WHEN 'Desember' THEN 12
                END
            ")
            ->getCompiledSelect(); // Dapatkan string SQL dari subquery

        // Query utama untuk mengambil data WPDP Bulanan (filter tahun 2025)
        $data['wpdp'] = $this->wpdpModel
            ->select('wpdp_bulanan.*, p.nama_program, k.nama_kegiatan, kr.nama_kro, r.nama_ro, ko.nama_komponen, a.nama_akun, d.nama_detil, d.pagu_anggaran_awal') // Tambah pagu awal detil
            // Tambahkan kolom untuk pagu tersisa
            ->select("d.pagu_anggaran_awal - COALESCE((" . $subQuery . "), 0) AS pagu_tersisa") // Gunakan subquery
            ->where('wpdp_bulanan.tahun', $this->tahunAnggaran) // Filter data hanya untuk tahun 2025
            // JOIN dari wpdp_bulanan ke detil
            ->join('detil d', 'd.id = wpdp_bulanan.detil_id')
            // JOIN ke atas dari detil
            ->join('akun a', 'a.id = d.akun_id')
            ->join('komponen ko', 'ko.id = a.komponen_id')
            ->join('ro r', 'r.id = ko.ro_id')
            ->join('kro kr', 'kr.id = r.kro_id')
            ->join('kegiatan k', 'k.id = kr.kegiatan_id')
            ->join('program p', 'p.id = k.program_id')
            // Urutkan berdasarkan bulan dan hierarki
            ->orderBy('FIELD(wpdp_bulanan.bulan, "Januari", "Februari", "Maret", "April", "May", "June", "July", "August", "September", "October", "November", "December"), p.nama_program, k.nama_kegiatan, kr.nama_kro, r.nama_ro, ko.nama_komponen, a.nama_akun, d.nama_detil')
            ->findAll();

        return view('wpdp/index', $data);
    }

    public function delete($id)
    {
         // Mulai Transaksi jika tidak menggunakan ON DELETE CASCADE di DB
         $this->db->transBegin();
         try {
             // Hapus Kendala & Solusi terkait secara manual jika tidak pakai ON DELETE CASCADE
             $this->kendalaModel->where('wpdp_bulanan_id', $id)->delete();
             $this->solusiModel->where('wpdp_bulanan_id', $id)->delete();

             // Hapus record utama WPDP
             $this->wpdpModel->delete($id);

             $this->db->transCommit();
             return redirect()->to(base_url('wpdp'))->with('success', 'Data berhasil dihapus.');

         } catch (\Exception $e) {
             $this->db->transRollback();
             log_message('error', 'Delete WPDP failed for ID ' . $id . ': ' . $e->getMessage());
             return redirect()->to(base_url('wpdp'))->with('error', 'Gagal menghapus data: ' . $e->getMessage());
         }
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $data = $db->table('wpdp_bulanan w')
             ->select('w.*, p.nama_program, k.nama_kegiatan, kr.nama_kro, r.nama_ro, ko.nama_komponen, a.nama_akun, d.nama_detil') // Tambah select akun & detil
             ->where('w.tahun', $this->tahunAnggaran) // Filter data hanya untuk tahun 2025
            ->join('detil d', 'd.id = w.detil_id') // Join ke detil
            ->join('akun a', 'a.id = d.akun_id') // Join ke akun
            ->join('komponen ko', 'ko.id = a.komponen_id') // Join ke komponen
            ->join('ro r', 'r.id = ko.ro_id') // Join ke ro
            ->join('kro kr', 'kr.id = r.kro_id') // Join ke kro
            ->join('kegiatan k', 'k.id = kr.kegiatan_id') // Join ke kegiatan
            ->join('program p', 'p.id = k.program_id') // Join ke program
            ->orderBy('FIELD(w.bulan, "Januari", "Februari", "Maret", "April", "May", "June", "July", "August", "September", "October", "November", "December"), p.nama_program, k.nama_kegiatan, kr.nama_kro, r.nama_ro, ko.nama_komponen, a.nama_akun, d.nama_detil') // Urutkan
            ->get()->getResultArray(); // Tetap pakai get()->getResultArray() seperti sebelumnya

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Program');
        $sheet->setCellValue('C1', 'Kegiatan');
        $sheet->setCellValue('D1', 'KRO');
        $sheet->setCellValue('E1', 'RO');
        $sheet->setCellValue('F1', 'Komponen');
        $sheet->setCellValue('G1', 'Akun'); // Tambah header
        $sheet->setCellValue('H1', 'Detil'); // Tambah header
        $sheet->setCellValue('I1', 'Tahun'); // Tambah header
        $sheet->setCellValue('J1', 'Bulan');
        $sheet->setCellValue('K1', 'Anggaran');
        $sheet->setCellValue('L1', 'Realisasi');
        $sheet->setCellValue('M1', 'Memo');

        // Data
        $row = 2;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nama_program']);
            $sheet->setCellValue('C' . $row, $item['nama_kegiatan']);
            $sheet->setCellValue('D' . $row, $item['nama_kro']);
            $sheet->setCellValue('E' . $row, $item['nama_ro']);
            $sheet->setCellValue('F' . $row, $item['nama_komponen']);
            $sheet->setCellValue('G' . $row, $item['nama_akun']);
            $sheet->setCellValue('H' . $row, $item['nama_detil']);
            $sheet->setCellValue('I' . $row, $item['tahun']); // Tampilkan Tahun
            $sheet->setCellValue('J' . $row, $item['bulan']);
            $sheet->setCellValue('K' . $row, $item['usulan_wp']);
            $sheet->setCellValue('L' . $row, $item['realisasi_dp']);
            $sheet->setCellValue('M' . $row, $item['memo']);
            $row++;
        }

        // Set Header
        $filename = 'wpdp_bulanan_' . $this->tahunAnggaran . '_' . date('Ymd_His') . '.xlsx'; // Tambah tahun di nama file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function laporanTriwulan()
    {
        $model = new WpdpModel();
        $triwulan = $this->request->getGet('triwulan'); // Ambil input dari dropdown
        // Tahun hardcode 2025
        $tahun = $this->tahunAnggaran;

        // Default triwulan 1 jika belum dipilih
        $triwulan = $triwulan ?? '1';

        // Mapping triwulan ke bulan
        $bulanMap = [
            '1' => ['Januari', 'Februari', 'Maret'],
            '2' => ['April', 'Mei', 'Juni'],
            '3' => ['Juli', 'Agustus', 'September'],
            '4' => ['Oktober', 'November', 'Desember'],
        ];

        // Ambil bulan sesuai triwulan
        $bulanFilter = $bulanMap[$triwulan] ?? [];

        // Ambil data dari model berdasarkan bulan dan tahun 2025
        $data['rekap'] = $model
            ->select('wpdp_bulanan.*, p.nama_program, k.nama_kegiatan, kr.nama_kro, r.nama_ro, ko.nama_komponen, a.nama_akun, d.nama_detil') // Tambah select akun & detil
            ->where('wpdp_bulanan.tahun', $tahun) // Filter berdasarkan tahun 2025
            ->whereIn('wpdp_bulanan.bulan', $bulanFilter) // Tambahkan filter bulan
            ->join('detil d', 'd.id = wpdp_bulanan.detil_id') // Join ke detil
            ->join('akun a', 'a.id = d.akun_id') // Join ke akun
            ->join('komponen ko', 'ko.id = a.komponen_id') // Join ke komponen
            ->join('ro r', 'r.id = ko.ro_id') // Join ke ro
            ->join('kro kr', 'kr.id = r.kro_id') // Join ke kro
            ->join('kegiatan k', 'k.id = kr.kegiatan_id') // Join ke kegiatan
            ->join('program p', 'p.id = k.program_id') // Join ke program
            ->orderBy('p.nama_program, k.nama_kegiatan, kr.nama_kro, r.nama_ro, ko.nama_komponen, a.nama_akun, d.nama_detil, FIELD(wpdp_bulanan.bulan, "Januari", "Februari", "Maret", "April", "May", "June", "July", "August", "September", "October", "November", "December")') // Urutkan
            ->findAll(); // Menggunakan findAll() dari Model


        $data['triwulan'] = $triwulan;
        $data['tahun'] = $tahun; // Kirim tahun 2025 ke view
        // Daftar tahun yang tersedia tidak perlu diambil lagi
        // $data['available_years'] = $this->wpdpModel->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll();

        return view('wpdp/laporan_triwulanan', $data);
    }
    public function export_triwulan()
    {
         $triwulan = $this->request->getGet('triwulan'); // Ambil input dari dropdown
         // Tahun hardcode 2025
         $tahun = $this->tahunAnggaran;

         // Default triwulan 1 jika belum dipilih
         $triwulan = $triwulan ?? '1';

         // Mapping triwulan ke bulan
         $bulanMap = [
             '1' => ['Januari', 'Februari', 'Maret'],
             '2' => ['April', 'Mei', 'Juni'],
             '3' => ['Juli', 'Agustus', 'September'],
             '4' => ['Oktober', 'November', 'Desember'],
         ];

         // Ambil bulan sesuai triwulan
         $bulanFilter = $bulanMap[$triwulan] ?? [];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'Nama Aktivitas (RO)', // Ubah label
            'Kumulatif Usulan Anggaran/WP (Rp)',
            'Kumulatif Realisasi Anggaran/DP (Rp)',
            'Kumulatif Realisasi Penyelesaian Kegiatan (%)',
            'Kompilasi Kendala',
            'Kompilasi Solusi',
            'Nama Publikasi (ttd Kepala)',
            'Pendahuluan',
            'Pelaksanaan',
            'Penutup',
            'Lampiran',
            'Insight dalam Publikasi',
            'Nama Indikator di Website'
        ];

        // Tulis header ke Excel
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Ambil data dari database (filter tahun 2025)
        $db = \Config\Database::connect();
        $builder = $db->table('wpdp_bulanan wb');
        $builder->select('
        r.nama_ro as nama_aktivitas,
        SUM(wb.usulan_wp) as total_anggaran,
        SUM(wb.realisasi_dp) as total_realisasi,
        ROUND((SUM(wb.realisasi_dp) / NULLIF(SUM(wb.usulan_wp), 0)) * 100, 2) as persen_realisasi
    ');
        $builder->where('wb.tahun', $tahun); // Filter berdasarkan tahun 2025
        $builder->whereIn('wb.bulan', $bulanFilter); // Filter berdasarkan bulan triwulan
        $builder->join('detil d', 'd.id = wb.detil_id');
        $builder->join('akun a', 'a.id = d.akun_id');
        $builder->join('komponen ko', 'ko.id = a.komponen_id');
        $builder->join('ro r', 'r.id = ko.ro_id'); // Join sampai level RO

        $builder->groupBy('r.id, r.nama_ro'); // Group by ID dan nama RO
        $query = $builder->get();
        $results = $query->getResultArray();

        // Ambil Kendala dan Solusi secara terpisah per RO (filter tahun 2025)
        $kendalaSolusiData = [];
        if (!empty($results)) {
             // Ambil nama RO dari hasil rekap (untuk mencocokkan)
             $roNames = array_column($results, 'nama_aktivitas');

             $kendalaSolusiQuery = $db->table('wpdp_bulanan wb')
                 ->select('r.nama_ro, GROUP_CONCAT(DISTINCT ken.kendala SEPARATOR ", ") as kompilasi_kendala, GROUP_CONCAT(DISTINCT sol.solusi SEPARATOR ", ") as kompilasi_solusi')
                 ->join('ro r', 'r.id = wb.ro_id') // Join ke RO
                 ->join('kendala ken', 'ken.wpdp_bulanan_id = wb.id', 'left') // Join ke Kendala (LEFT JOIN agar RO tanpa kendala tetap muncul)
                 ->join('solusi sol', 'sol.wpdp_bulanan_id = wb.id', 'left') // Join ke Solusi (LEFT JOIN)
                 ->where('wb.tahun', $tahun) // Filter berdasarkan tahun 2025
                 ->whereIn('wb.bulan', $bulanFilter) // Filter berdasarkan bulan triwulan
                 ->groupBy('r.id, r.nama_ro')
                 ->get()->getResultArray();

             // Bentuk data kendala/solusi agar mudah dicari berdasarkan nama RO
             foreach($kendalaSolusiQuery as $ks) {
                 $kendalaSolusiData[$ks['nama_ro']] = [
                     'kendala' => $ks['kompilasi_kendala'],
                     'solusi' => $ks['kompilasi_solusi']
                 ];
             }
        }


        // Tulis ke Excel
        $row = 2;
        foreach ($results as $item) {
            $col = 'A';
            $namaRo = $item['nama_aktivitas']; // Nama RO

            // Ambil kendala dan solusi dari data terpisah
            $kompilasiKendala = $kendalaSolusiData[$namaRo]['kendala'] ?? '';
            $kompilasiSolusi = $kendalaSolusiData[$namaRo]['solusi'] ?? '';

            // Pastikan urutan kolom sesuai dengan SELECT di atas
            $sheet->setCellValue($col++ . $row, $item['nama_aktivitas']);
            $sheet->setCellValue($col++ . $row, $item['total_anggaran']);
            $sheet->setCellValue($col++ . $row, $item['total_realisasi']);
            $sheet->setCellValue($col++ . $row, $item['persen_realisasi']);
            $sheet->setCellValue($col++ . $row, $item['kendala']); // Masih mengambil dari kolom lama/tidak sesuai
            $sheet->setCellValue($col++ . $row, $item['solusi']); // Masih mengambil dari kolom lama/tidak sesuai
            $sheet->setCellValue($col++ . $row, $item['nama_publikasi']);
            $sheet->setCellValue($col++ . $row, ''); // Pendahuluan
            $sheet->setCellValue($col++ . $row, ''); // Pelaksanaan
            $sheet->setCellValue($col++ . $row, ''); // Penutup
            $sheet->setCellValue($col++ . $row, ''); // Lampiran
            $sheet->setCellValue($col++ . $row, ''); // Insight
            $sheet->setCellValue($col++ . $row, ''); // Indikator Website
            $row++;
        }

        // Output file Excel
        $filename = 'rekap_triwulan_' . $tahun . '_' . $triwulan . '_' . date('Ymd_His') . '.xlsx'; // Tambah tahun dan triwulan di nama file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
// Metode BARU untuk Laporan Komprehensif
public function laporanKomprehensif()
{
    // Ambil data laporan dari Model (default tahun 2025)
    // Anda bisa menambahkan logika untuk memilih tahun berdasarkan input pengguna jika diperlukan
    $tahun = 2025; // Atau ambil dari request get jika ada filter tahun
    $reportData = $this->wpdpModel->getComprehensiveReportData($tahun);

    // Hitung persentase capaian di Controller sebelum dikirim ke View
    // Persentase Capaian Kegiatan (%) = (Total Realisasi DP per RO / Total Pagu Awal Detil per RO) * 100
    foreach ($reportData as &$row) {
        $totalRealisasi = (float) $row['total_realisasi_ro'];
        $totalPaguAwal = (float) $row['total_pagu_awal_detil_ro'];

        if ($totalPaguAwal > 0) {
            $row['persentase_capaian'] = round(($totalRealisasi / $totalPaguAwal) * 100, 2);
        } else {
            $row['persentase_capaian'] = 0; // Hindari division by zero
        }

        // Format angka untuk tampilan
         $row['total_usulan_ro_formatted'] = number_format($row['total_usulan_ro'], 0, ',', '.');
         $row['total_realisasi_ro_formatted'] = number_format($row['total_realisasi_ro'], 0, ',', '.');
         $row['total_pagu_awal_detil_ro_formatted'] = number_format($row['total_pagu_awal_detil_ro'], 0, ',', '.');

         // Untuk field teks yang diagregasi (kendala, solusi, dll), pastikan tidak null
         $row['rekap_memo'] = $row['rekap_memo'] ?? '-';
         $row['rekap_kendala'] = $row['rekap_kendala'] ?? '-';
         $row['rekap_solusi'] = $row['rekap_solusi'] ?? '-';
         $row['rekap_rencana_tindak_lanjut'] = $row['rekap_rencana_tindak_lanjut'] ?? '-';
         $row['rekap_batas_tindak_lanjut'] = $row['rekap_batas_tindak_lanjut'] ?? '-';

    }
    unset($row); // Unset reference after loop


    $data = [
        'reportData' => $reportData,
        'tahun' => $tahun,
        'title' => 'Laporan WPDP Komprehensif Tahun ' . $tahun
    ];

    return view('wpdp/laporan_komprehensif', $data); // Load view baru
}
}
