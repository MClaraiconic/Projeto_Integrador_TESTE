<?php

require 'conecta.php';

?>
<!doctype html>
<html>
<header>
	<title>Pedidos</title>
	<meta lang="pt-br">
    <meta charset="UTF-8">
	<link rel="stylesheet" href="css/admin_menu.css">
	<?php include 'menu.php' ?>
</header>
<body>
<main>
    <div>
        <form action="cadastra_pedido.php" method="post">
            <h2>Cadastro de Pedido</h2>
            <label for="cliente_pedido">Nome do Cliente:</label>
            <input type="text" name="cliente_pedido" required>
            
            <label for="status_entrega">Status da Entrega:</label>
            <input type="text" name="status_entrega">

            <label for="descricao_pedido">Descrição:</label>
            <input type="text" name="descricao_pedido">

            <label for="endereco_pedido">Endereço:</label>
            <input type="text" name="endereco_pedido">
            
            <label for="contato_pedido">Telefone/Celular:</label>
            <input type="text" name="contato_pedido">

            
            <input type="submit" value="Salvar Pedido">
        </form>
    </div>

    <section>
        <h1 style="text-align: center;">Tabela de Pedidos</h1>
        <div class="tabela-produtos"><!--PEDIDOS-->
            <?php
            $sql = "SELECT * FROM pedido";
            $stmt = $pdo->query($sql);
            echo "<table border=1>";
            echo " <tr>";
            echo " <th>ID</th>";
            echo " <th>Cliente</th>";
            echo " <th>Status da Entrega</th>";
            echo " <th>Descrição</th>";
            echo " <th>Endereço</th>";
            echo " <th>Telefone/Celular</th>";
            echo " </tr>";
            while ($row = $stmt->fetch()) {
            echo " <tr>";
            echo " <th>" . $row['id_pedido'] . "</th>";
            echo " <th>" . $row['cliente_pedido'] . "</th>";
            echo " <th>" . $row['descricao_pedido'] . "</th>";
            echo " <th>" . $row['endereco_pedido'] . "</th>";
            echo " <th>" . $row['contato_pedido'] . "</th>";
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