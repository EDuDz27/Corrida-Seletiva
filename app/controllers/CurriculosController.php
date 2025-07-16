<?php

require_once 'config/database.php';
require_once 'app/models/CurriculosModel.php';

class CurriculosController
{
    private $adminModel;

    public function __construct()
    {
        $this->curriculosModel = new CurrciulosModel();
    }

    public function index()
    {
        session_start();
        if (!isset($_SESSION['email'])) {
            session_abort();
            header("Location: admin");
            exit();
        }

        include 'app/views/curriculos.html';

    }
}
