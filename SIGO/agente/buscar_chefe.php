<?php
include "../includes/conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_query = isset($_POST['search_query']) ? "%" . $_POST['search_query'] . "%" : '%';

    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_chefe_bairro 
                                WHERE nome_completo LIKE ? 
                                OR genero LIKE ? 
                                OR bairro LIKE ? 
                                OR contacto LIKE ?");
        $stmt->execute([$search_query, $search_query, $search_query, $search_query]);

        if ($stmt->rowCount() > 0) {
            echo '
            <table class="cheads-table">
                <thead>
                    <tr>
                        <th>Nome completo</th>
                        <th>Género</th>
                        <th>Bairro</th>
                        <th>Contacto</th>
                    </tr>
                </thead>
                <tbody>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['nome_completo']) . "</td>
                        <td>" . htmlspecialchars($row['genero']) . "</td>
                        <td>" . htmlspecialchars($row['bairro']) . "</td>
                        <td>" . htmlspecialchars($row['contacto']) . "</td>
                    </tr>";
            }
            echo '</tbody></table>';
        } else {
            echo 'Nenhum chefe do bairro encontrado.';
        }
    } catch (PDOException $e) {
        echo 'Erro ao buscar chefes: ' . $e->getMessage();
    }
}
?>
