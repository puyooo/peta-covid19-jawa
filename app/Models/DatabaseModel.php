<?php

namespace App\Models;

use CodeIgniter\Model;

class DatabaseModel extends Model {

    protected $table = 'provinsi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'nama_provinsi', 'polygon', 'posisi_popup'];

    public function getProvinsiById($id) {        
        return $this->asObject()
                        ->where(['id' => $id])
                        ->first();
    }
    
    public function getAll() {
        return $this->asObject()->findAll();
    }
}