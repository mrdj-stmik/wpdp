<?php

namespace App\Models;

use CodeIgniter\Model;

class PmoModel extends Model
{
    protected $table            = 'pmo'; // disesuaikan
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama'];
}
