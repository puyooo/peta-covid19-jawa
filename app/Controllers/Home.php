<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DatabaseModel;

class Home extends Controller {

    public function index() {
        $databaseModel = new DatabaseModel();
        
        //ambil data dari api data.covid19.go.id
        $api_covid = 'https://data.covid19.go.id/public/api/prov.json';        
        $data_covid = json_decode(file_get_contents($api_covid), true); //file json dari api di convert ke array

        //data untuk dikirim ke View home.php
        $data_home = [
            'provinsi' => $databaseModel->getAll(), //ambil data dari database
            'data_covid' => $data_covid
        ];

        $data_header = [
            'title' => 'COVID-19 di Pulau Jawa'
        ];

        //menampilkan halaman website
        echo view('template/header', $data_header);
        echo view('/home', $data_home); //mengirim data database dan api json
        echo view('template/footer');
    }
}