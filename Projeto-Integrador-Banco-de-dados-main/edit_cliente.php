<?php
require 'conecta.php';

// 1. BUSCAR DADOS DO CLIENTE PARA PREENCHER O FORMULÁRIO
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM cliente WHERE id_cliente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        die("Cliente não encontrado.");
    }
}

// 2. ATUALIZAR OS DADOS NO BANCO QUANDO O FORMULÁRIO FOR ENVIADO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];

    $sql_update = "UPDATE cliente SET nome = :nome, cpf = :cpf, email = :email, endereco = :endereco, telefone = :telefone WHERE id_cliente = :id";
    $stmt_update = $pdo->prepare($sql_update);
    
    $stmt_update->bindParam(':nome', $nome);
    $stmt_update->bindParam(':cpf', $cpf);
    $stmt_update->bindParam(':email', $email);
    $stmt_update->bindParam(':endereco', $endereco);
    $stmt_update->bindParam(':telefone', $telefone);
    $stmt_update->bindParam(':id', $id_cliente, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
        header("Location: clientes.php?msg=sucesso_atualizar");
        exit();
    } else {
        echo "Erro ao atualizar dados.";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <title>Editar Cliente</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/admin_menu.css">
    <link rel="stylesheet" href="css/buton.css";>
    <?php include 'menu.php'; ?>
</head>
<body>
<main>
    <div>
        <form action="edit_cliente.php?id=<?= $cliente['id_cliente'] ?>" method="post">
            <h2>Editar Cliente</h2>
            
            <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required value="<?php echo htmlspecialchars($cliente['nome']); ?>">

            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" required value="<?php echo htmlspecialchars($cliente['cpf']); ?>">

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($cliente['email']); ?>">

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" required value="<?php echo htmlspecialchars($cliente['endereco']); ?>">

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" required value="<?php echo htmlspecialchars($cliente['telefone']); ?>">
            
            <input type="submit" value="Atualizar Dados">
            <a href="cliente.php" class="btn-cancelar">Cancelar</a>
        </form>
    </div>
</main>
</body>
</html>