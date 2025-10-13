<?php

namespace App\Models;

use CodeIgniter\Model;

class SolusiModel extends Model
{
    protected $table = 'wpdp_solusi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'wpdp_bulanan_id',
        'solusi'
    ];

    
}
