<?php

namespace App\Models;

use CodeIgniter\Model;

class DetilRevisiModel extends Model
{
    // Nama tabel di database
    protected $table = 'detil_revisi_temp';
    // Primary key dari tabel
    protected $primaryKey = 'id';
    // Field yang diizinkan untuk diisi (untuk operasi insert/update)
    protected $allowedFields = [
        'detil_id', 'revisi_ke', 'volume', 'harga_satuan', 'jumlah_biaya', 'catatan', 'sd_cp', 'tanggal_revisi'
    ];
    
    // Disable automatic timestamps since we don't have created_at/updated_at columns
    protected $useTimestamps = false;

    /**
     * Mengambil semua data dari tabel detil_revisi.
     *
     * @return array Array of objects/arrays representing all rows.
     */
    public function getAll()
    {
        return $this->findAll();
    }

    /**
     * Mengambil data detil revisi berdasarkan primary key.
     *id_detil
     * @param int $id_detil_revisi Primary key dari baris yang ingin diambil.
     * @return array|null Array representing the row, or null if not found.
     */
    public function getById($id_detil_revisi)
    {
        return $this->find($id_detil_revisi);
    }

    /**
     * Mengambil data detil revisi berdasarkan id_detil dan nomor revisi.
     * Berguna untuk mencari data spesifik item detil pada revisi tertentu.
     *
     * @param int $id_detil ID detil dari tabel 'detil'.
     * @param int $revisi_ke Nomor revisi (1, 2, 3, dst.).
     * @return array|null Array representing the row, or null if not found.
     */
    public function getByDetilAndRevisi($id_detil, $revisi_ke)
    {
        return $this->where(['detil_id' => $id_detil, 'revisi_ke' => $revisi_ke])->first();
    }

    /**
     * Mengambil semua data detil revisi untuk satu item detil tertentu.
     * Diurutkan berdasarkan nomor revisi.
     *
     * @param int $id_detil ID detil dari tabel 'detil'.
     * @return array Array of objects/arrays representing the revision history for the detil item.
     */
    public function getRevisionsByDetilId($id_detil)
    {
        return $this->where('detil_id', $id_detil)
                    ->orderBy('revisi_ke', 'ASC')
                    ->findAll();
    }

    /**
     * Menyimpan data baru ke tabel detil_revisi.
     *
     * @param array $data Data yang akan disimpan (sesuai dengan $allowedFields).
     * @return bool True jika berhasil, False jika gagal.
     */
    public function insertData($data)
    {
        // Pastikan kolom tanggal_revisi terisi jika tidak ada di $data
        if (!isset($data['tanggal_revisi'])) {
            $data['tanggal_revisi'] = date('Y-m-d H:i:s');
        }
        return $this->insert($data);
    }

    /**
     * Memperbarui data di tabel detil_revisi berdasarkan primary key.
     *
     * @param int $id_detil_revisi Primary key dari baris yang akan diperbarui.
     * @param array $data Data yang akan diperbarui (sesuai dengan $allowedFields).
     * @return bool True jika berhasil, False jika gagal.
     */
    public function updateData($id_detil_revisi, $data)
    {
        return $this->update($id_detil_revisi, $data);
    }

    /**
     * Menghapus data dari tabel detil_revisi berdasarkan primary key.
     *
     * @param int $id_detil_revisi Primary key dari baris yang akan dihapus.
     * @return bool True jika berhasil, False jika gagal.
     */
    public function deleteData($id_detil_revisi)
    {
        return $this->delete($id_detil_revisi);
    }

    /**
     * Menghapus semua data revisi untuk item detil tertentu.
     * Berguna jika item detil dihapus.
     *
     * @param int $id_detil ID detil dari tabel 'detil'.
     * @return bool True jika berhasil, False jika gagal.
     */
    public function deleteByDetilId($id_detil)
    {
        return $this->where('detil_id', $id_detil)->delete();
    }

    /**
     * Mengambil revisi terakhir untuk item detil tertentu.
     *
     * @param int $id_detil ID detil dari tabel 'detil'.
     * @return array|null Array representing the last revision, or null if not found.
     */
    public function getLastRevision($id_detil)
    {
        return $this->where('detil_id', $id_detil)
                    ->orderBy('revisi_ke', 'DESC')
                    ->first();
    }
}