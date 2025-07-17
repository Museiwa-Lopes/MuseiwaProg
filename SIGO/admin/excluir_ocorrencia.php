<?php 

   include '../includes/conexao.php';

   if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
     $ocorrenciaId = $_GET["id"];

     try{
        $stmt = $conn->prepare("DELETE FROM tbl_ocorrencia WHERE id = ?");
        $stmt->execute([$ocorrenciaId]);

        header("location: listar_ocorrencias.php");
        exit();
     } catch(PDOException $e) {
        echo 'Erro ao excluir a ocorrencia: ' . $e->getMessage();
     }

   }

  $conn = null;
?>