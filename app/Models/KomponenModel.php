<?php

namespace App\Models;

use CodeIgniter\Model;

class KomponenModel extends Model
{
    protected $DBGroup          = 'default';  // Tentukan grup database jika lebih dari satu
    protected $table            = 'komponen'; // Nama tabel, pastikan sudah benar
    protected $primaryKey       = 'id';       // Tentukan primary key
    protected $useAutoIncrement = true;       // Pastikan id auto increment
    protected $returnType       = 'array';    // Bisa diubah menjadi 'object' jika dibutuhkan
    protected $useSoftDeletes   = false;      // Tentukan jika menggunakan soft deletes
    protected $protectFields    = true;       // Lindungi field dari manipulasi langsung
    protected $allowedFields    = ['ro_id', 'kode_komponen', 'nama_komponen']; // Kolom yang bisa diisi

    // Dates
    protected $useTimestamps    = false;       // Tentukan jika menggunakan timestamps (created_at, updated_at)
    protected $dateFormat       = 'datetime';  // Format tanggal, ubah jika diperlukan
    protected $createdField     = 'created_at'; // Nama kolom untuk created_at
    protected $updatedField     = 'updated_at'; // Nama kolom untuk updated_at
    protected $deletedField     = 'deleted_at'; // Nama kolom untuk deleted_at, jika menggunakan soft deletes

    // Validation
    protected $validationRules      = [];  // Atur aturan validasi jika diperlukan
    protected $validationMessages   = [];  // Atur pesan validasi jika diperlukan
    protected $skipValidation       = false; // Tentukan apakah validasi ingin dilewati
    protected $cleanValidationRules = true;  // Tentukan untuk membersihkan aturan validasi

    // Callbacks (Jika diperlukan)
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
