<?php
include "../includes/conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_query = isset($_POST['search_query']) ? "%" . $_POST['search_query'] . "%" : '%';

    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_ocorrencia
                                WHERE nome_cidadao LIKE ?
                                OR data_ocorrido LIKE ?
                                OR hora_ocorrido LIKE ?
                                OR bairro LIKE ?
                                OR descricao_ocorrido LIKE ?
                                ORDER BY data_ocorrido DESC, hora_ocorrido DESC
                                ");
        $stmt->execute([$search_query, $search_query, $search_query, $search_query, $search_query]);

        if ($stmt->rowCount() > 0) {
            echo '
            <table class="occurrences-table">
                <thead>
                    <tr>
                        <th>Nome (Cidadão)</th>
                        <th>Bairro (Residência)</th>
                        <th>Data (Ocorrido)</th>
                        <th>Hora (Ocorrido)</th>
                        <th>Descrição (Ocorrido)</th>
                        <th>Acção</th>
                    </tr>
                </thead>
                <tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                echo '
                <tr>
                    <td>' . htmlspecialchars($row['nome_cidadao']) . '</td>
                    <td>' . htmlspecialchars($row['bairro']) . '</td>
                    <td>' . htmlspecialchars($row['data_ocorrido']) . '</td>
                    <td>' . htmlspecialchars($row['hora_ocorrido']) . '</td>
                    <td class="reduz">' . htmlspecialchars($row['descricao_ocorrido']) . '</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="visualizar_ocorrencia.php?id=' . $row['id'] . '" style="border: none; background: none; padding: 0;">
                                <img src="../image/visualizar.png" title="Visualizar" style="width:30px;">
                            </a>
                            <a href="actualizar_ocorrencia.php?id=' . $row['id'] . '" style="border: none; background: none; padding: 0;">
                                <img src="../image/actualizar.png" title="Actualizar" style="width:30px;">
                            </a>
                            <form action="notificar_como.php" method="post" style="display:inline;">
                                <button name="notificar" value="' . $row['id'] . '" style="border: none; background: none; padding: 0; cursor: pointer;">
                                    <img src="../image/notificar.png" title="Notificar" style="width:30px;">
                                </button>
                            </form>
                            <a href="excluir_ocorrencia.php?id=' . $row['id'] . '" style="border: none; background: none; padding: 0;" onclick="confirmDelete(' . htmlspecialchars($row['id']) . ')">
                                <img src="../image/excluir.png" title="Excluir" style="width:30px;">
                            </a>
                        </div>
                    </td>

                </tr>';

            }

            echo '</tbody></table>';
        } else {
            echo '<p>Nenhuma ocorrência correspondente encontrada.</p>';
        }
    } catch (PDOException $e) {
        echo '<p>Erro: ' . $e->getMessage() . '</p>';
    }
}
?>
