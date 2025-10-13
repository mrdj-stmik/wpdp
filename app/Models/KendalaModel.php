<?php namespace App\Models;

use CodeIgniter\Model;

class KendalaModel extends Model
{
    protected $table = 'wpdp_kendala';
    protected $primaryKey = 'id';
    protected $allowedFields = ['wpdp_bulanan_id', 'kendala'];
}
