<?php

namespace App\Models;

use CodeIgniter\Model;

class KetuaTimModel extends Model
{
    protected $table            = 'ketua_tim'; // pastikan ini sesuai nama tabel
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama', 'pmo_id'];
}
