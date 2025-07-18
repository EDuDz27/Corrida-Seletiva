<?php

require_once 'app/models/DashboardModel.php';

class DashboardController
{
    private $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();
    }

    public function index()
    {
        session_start();
        if (!isset($_SESSION['email'])) {
            session_abort();
            header("Location: admin");
            exit();
        }

        $candidatos = $this->dashboardModel->getAllCandidatos();
        $estatisticas = $this->dashboardModel->getEstatisticas();
        include 'app/views/dashboard.html';
    }

    public function candidatosPorMes()
    {
        header('Content-Type: application/json');
        $ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');
        $meses = $this->dashboardModel->getCandidatosPorMes($ano);
        // Nomes dos meses em português
        $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $valores = array_values($meses);
        echo json_encode([
            'label' => 'Candidatos por mês',
            'labels' => $labels,
            'valores' => $valores
        ]);
        exit();
    }
} 