<?php

require_once 'config/database.php';
require_once 'app/models/CurriculosModel.php';
require_once 'app/models/AnaliseIAModel.php';

class CurriculosController
{
    private $curriculosModel;
    private $analiseIAModel;

    public function __construct()
    {
        $this->curriculosModel = new CurriculosModel();
        $this->analiseIAModel = new AnaliseIAModel();
    }

    public function index()
    {
        session_start();
        if (!isset($_SESSION['email'])) {
            session_abort();
            header("Location: admin");
            exit();
        }
        $dados = $this->analiseIAModel->buscarCurriculosComPontuacao();
        include 'app/views/curriculos.html';
    }

    public function cadastrarCurriculo() {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $cep = $_POST['cep'] ?? '';
        $uf = $_POST['uf'] ?? '';
        $rua = $_POST['rua'] ?? '';
        $bairro = $_POST['bairro'] ?? '';
        $cidade = $_POST['cidade'] ?? '';
        $numero = $_POST['numero'] ?? '';
        $complemento = $_POST['complemento'] ?? '';
        $curriculo = $_FILES['curriculo'] ?? null;
        $this->curriculosModel->cadastrarCurriculo($nome, $email, $telefone, $cep, $uf, $rua, $bairro, $cidade, $numero, $complemento, $curriculo);
    }
}
