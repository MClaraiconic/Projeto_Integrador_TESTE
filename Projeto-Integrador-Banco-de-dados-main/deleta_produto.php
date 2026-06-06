<?php
require 'conecta.php';

// Verifica se o ID foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara a query para deletar de forma segura (Prepared Statements)
    $sql = "DELETE FROM produto WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    
    // Executa passando o ID
    $stmt->execute([':id' => $id]);
}

// Redireciona de volta para a página principal (ajuste o nome do arquivo se necessário)
header("Location: admin.php"); 
exit;
?>