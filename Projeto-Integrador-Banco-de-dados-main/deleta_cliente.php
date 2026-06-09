<?php
require 'conecta.php';

// Verifica se o ID foi passado na URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara o comando SQL para evitar SQL Injection
    $sql = "DELETE FROM cliente WHERE id_cliente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redireciona de volta para a página principal (ajuste o nome do arquivo se necessário)
        header("Location: clientes.php?msg=sucesso_deletar");
        exit();
    } else {
        echo "Erro ao deletar cliente.";
    }
} else {
    header("Location: clientes.php");
    exit();
}
?>