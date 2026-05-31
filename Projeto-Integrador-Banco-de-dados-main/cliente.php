<?php

require 'conecta.php';

?>
<!doctype html>
<html>
<header>
	<title>Clientes</title>
	<meta lang="pt-br">
    <meta charset="UTF-8">
	<link rel="stylesheet" href="css/admin_menu.css">
	<?php include 'menu.php' ?>
</header>
<body>
<main>
    <div>
        <form action="cadastra_cliente.php" method="post">
            <h2>Cadastro de Cliente</h2>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>
            
            <label for="cpf">CPF:</label>
            <input type="number" name="cpf" step="11" placeholder="000.000.000-00">

            <label for="email">E-mail:</label>
            <input type="text" name="email">

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco">
            
            <label for="telefone">Telefone/Celular:</label>
            <input type="number" name="telefone" step="11" placeholder="00 0 0000-0000">

            
            <input type="submit" value="Salvar Cliente">
        </form>
    </div>

    <section>
        <h1 style="text-align: center;">Tabela de Clientes</h1>
        <div class="tabela-produtos"><!--CLIENTES-->
            <?php
            $sql = "SELECT * FROM cliente";
            $stmt = $pdo->query($sql);
            echo "<table border=1>";
            echo " <tr>";
            echo " <th>ID</th>";
            echo " <th>Nome</th>";
            echo " <th>CPF</th>";
            echo " <th>E-mail</th>";
            echo " <th>Endereço</th>";
            echo " <th>Telefone/Celular</th>";
            echo " </tr>";
            while ($row = $stmt->fetch()) {
            echo " <tr>";
            echo " <th>" . $row['id_cliente'] . "</th>";
            echo " <th>" . $row['nome'] . "</th>";
            echo " <th>" . number_format($row['cpf'], 3, '.', 3, '.', 3, '-', 2) . "</th>";
            echo " <th>" . $row['email'] . "</th>";
            echo " <th>" . $row['endereco'] . "</th>";
            echo " <th>" . number_format($row['telefone_celular'], 2, 9) . "</th>";
            echo " </tr>";
            }
            echo "</table>";
?>
        </div>
    </section>
</main>
<?php
?>
</body>
</html>