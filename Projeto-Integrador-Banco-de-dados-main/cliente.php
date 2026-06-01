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
            <input type="number" name="cpf">

            <label for="email">E-mail:</label>
            <input type="text" name="email">

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco">
            
            <label for="telefone">Telefone/Celular:</label>
            <input type="number" name="telefone">

            
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
            echo " <th>" . $row['cpf'] . "</th>";
            echo " <th>" . $row['email'] . "</th>";
            echo " <th>" . $row['endereco'] . "</th>";
            echo " <th>" . $row['telefone'] . "</th>";
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