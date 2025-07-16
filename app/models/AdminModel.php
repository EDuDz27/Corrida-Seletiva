<?php

class AdminModel
{
    private $conn;
    public $email;
    public $senha;
    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function login()
    {

        session_start();
        if (isset($_SESSION['email'])) {
            return true;
        }

        $query = "SELECT email, senha FROM admin WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($this->senha, $row['senha'])) {
                $_SESSION['email'] = $row['email'];
                return true;
            }
        }

        return false;
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        return true;
    }

}
