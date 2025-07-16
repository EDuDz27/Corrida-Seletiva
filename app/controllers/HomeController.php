<?php

require_once 'config/database.php';
require_once 'app/models/CurriculosModel.php';

class HomeController
{
    public function index()
    {
        include 'app/views/home.html';
    }

    public function cadastrarCurriculo()
    {
        $curriculosModel = new CurriculosModel();
        $curriculosModel->cadastrarCurriculo();
    }
}

