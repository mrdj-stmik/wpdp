<?php

namespace App\Models;

use CodeIgniter\Model;

class DetilModel extends Model
{
    protected $table            = 'detil'; // Nama tabel di database
    protected $primaryKey       = 'id'; // Nama kolom primary key
    protected $useAutoIncrement = true; // Apakah primary key auto increment
    protected $returnType       = 'array'; // Tipe data hasil query (array atau object)
    protected $useSoftDeletes   = false; // Gunakan soft delete (karena ada kolom 'deleted_at')
    protected $protectFields    = true;
    protected $allowedFields    = ['akun_id', 'kode_detil', 'nama_detil', 'pagu_anggaran_awal', 'pagu_revisi_terakhir', 'lock_pagu']; // Kolom yang diizinkan diisi saat insert/update

    // Dates
    protected $useTimestamps = false; // Disable timestamp karena tabel tidak punya created_at/updated_at
    protected $dateFormat    = 'datetime'; // Format tanggal
    protected $updatedField  = 'updated_at'; // Nama kolom updated_at
    protected $deletedField  = 'deleted_at'; // Nama kolom deleted_at


        public function getDetilWithAkun()
    {
        return $this->select('detil.*, akun.nama_akun')
                    ->join('akun', 'akun.id = detil.akun_id', 'left')
                    ->findAll();
    }

        public function getDetilWithRO()
    {
        return $this->select('detil.*, ro.nama_ro')
                    ->join('akun', 'akun.id = detil.akun_id', 'left')
                    ->join('komponen', 'komponen.id = akun.komponen_id', 'left')
                    ->join('ro', 'ro.id = komponen.ro_id', 'left')
                    ->findAll();
    }

        public function getDetilWithROAndAkun()
    {
        return $this->select('detil.*, ro.nama_ro, akun.nama_akun')
                    ->join('akun', 'akun.id = detil.akun_id', 'left')
                    ->join('komponen', 'komponen.id = akun.komponen_id', 'left')
                    ->join('ro', 'ro.id = komponen.ro_id', 'left')
                    ->findAll();
    }
    // Validation (opsional, bisa ditambahkan nanti)
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // Callbacks (opsional, bisa ditambahkan nanti)
    // protected $allowCallbacks = true;
    // protected $beforeInsert   = [];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];

    // Metode kustom jika diperlukan, contoh:
    // public function getDetilByAkun($akunId)
    // {
    //     return $this->where('akun_id', $akunId)->findAll();
    // }
    // Metode untuk mendapatkan pagu revisi terakhir berdasarkan ID Detil
    public function getPaguRevisiTerakhirByDetilId($detilId)
    {
        $detil = $this->find($detilId);
        return $detil['pagu_revisi_terakhir'] ?? 0; // Return 0 jika detil tidak ditemukan atau pagu NULL
    }

    

}