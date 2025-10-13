<?php namespace App\Models;

use CodeIgniter\Model;

class ViewPaguPerRoModel extends Model
{
    protected $table = 'view_pagu_per_ro'; // Nama view di database
    protected $returnType = 'array'; // Mengembalikan data sebagai array
    protected $allowedFields = []; // Kosongkan jika hanya untuk membaca data
}