<?php

namespace App\Models;

use CodeIgniter\Model;
// Model ini merupakan bagian dari aplikasi SIPPEKA
// Aplikasi ini dibuat oleh Darojatun - BPS Tasikmalaya

class WpdpModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'wpdp_bulanan'; // Pastikan nama tabel benar
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; // Atau 'object'
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    // Tambahkan 'rencana_tindak_lanjut' dan 'batas_tindak_lanjut' ke allowedFields
    // Hapus nama_kegiatan, aktivitas, realisasi_persen karena bukan kolom langsung di tabel ini
    protected $allowedFields = [
        'program_id',
        'kegiatan_id',
        'kro_id',
        'ro_id',
        'komponen_id',
        'akun_id',
        'detil_id',
        'bulan',
        'tahun',
        // 'nama_kegiatan', // Hapus karena ini dari tabel kegiatan
        // 'aktivitas', // Hapus karena ini kemungkinan nama_ro atau nama_detil dari tabel lain
        'usulan_wp',
        'realisasi_dp',
        // 'realisasi_persen', // Hapus karena ini hasil perhitungan
        'memo',
        'rencana_tindak_lanjut', // Ini kolom di tabel wpdp_bulanan
        'batas_tindak_lanjut' // Ini kolom di tabel wpdp_bulanan
    ];
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    // Metode untuk mengambil tahun-tahun unik dari data WPDP
    public function getAvailableYears()
    {
        return $this->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll();
    }


    public function getTriwulan($bulanList) // Ganti nama parameter agar lebih jelas
    {
        // Join melalui hierarki baru untuk mendapatkan nama-nama level
        return $this->select('wpdp_bulanan.*, p.nama_program, k.nama_kegiatan, kr.nama_kro, r.nama_ro, ko.nama_komponen, a.nama_akun, d.nama_detil') // Tambah nama akun & detil
            ->join('detil d', 'd.id = wpdp_bulanan.detil_id') // Join ke detil
            ->join('akun a', 'a.id = d.akun_id') // Join ke akun
            ->join('komponen ko', 'ko.id = a.komponen_id') // Join ke komponen
            ->join('ro r', 'r.id = ko.ro_id') // Join ke ro
            ->join('kro kr', 'kr.id = r.kro_id') // Join ke kro
            ->join('kegiatan k', 'k.id = kr.kegiatan_id') // Join ke kegiatan
            ->join('program p', 'p.id = k.program_id') // Join ke program
            ->whereIn('wpdp_bulanan.bulan', $bulanList) // Tambahkan alias tabel
            ->findAll();
    }

    public function getRekapPerTriwulan()
    {
        // Join melalui hierarki baru, agregasi dan group by per RO dan bulan
        return $this->select('p.nama_program, k.nama_kegiatan, kr.nama_kro, r.nama_ro, // Pilih nama level agregasi
                             SUM(wpdp_bulanan.usulan_wp) AS total_usulan,
                             SUM(wpdp_bulanan.realisasi_dp) AS total_realisasi,
                             wpdp_bulanan.bulan') // Masukkan bulan dalam select
            ->join('detil d', 'd.id = wpdp_bulanan.detil_id') // Join ke detil
            ->join('akun a', 'a.id = d.akun_id') // Join ke akun
            ->join('komponen ko', 'ko.id = a.komponen_id') // Join ke komponen
            ->join('ro r', 'r.id = ko.ro_id') // Join ke ro (level agregasi)
            ->join('kro kr', 'kr.id = r.kro_id') // Join ke kro
            ->join('kegiatan k', 'k.id = kr.kegiatan_id') // Join ke kegiatan
            ->join('program p', 'p.id = k.program_id') // Join ke program
            // Group by level agregasi dan bulan
            ->groupBy('p.nama_program, k.nama_kegiatan, kr.nama_kro, r.nama_ro, wpdp_bulanan.bulan')
            ->orderBy('wpdp_bulanan.bulan') // Urutkan berdasarkan bulan
            ->findAll();
    }

    // Metode baru untuk mengambil data komprehensif untuk laporan
    public function getComprehensiveReportData($tahun = 2025)
{
    return $this->db->table('wpdp_bulanan wb') // Alias untuk tabel utama
        ->select('
            keg.sasaran,
            keg.indikator,
            keg.nama_pmo,
            kro.nama_kro,
            ro.kode_ro,
            ro.nama_ro,
            ro.nama_ketua_tim,
            SUM(wb.usulan_wp) AS total_usulan_ro,
            SUM(wb.realisasi_dp) AS total_realisasi_ro,
            SUM(det.pagu_anggaran_awal) AS total_pagu_awal_detil_ro,
            GROUP_CONCAT(DISTINCT wb.memo SEPARATOR "; ") AS rekap_memo,
            GROUP_CONCAT(DISTINCT ken.kendala SEPARATOR "; ") AS rekap_kendala,
            GROUP_CONCAT(DISTINCT sol.solusi SEPARATOR "; ") AS rekap_solusi,
            GROUP_CONCAT(DISTINCT wb.rencana_tindak_lanjut SEPARATOR "; ") AS rekap_rencana_tindak_lanjut,
            GROUP_CONCAT(DISTINCT wb.batas_tindak_lanjut SEPARATOR "; ") AS rekap_batas_tindak_lanjut
        ')
        ->join('detil det', 'det.id = wb.detil_id')
        ->join('akun ak', 'ak.id = det.akun_id')
        ->join('komponen kom', 'kom.id = ak.komponen_id')
        ->join('ro ro', 'ro.id = kom.ro_id')
        ->join('kro kro', 'kro.id = ro.kro_id')
        ->join('kegiatan keg', 'keg.id = kro.kegiatan_id')
        ->join('program prog', 'prog.id = keg.program_id')
        ->join('wpdp_kendala ken', 'ken.wpdp_bulanan_id = wb.id')
        ->where('wb.tahun', $tahun)
        ->join('wpdp_solusi sol', 'sol.wpdp_bulanan_id = wb.id', 'LEFT')
        ->where('wb.tahun', $tahun)
        ->groupBy('prog.nama_program, keg.nama_kegiatan, keg.nama_pmo, kro.nama_kro, ro.kode_ro, ro.nama_ro, ro.nama_ketua_tim')
        ->orderBy('prog.nama_program, keg.nama_kegiatan, kro.nama_kro, ro.kode_ro')
        ->get() // Menggunakan get() untuk mendapatkan hasil dalam bentuk object atau array
        ->getResultArray(); // Mengubah hasil ke dalam bentuk array
}

    // Metode untuk mengambil Kendala per WPDP ID (Jika Kendala di tabel terpisah)
    // Jika Kendala di tabel wpdp_bulanan, metode ini tidak lagi relevan untuk laporan agregat per RO
    // Tapi tetap relevan untuk form edit WPDP bulanan tunggal
    public function getKendalaByWpdpId($wpdpId)
    {
         // Asumsi KendalaModel ada dan terhubung ke tabel kendala
         $kendalaModel = new \App\Models\KendalaModel(); // Gunakan fully qualified name atau import di atas
         return $kendalaModel->where('wpdp_bulanan_id', $wpdpId)->findAll();
    }

    // Metode untuk mengambil Solusi per WPDP ID (Jika Solusi di tabel terpisah)
    // Jika Solusi di tabel wpdp_bulanan, metode ini tidak lagi relevan untuk laporan agregat per RO
    // Tapi tetap relevan untuk form edit WPDP bulanan tunggal
    public function getSolusiByWpdpId($wpdpId)
    {
         // Asumsi SolusiModel ada dan terhubung ke tabel solusi
         $solusiModel = new \App\Models\SolusiModel(); // Gunakan fully qualified name atau import di atas
         return $solusiModel->where('wpdp_bulanan_id', $wpdpId)->findAll();
    }


     // Metode untuk mengambil pagu awal detil berdasarkan detil ID (untuk perhitungan di controller)
     public function getPaguAwalDetil($detilId)
     {
         // Asumsi DetilModel ada dan terhubung ke tabel detil
         $detilModel = new \App\Models\DetilModel(); // Gunakan fully qualified name atau import di atas
         $detil = $detilModel->find($detilId);
         return $detil['pagu_awal'] ?? 0;
     }

     // Metode untuk menghitung total realisasi bulan sebelumnya untuk detil dan tahun tertentu
     public function getTotalRealisasiBulanSebelumnya($detilId, $bulan, $tahun)
     {
         $bulanList = [
             'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
             'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
             'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
         ];

         $bulanIndex = $bulanList[$bulan] ?? null;

         if ($bulanIndex === null || $bulanIndex === 1) {
             return 0; // Tidak ada bulan sebelumnya atau bulan Januari
         }

         // Ambil bulan-bulan sebelum bulan saat ini
         $bulanSebelumnyaList = array_keys(array_filter($bulanList, fn($index) => $index < $bulanIndex));

         // Sum realisasi_dp untuk detil, tahun, dan bulan-bulan sebelumnya
         $totalRealisasi = $this->selectSum('realisasi_dp')
             ->where('detil_id', $detilId)
             ->where('tahun', $tahun)
             ->whereIn('bulan', $bulanSebelumnyaList)
             ->get()
             ->getRow();

         return $totalRealisasi->realisasi_dp ?? 0;
     }

     /**
     * Mengambil total nilai RPD (usulan_wp) untuk semua bulan dalam tahun tertentu.
     */
     public function getTotalRpdSetahun($tahun)
    {
        return $this->db->table($this->table)
                        ->selectSum('usulan_wp') // Mengambil total dari kolom usulan_wp
                        ->where('tahun', $tahun)
                        ->get()
                        ->getRow()
                        ->usulan_wp ?? 0; // Mengembalikan 0 jika tidak ada data
    }
     
     // Metode AJAX untuk mendapatkan anggaran (pagu awal atau sisa)
     // Metode ini seharusnya ada di WpdpController, bukan di Model
     // public function getAnggaranByDetilMonthYear($detilId, $bulan) { ... }
     public function getTotalRealisasiSetahun($tahun)
     {
         return $this->db->table($this->table)
                         ->selectSum('realisasi_dp') // Ganti 'nilai_realisasi' jika nama kolom berbeda
                         ->where('tahun', $tahun)
                         ->get()
                         ->getRow()
                         ->realisasi_dp ?? 0; // Mengembalikan 0 jika tidak ada data
     }

/**
     * Mengambil data RPD (usulan_wp) per bulan untuk tahun tertentu.
     */
    
public function getMonthlyRpd($tahun)
{
    // Mapping nama bulan ke indeks bulan
    $bulanMapping = [
        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
    ];

    // Query untuk mengambil total usulan_wp per bulan
    $builder = $this->db->table($this->table);
    $builder->select('bulan, SUM(usulan_wp) as total_rpd'); // Sum kolom usulan_wp
    $builder->where('tahun', $tahun); // Filter berdasarkan tahun
    $builder->groupBy('bulan');
    $builder->orderBy('bulan', 'ASC'); // Urutkan berdasarkan bulan

    $results = $builder->get()->getResultArray();

    // Format data untuk grafik
    $formattedData = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], // Label bulan
        'rpd_data' => array_fill(0, 12, 0) // Inisialisasi array dengan 0 untuk 12 bulan
    ];

    // Isi data yang ada dari hasil query
    foreach ($results as $row) {
        // Jika bulan disimpan sebagai nama (Januari, Februari, dll.)
        $bulanIndex = $bulanMapping[$row['bulan']] ?? null; // Konversi nama bulan ke indeks
        if ($bulanIndex !== null) {
            $formattedData['rpd_data'][$bulanIndex - 1] = (float)$row['total_rpd'];
        }
    }

    return $formattedData;
}


/**
     * Mengambil data Realisasi (dari tabel WPDP) per bulan untuk tahun tertentu.
     */
    
public function getMonthlyRealisasi($tahun)
{
    // Mapping nama bulan ke indeks bulan
    $bulanMapping = [
        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
    ];

    // Query untuk mengambil total Realisasi per bulan
    $builder = $this->db->table($this->table);
    $builder->select('bulan, SUM(realisasi_dp) as total_realisasi');
    $builder->where('tahun', $tahun);
    $builder->groupBy('bulan');

    $results = $builder->get()->getResultArray();

    // Format data untuk grafik
    $formattedData = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], // Label bulan
        'realisasi_data' => array_fill(0, 12, 0) // Inisialisasi array dengan 0 untuk 12 bulan
    ];

    // Isi data yang ada dari hasil query
    foreach ($results as $row) {
        $bulanIndex = $bulanMapping[$row['bulan']] ?? null; // Konversi nama bulan ke indeks
        if ($bulanIndex !== null) {
            $formattedData['realisasi_data'][$bulanIndex - 1] = (float)$row['total_realisasi'];
        }
    }

    return $formattedData;
}

}
