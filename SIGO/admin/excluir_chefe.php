<?php 

   include '../includes/conexao.php';

   if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
     $chefeId = $_GET["id"];

     try{
        $stmt = $conn->prepare("DELETE FROM tbl_chefe_bairro WHERE id = ?");
        $stmt->execute([$chefeId]);

        header("location: gerenciar_chefes.php");
        exit();
     } catch(PDOException $e) {
        echo 'Erro ao excluir o Chefe do bairro: ' . $e->getMessage();
     }

   }

  $conn = null;
?>