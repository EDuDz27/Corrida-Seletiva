<?php

require_once 'config/database.php';
require_once 'app/models/AdminModel.php';

class AdminController
{
    private $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        session_start();
        if (!isset($_SESSION['email'])) {
            session_abort();
            header("Location: admin_form");
            exit();
        }
        
        header("Location: dashboard");
    }

    public function showForm()
    {

        session_start();
        if (!isset($_SESSION['email'])) {
            session_abort();
            include 'app/views/login_admin.html';
            echo "<script>document.getElementById('erro-login').style.display = 'none';</script>";
        }
        else{
            header("Location: admin");
        }

    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->adminModel->email = $_POST['email'];
            $this->adminModel->senha = $_POST['senha'];

            // Tenta fazer o login
            if ($this->adminModel->login()) {
                header("Location: admin"); // Redireciona para o perfil
                exit();
            } else {
                include 'app/views/login_admin.html'; // Recarrega o formulário com a mensagem de erro
                echo "<script>document.getElementById('erro-login').style.display = 'block';</script>";
            }
        } else {
            // Se o método não for POST, exibe o formulário de login
            $this->index();
        }
    }

    public function logout()
    {
        $this->adminModel->logout();
        $this->showForm();
    }

}
