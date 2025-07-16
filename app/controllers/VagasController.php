<?php

require_once 'config/database.php';
require_once 'app/models/VagasModel.php';

class VagasController
{
    private $adminModel;

    public function __construct()
    {
        $this->vagasModel = new VagasModel();
    }

    public function index()
    {
        session_start();
        if (!isset($_SESSION['email'])) {
            session_abort();
            header("Location: admin");
            exit();
        }

        include 'app/views/vagas.html';

    }
}
