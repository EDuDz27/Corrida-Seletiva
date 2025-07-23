<?php
class AnaliseIAModel
{
    private $conn;
    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    // Envia o payload para a IA e retorna a pontuação
    public function enviarParaIA($promptText)
    {
        $url = API_URL;
        $apiKey = API_KEY;

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $promptText]
                    ]
                ]
            ]
        ];

        $ch = curl_init($url . '?key=' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);

        if ($response === false) {
            error_log('Erro cURL: ' . curl_error($ch));
            curl_close($ch);

            echo "<script>alert('ERRO CURL: " . addslashes(json_encode(curl_error($ch))) . "');</script>";

            return null;
        }

        curl_close($ch);
        $result = json_decode($response, true);

        // Extrai o texto da resposta da IA
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $respostaIA = $result['candidates'][0]['content']['parts'][0]['text'];
    
            // Limpar a resposta para remover qualquer texto adicional, como '```json' e '```'
            $respostaIA = preg_replace('/```json\n|\n```/', '', $respostaIA);
    
            // Tenta decodificar a resposta limpa como JSON
            $json = json_decode($respostaIA, true);
            if (is_array($json) && isset($json['pontuacao'])) {
                echo "<script>alert('json:     " . addslashes(json_encode($respostaIA)) . "');</script>";
                
                return $json['pontuacao']; // ou return $json para retornar tudo
            } else {
                error_log("Resposta não pôde ser decodificada como JSON: " . $respostaIA);
                echo "<script>alert('Resposta não pôde ser decodificada como JSON: " . addslashes(json_encode($respostaIA)) . "');</script>";
            }
        }
        
        echo "<script>alert('ERROR RETORNANDO NULL');</script>";
        return null;
    }

    // Salva a pontuação na tabela pontuacoes
    public function salvarPontuacao($idCandidato, $idVaga, $pontuacao)
    {
        $stmt = $this->conn->prepare("INSERT INTO pontuacoes (Id_Candidato, Id_Vaga, Pontuacao) VALUES (?, ?, ?)");
        $stmt->execute([$idCandidato, $idVaga, $pontuacao]);
    }

    // Busca currículos com pontuação para exibir na tela
    public function buscarCurriculosComPontuacao()
    {
        $sql = "SELECT c.Id_Candidato, c.Nome, c.Curriculo, c.Aprovacao, p.Pontuacao, v.Nome AS NomeVaga
                FROM candidato c
                INNER JOIN pontuacoes p ON c.Id_Candidato = p.Id_Candidato
                LEFT JOIN vagas v ON p.Id_Vaga = v.Id_Vaga ";
        // WHERE c.Analise_IA = 'analisado' AND p.Pontuacao IS NOT NULL
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}