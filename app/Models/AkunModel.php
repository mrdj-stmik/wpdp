<?php

namespace App\Models;

use CodeIgniter\Model;

class AkunModel extends Model
{
    protected $table            = 'akun'; // Nama tabel di database
    protected $primaryKey       = 'id'; // Nama kolom primary key
    protected $useAutoIncrement = true; // Apakah primary key auto increment
    protected $returnType       = 'array'; // Tipe data hasil query (array atau object)
    protected $useSoftDeletes   = false; // Gunakan soft delete (karena ada kolom 'deleted_at')
    protected $protectFields    = true;
    protected $allowedFields    = ['komponen_id', 'kode_akun', 'nama_akun']; // Kolom yang diizinkan diisi saat insert/update

    // Dates
    protected $useTimestamps = false; // Disable timestamp karena tabel tidak punya created_at/updated_at
    protected $dateFormat    = 'datetime'; // Format tanggal
    protected $createdField  = 'created_at'; // Nama kolom created_at
    protected $updatedField  = 'updated_at'; // Nama kolom updated_at
    protected $deletedField  = 'deleted_at'; // Nama kolom deleted_at

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
    // public function getAkunByKomponen($komponenId)
    // {
    //     return $this->where('komponen_id', $komponenId)->findAll();
    // }
}