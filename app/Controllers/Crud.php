<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DatabaseModel;

class Crud extends Controller {

    public function index() {
        $databaseModel = new DatabaseModel();
        
        $data_crud = [
            'provinsi' => $databaseModel->getAll(), //ambil data dari database
        ];

        $data_header = [
            'title' => 'CRUD'
        ];

        //menampilkan halaman website
        echo view('template/header', $data_header);
        echo view('/crud', $data_crud); //mengirim data database dan api json
        echo view('template/footer');
    }
    
    public function savedata() {
        $databaseModel = new DatabaseModel();
        
        $id = $this->request->getVar('id');
        $nama_provinsi = $this->request->getVar('nama_provinsi');
        $polygon = $this->request->getVar('polygon');
        $posisi_popup = $this->request->getVar('posisi_popup');
        
        $data = [
            'nama_provinsi' => $nama_provinsi,
            'polygon' => $polygon,
            'posisi_popup' => $posisi_popup
        ];
        
        if (empty($databaseModel->getProvinsiById($id))) {
            $databaseModel->insert($data, false);            
        } else {
            $databaseModel->update($id, $data);
        }
        
        return redirect()->to(site_url('crud'));
    }
    
    public function deletedata($id) {
        $databaseModel = new DatabaseModel();
        
        $databaseModel->delete($id);
        
        return redirect()->to(site_url('crud'));
    }
}