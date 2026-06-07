<?php
require 'conecta.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_pedido = $_GET['id'];

    try {
        // Iniciamos uma transação para garantir que ambas as exclusões ocorram com sucesso
        $pdo->beginTransaction();

        // 1. Deleta os itens do carrinho vinculados a este pedido primeiro
        $sql_itens = "DELETE FROM item_pedido WHERE id_pedido = :id_pedido";
        $stmt_itens = $pdo->prepare($sql_itens);
        $stmt_itens->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt_itens->execute();

        // 2. Deleta o pedido principal
        $sql_pedido = "DELETE FROM pedido WHERE id_pedido = :id_pedido";
        $stmt_pedido = $pdo->prepare($sql_pedido);
        $stmt_pedido->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt_pedido->execute();

        // Confirma as alterações no banco
        $pdo->commit();

        header("Location: pedidos.php?msg=pedido_deletado");
        exit();

    } catch (Exception $e) {
        // Se algo der errado, desfaz tudo
        $pdo->rollBack();
        echo "Erro ao deletar pedido: " . $e->getMessage();
    }
} else {
    header("Location: pedidos.php");
    exit();
}
?>