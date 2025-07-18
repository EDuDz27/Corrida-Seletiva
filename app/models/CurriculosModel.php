<?php

class CurriculosModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function cadastrarCurriculo($nome, $email, $telefone, $cep, $uf, $rua, $bairro, $cidade, $numero, $complemento, $curriculo)
    {
        try {
            $this->conn->beginTransaction();

            // Lê o conteúdo binário do PDF
            $curriculoBinario = null;
            if ($curriculo && $curriculo['tmp_name'] && is_uploaded_file($curriculo['tmp_name'])) {
                $curriculoBinario = file_get_contents($curriculo['tmp_name']);
            }

            // Insere candidato
            $stmt = $this->conn->prepare("INSERT INTO candidato (Nome, Email, Telefone, Curriculo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $telefone, $curriculoBinario]);
            $idCandidato = $this->conn->lastInsertId();

            // Insere endereço
            $stmt2 = $this->conn->prepare("INSERT INTO endereco (Id_Candidato, CEP, Rua, Bairro, Cidade, UF, Numero, Complemento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt2->execute([$idCandidato, $cep, $rua, $bairro, $cidade, $uf, $numero, $complemento]);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('Erro ao cadastrar currículo: ' . $e->getMessage());
            return false;
        }
    }

}
