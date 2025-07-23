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

    /**
     * Buscar candidatos aguardando análise da IA
     */
    public function buscarCandidatosAguardandoIA() {
        $stmt = $this->conn->prepare("SELECT * FROM candidato WHERE Analise_IA = 'aguardando'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Atualizar status de análise da IA do candidato
     */
    public function atualizarStatusIA($idCandidato, $status) {
        $stmt = $this->conn->prepare("UPDATE candidato SET Analise_IA = ? WHERE Id_Candidato = ?");
        $stmt->execute([$status, $idCandidato]);
    }

    /**
     * Atualizar aprovação do candidato
     */
    public function atualizarAprovacao($idCandidato, $aprovacao) {
        $stmt = $this->conn->prepare("UPDATE candidato SET Aprovacao = ? WHERE Id_Candidato = ?");
        $stmt->execute([$aprovacao, $idCandidato]);
    }

    public function buscarCandidatoComCurriculoPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM candidato WHERE Id_Candidato = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
