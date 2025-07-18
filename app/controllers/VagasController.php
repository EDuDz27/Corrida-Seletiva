<?php

require_once 'config/database.php';
require_once 'app/models/VagasModel.php';

class VagasController
{
    private $vagasModel;

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

    public function cadastrarVaga(){
        $nome = $_POST['titulo'] ?? '';
        $area = $_POST['area'] ?? '';
        $localizacao = $_POST['local'] ?? '';
        $tipo_de_contrato = $_POST['contrato'] ?? '';
        $nivel_da_vaga = $_POST['nivel'] ?? '';
        $salario = $_POST['salario'] ?? '';
        $beneficios = $_POST['beneficios'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $requisitos_obrigatorios = $_POST['requisitos'] ?? '';
        $diferenciais = $_POST['diferenciais'] ?? '';
        $this->vagasModel->cadastrarVaga($nome, $area, $localizacao, $tipo_de_contrato, $nivel_da_vaga, $salario, $beneficios, $descricao, $requisitos_obrigatorios, $diferenciais);
    }

    public function editarVaga() {
        $id = $_POST['id'] ?? '';
        $nome = $_POST['titulo'] ?? '';
        $area = $_POST['area'] ?? '';
        $localizacao = $_POST['local'] ?? '';
        $tipo_de_contrato = $_POST['contrato'] ?? '';
        $nivel_da_vaga = $_POST['nivel'] ?? '';
        $salario = $_POST['salario'] ?? '';
        $beneficios = $_POST['beneficios'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $requisitos_obrigatorios = $_POST['requisitos'] ?? '';
        $diferenciais = $_POST['diferenciais'] ?? '';
        $ok = $this->vagasModel->editarVaga($id, $nome, $area, $localizacao, $tipo_de_contrato, $nivel_da_vaga, $salario, $beneficios, $descricao, $requisitos_obrigatorios, $diferenciais);
        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
        exit();
    }

    public function excluirVaga() {
        $id = $_POST['id'] ?? ($_GET['id'] ?? '');
        $ok = $this->vagasModel->excluirVaga($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
        exit();
    }

    public function getVagas() {
        header('Content-Type: application/json');
        $vagas = $this->vagasModel->getAllVagas();
        echo json_encode(['vagas' => $vagas]);
        exit();
    }
}
