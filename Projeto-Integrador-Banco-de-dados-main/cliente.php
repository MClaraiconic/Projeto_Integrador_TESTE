<?php
require 'conecta.php';
?>
<!doctype html>
<html lang="pt-br">
<head>
    <title>Clientes</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/admin_menu.css">
    <link rel="stylesheet" href="css/buton.css">
    <?php include 'menu.php'; ?>
</head>
<body>
<main>
    <div>
        <form action="cadastra_cliente.php" method="post">
            <h2>Cadastro de Cliente</h2>
            
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required placeholder="Nome">

            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" required placeholder="000.000.000-00">

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required placeholder="exemplo@gmail.com">

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" required placeholder="Rua, Bairro, Número">

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" required placeholder="(00) 00000-0000">
            
            <input type="submit" value="Salvar Cliente">
        </form>
    </div>

    <section>
        <h1 style="text-align: center;">Tabela de Clientes</h1>
        <div class="tabela-produtos">
            <?php
            $sql = "SELECT * FROM cliente ORDER BY id_cliente ASC";
            $stmt = $pdo->query($sql);
            
            echo "<table border='1' style='width:100%; text-align:left; border-collapse: collapse;'>";
            echo " <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                   </tr>";
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo " <tr>";
                echo " <td>" . $row['id_cliente'] . "</td>";
                echo " <td>" . htmlspecialchars($row['nome']) . "</td>";
                echo " <td>" . htmlspecialchars($row['cpf']) . "</td>";
                echo " <td>" . htmlspecialchars($row['email']) . "</td>";
                echo " <td>" . htmlspecialchars($row['endereco']) . "</td>";
                echo " <td>" . htmlspecialchars($row['telefone']) . "</td>";
                // Botões de Ação passando o ID via URL (?id=...)
            echo " <td>";
            echo "  <a href='edit_cliente.php?id=" . $row['id_cliente'] . "' class='btn-acao btn-editar'>Editar</a>";
            echo "  <a href='deleta_cliente.php?id=" . $row['id_cliente'] . "' class='btn-acao btn-excluir' onclick=\"return confirm('Tem certeza que deseja excluir este cliente?')\">Excluir</a>";
            echo " </td>";
            
            echo " </tr>";
            }
            echo "</table>";
            ?>
        </div>
    </section>
</main>
</body>
</html>