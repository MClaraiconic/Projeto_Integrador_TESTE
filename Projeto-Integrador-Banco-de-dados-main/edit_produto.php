<?php
require 'conecta.php';

// 1. BUSCAR DADOS DO PRODUTO SELECIONADO
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM produto WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $produto = $stmt->fetch();

    // Se o produto não existir, volta para a index
    if (!$produto) {
        header("Location: admin.php");
        exit;
    }
} else {
    header("Location: admin.php");
    exit;
}

// 2. ATUALIZAR OS DADOS QUANDO O FORMULÁRIO FOR ENVIADO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produto = $_POST['id_produto'];
    $descricao  = $_POST['descricao'];
    $sabor      = $_POST['sabor'];
    $preco      = $_POST['preco'];

    $sql_update = "UPDATE produto SET descricao = :descricao, sabor = :sabor, preco = :preco WHERE id_produto = :id";
    $stmt_update = $pdo->prepare($sql_update);
    
    $stmt_update->execute([
        ':descricao' => $descricao,
        ':sabor'     => $sabor,
        ':preco'     => $preco,
        ':id'        => $id_produto
    ]);

    // Redireciona após atualizar
    header("Location: admin.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
    <title>Editar Produto</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/admin_menu.css";>
    <link rel="stylesheet" href="css/buton.css";>
</head>
<body>
<main>
    <div>
        <form action="edit_produto.php?id=<?= $produto['id_produto'] ?>" method="post">
            <h2>Editar Produto</h2>
            
            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">

            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" value="<?= htmlspecialchars($produto['descricao']) ?>" required>
            
            <label for="sabor">Sabor:</label>
            <input type="text" name="sabor" value="<?= htmlspecialchars($produto['sabor']) ?>">
            
            <label for="preco">Preço:</label>
            <input type="number" name="preco" step="0.01" value="<?= $produto['preco'] ?>">
            
            <input type="submit" value="Atualizar Produto">
            <a href="admin.php" class="btn-cancelar">Cancelar</a>
        </form>
    </div>
</main>
</body>
</html>