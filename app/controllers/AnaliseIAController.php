<?php
require_once 'app/models/AnaliseIAModel.php';
require_once 'app/models/CurriculosModel.php';
require_once 'app/models/VagasModel.php';
require_once 'config/database.php';
require_once 'config/API.php';

class AnaliseIAController
{
    private $analiseIAModel;
    private $curriculosModel;
    private $vagasModel;

    public function __construct()
    {
        $this->analiseIAModel = new AnaliseIAModel();
        $this->curriculosModel = new CurriculosModel();
        $this->vagasModel = new VagasModel();
    }

    // Fluxo principal de análise IA
    public function analisarCandidatos()
    {
        $candidatos = $this->curriculosModel->buscarCandidatosAguardandoIA();
        $vagas = $this->vagasModel->getAllVagas();

        foreach ($candidatos as $candidato) {
            foreach ($vagas as $vaga) {
                
                $payload = "Analise o currículo do candidato abaixo e compare com a vaga informada.
                    Dê uma nota de 0 a 100 para a compatibilidade do candidato com a vaga.
                    
                    Responda apenas em JSON com a estrutura:
                    {
                    \"pontuacao\": 85
                    }
                    
                    Nome do candidato: {$candidato['Nome']}
                    
                    Descrição da vaga:
                    " . print_r($vaga, true) . "
                    
                    Currículo do candidato (em base64):
                    " . base64_encode($candidato['Curriculo']);

                // Envio para IA (exemplo com cURL)
                $pontuacao = $this->analiseIAModel->enviarParaIA($payload);
                $this->analiseIAModel->salvarPontuacao($candidato['Id_Candidato'], $vaga['Id_Vaga'], $pontuacao);
            }
            $this->curriculosModel->atualizarStatusIA($candidato['Id_Candidato'], 'analisado');
        }
        
        header('Location: ../curriculos');
        exit;

    }

    // Aprovar candidato
    public function aprovarCandidato()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->curriculosModel->atualizarAprovacao($id, 'aprovado');
        }
        header('Location: ../curriculos');
        exit;
    }

    // Negar candidato
    public function negarCandidato()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->curriculosModel->atualizarAprovacao($id, 'negado');
        }
        header('Location: ../curriculos');
        exit;
    }

    public function abrirPdf()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $candidato = $this->curriculosModel->buscarCandidatoComCurriculoPorId($id);
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="curriculo.pdf"');
            echo $candidato['Curriculo'];
            exit;
        }
        http_response_code(404);
        echo 'Currículo não encontrado.';
    }

    // Retornar dados para tela curriculos
    public function listarCurriculosComPontuacao()
    {
        return $this->analiseIAModel->buscarCurriculosComPontuacao();
    }
}