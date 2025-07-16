<?php

class Database {
    private $host = "localhost";
    private $db_name = "corrida_seletiva";
    private $username = "root"; 
    private $password = ""; 
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Criação da conexão com o banco de dados
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            // Logando o erro em vez de exibir
            error_log("Erro de conexão: " . $exception->getMessage());
            echo "Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.";
        }
        
        return $this->conn;
    }
}

?>