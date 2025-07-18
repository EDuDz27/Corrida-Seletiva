<?php

require_once 'config/database.php';

class DashboardModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function getAllCandidatos()
    {
        try {
            $query = "SELECT Id_Candidato, Nome, Email, Telefone, Data, Aprovacao 
                      FROM candidato 
                      ORDER BY Data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Erro ao buscar candidatos: ' . $e->getMessage());
            return [];
        }
    }

    public function getEstatisticas()
    {
        try {
            $stats = [];
            
            // Total de vagas
            $query = "SELECT COUNT(*) as total FROM vagas";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_vagas'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de candidatos
            $query = "SELECT COUNT(*) as total FROM candidato";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_candidatos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Candidatos aprovados
            $query = "SELECT COUNT(*) as total FROM candidato WHERE Aprovacao = 'aprovado'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['candidatos_aprovados'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Candidatos em análise
            $query = "SELECT COUNT(*) as total FROM candidato WHERE Aprovacao = 'analisando'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['candidatos_analisando'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Candidatos negados
            $query = "SELECT COUNT(*) as total FROM candidato WHERE Aprovacao = 'negado'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['candidatos_negados'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $stats;
        } catch (Exception $e) {
            error_log('Erro ao buscar estatísticas: ' . $e->getMessage());
            return [
                'total_vagas' => 0,
                'total_candidatos' => 0,
                'candidatos_aprovados' => 0,
                'candidatos_analisando' => 0,
                'candidatos_negados' => 0
            ];
        }
    }

    public function getCandidatosPorMes($ano = null)
    {
        if (!$ano) {
            $ano = date('Y');
        }
        try {
            $query = "SELECT MONTH(Data) as mes, COUNT(*) as total
                      FROM candidato
                      WHERE YEAR(Data) = :ano
                      GROUP BY mes
                      ORDER BY mes";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ano', $ano, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Inicializa todos os meses com 0
            $meses = array_fill(1, 12, 0);
            foreach ($result as $row) {
                $meses[(int)$row['mes']] = (int)$row['total'];
            }
            return $meses;
        } catch (Exception $e) {
            error_log('Erro ao buscar candidatos por mês: ' . $e->getMessage());
            return array_fill(1, 12, 0);
        }
    }

} 