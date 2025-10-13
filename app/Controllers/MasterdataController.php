<?php

namespace App\Controllers;
use App\Models\ProgramModel;
use App\Models\KegiatanModel;
use App\Models\RoModel;
use App\Models\KroModel;
use App\Models\ViewPaguPerRoModel;

class MasterdataController extends BaseController
{
    // Gunakan properti untuk model jika sering diakses
    protected $programModel;
    protected $kegiatanModel;
    protected $roModel;
    protected $kroModel; // Tambahkan properti KroModel

    public function __construct()
    {
        $this->programModel = new ProgramModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->roModel = new RoModel();
        $this->kroModel = new KroModel(); // Inisialisasi KroModel
        $this->viewPaguPerRoModel = new \App\Models\ViewPaguPerRoModel(); // Inisialisasi model baru
    }
    
    // Endpoint AJAX untuk mendapatkan Kegiatan berdasarkan Program
    // URL di JS: <?= base_url('masterdata/kegiatan'}
    public function getKegiatan($programId)
    {
        $kegiatans = $this->kegiatanModel->where('program_id', $programId)->findAll();
        return $this->response->setJSON($kegiatans);
    }

    // Endpoint AJAX untuk mendapatkan RO berdasarkan Kegiatan
    // URL di JS: <?= base_url('masterdata/ro-list') 
    // Perbaiki logika query agar join ke KRO
    public function getRoList($kegiatanId)
    {
        $db = \Config\Database::connect(); // Gunakan Query Builder untuk join
        $builder = $db->table('ro r');
        $builder->select('r.id, r.kode_ro, r.nama_ro');
        $builder->join('kro kr', 'kr.id = r.kro_id'); // Join ke tabel KRO
        $builder->where('kr.kegiatan_id', $kegiatanId); // Filter berdasarkan ID Kegiatan di tabel KRO
        $ros = $builder->get()->getResultArray(); // Ambil hasil sebagai array

        return $this->response->setJSON($ros);
    }

    // --- Metode BARU: Endpoint AJAX untuk mendapatkan RO berdasarkan Program ---
    // URL di JS: <?= base_url('masterdata/ro-list-by-program') 
    public function index()
    {
        $data['programs'] = $this->programModel->findAll();
        return view('masterdata/ro_index', $data);
    }
    
public function totalPagu()
{
    // Ambil data dari view view_pagu_per_ro
    $data['totalPagu'] = $this->viewPaguPerRoModel->findAll();

    // Kirim data ke view
    return view('masterdata/total_pagu', $data);
}
}
