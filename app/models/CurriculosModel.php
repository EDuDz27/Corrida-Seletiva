<?php

class CurrciulosModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }



}
