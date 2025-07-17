<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;


include '../includes/conexao.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("ID inválido.");
}

$stmt = $conn->prepare("SELECT * FROM tbl_ocorrencia WHERE id = :id");
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    die("Ocorrência não encontrada.");
}

$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Formatar datas
$data_nascimento = date("d/m/Y", strtotime($dados['data_nascimento']));
$data_ocorrido = date("d/m/Y", strtotime($dados['data_ocorrido']));
$hora_ocorrido = substr($dados['hora_ocorrido'], 0, 5);

// Variáveis para o template
extract([
    'nome' => $dados['nome_cidadao'],
    'sexo' => $dados['sexo_cidadao'],
    'estado_civil' => $dados['estado_civil'],
    'idade' => $dados['idade_cidadao'],
    'mae' => $dados['mae_cidadao'],
    'pai' => $dados['pai_cidadao'],
    'data_nascimento' => $data_nascimento,
    'naturalidade' => $dados['naturalidade_distrito'],
    'provincia' => $dados['provincia'],
    'nacionalidade' => $dados['nacionalidade'],
    'local_trabalho' => $dados['local_trabalho'],
    'endereco_trabalho' => $dados['local_trabalho'],
    'bairro' => $dados['bairro'],
    'endereco_caso' => $dados['endereco_caso'],
    'classificacao' => $dados['classificacao'],
    'data_ocorrido' => $data_ocorrido,
    'hora_ocorrido' => $hora_ocorrido,
    'lugar_ocorrido' => $dados['lugar_ocorrido'],
    'descricao' => $dados['descricao_ocorrido']
]);

ob_start();
include 'auto_denuncia_template.php';
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("auto_de_denuncia_{$id}.pdf", ["Attachment" => false]);

exit;
