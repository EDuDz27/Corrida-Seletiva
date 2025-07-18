<?php

class VagasModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function cadastrarVaga($nome, $area, $localizacao, $tipo_de_contrato, $nivel_da_vaga, $salario, $beneficios, $descricao, $requisitos_obrigatorios, $diferenciais)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO vagas (Nome, Area, Localizacao, Tipo_de_Contrato, Nivel_da_Vaga, Salario, Beneficios, Descricao, Requisitos_Obrigatorios, Diferenciais) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $area, $localizacao, $tipo_de_contrato, $nivel_da_vaga, $salario, $beneficios, $descricao, $requisitos_obrigatorios, $diferenciais]);
            return true;
        } catch (Exception $e) {
            error_log('Erro ao cadastrar vaga: ' . $e->getMessage());
            return false;
        }
    }

    public function getAllVagas()
    {
        try {
            $query = "SELECT * FROM vagas ORDER BY Id_Vaga DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Erro ao buscar vagas: ' . $e->getMessage());
            return [];
        }
    }

    public function editarVaga($id, $nome, $area, $localizacao, $tipo_de_contrato, $nivel_da_vaga, $salario, $beneficios, $descricao, $requisitos_obrigatorios, $diferenciais)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE vagas SET Nome=?, Area=?, Localizacao=?, Tipo_de_Contrato=?, Nivel_da_Vaga=?, Salario=?, Beneficios=?, Descricao=?, Requisitos_Obrigatorios=?, Diferenciais=? WHERE Id_Vaga=?");
            $stmt->execute([$nome, $area, $localizacao, $tipo_de_contrato, $nivel_da_vaga, $salario, $beneficios, $descricao, $requisitos_obrigatorios, $diferenciais, $id]);
            return true;
        } catch (Exception $e) {
            error_log('Erro ao editar vaga: ' . $e->getMessage());
            return false;
        }
    }

    public function excluirVaga($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM vagas WHERE Id_Vaga=?");
            $stmt->execute([$id]);
            return true;
        } catch (Exception $e) {
            error_log('Erro ao excluir vaga: ' . $e->getMessage());
            return false;
        }
    }
}
