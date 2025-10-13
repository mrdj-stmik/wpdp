<?php

namespace App\Models;

use CodeIgniter\Model;

class AktivitasModel extends Model
{
    protected $table = 'wpdp_aktivitas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['wpdp_bulanan_id', 'aktivitas'];
    
}
