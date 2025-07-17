<?php 

   include '../includes/conexao.php';

   if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["distintivo"])) {
     $agenteId = $_GET["distintivo"];

     try{
        $stmt = $conn->prepare("DELETE FROM tbl_usuarios WHERE distintivo = ?");
        $stmt->execute([$agenteId]);

        header("location: gerenciar_agentes.php");
        exit();
     } catch(PDOException $e) {
        echo 'Erro ao excluir o agente: ' . $e->getMessage();
     }

   }

  $conn = null;
?>