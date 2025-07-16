<?php

class CurriculosModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function cadastrarCurriculo()
    {
        
        
    }

}
