<?php

require 'conecta.php';

?>
<!doctype html>
<html>
<header>
	<title>Admin</title>
	<meta lang="pt-br">
    <meta charset="UTF-8">
	<link rel="stylesheet" href="css/admin_menu.css" >
    <link rel="stylesheet" href="css/buton.css" >
	
</header>
<body>
<header>
	<?php include 'menu.php' ?>
</header>
<main>
    <div>
        <form action="cadastra_produto.php" method="post">
            <h2>Cadastro de Produto</h2>
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" required>
            
            <label for="sabor">Sabor:</label>
            <input type="text" name="sabor">
            
            <label for="preco">Preço:</label>
            <input type="number" name="preco" step="0.01" placeholder="0.00">
            
            <input type="submit" value="Salvar Produto">
        </form>
    </div>

    <section>
    <h1 style="text-align: center;">Tabela de Produtos</h1>
    <div class="tabela-produtos">
        <?php
        $sql = "SELECT * FROM produto";
        $stmt = $pdo->query($sql);
        
        echo "<table border=1>";
        echo " <tr>";
        echo " <th>ID</th>";
        echo " <th>Descrição</th>";
        echo " <th>Sabor</th>";
        echo " <th>Preço</th>";
        echo " <th>Ações</th>"; // Nova coluna
        echo " </tr>";
        
        while ($row = $stmt->fetch()) {
            echo " <tr>";
            echo " <td>" . $row['id_produto'] . "</td>";
            echo " <td>" . $row['descricao'] . "</td>";
            echo " <td>" . $row['sabor'] . "</td>";
            echo " <td>R$ " . number_format($row['preco'], 2, ',', '.') . "</td>";
            
            // Botões de Ação passando o ID via URL (?id=...)
            echo " <td>";
            echo "  <a href='edit_produto.php?id=" . $row['id_produto'] . "' class='btn-acao btn-editar'>Editar</a>";
            echo "  <a href='deleta_produto.php?id=" . $row['id_produto'] . "' class='btn-acao btn-excluir' onclick=\"return confirm('Tem certeza que deseja excluir este produto?')\">Excluir</a>";
            echo " </td>";
            
            echo " </tr>";
        }
        echo "</table>";
        ?>
    </div>
<?php
	//Com loop usando for
	/*
	$resultados = $stmt->fetchAll(); // Transforma tudo em um array
	$total = count($resultados);    // Conta quantos registros existem
	for ($i = 0; $i < $total; $i++) {
		echo "ID: " . $resultados[$i]['id_produto'] . "<br>";
        echo "Descrição: " . $resultados[$i]['descricao'] . "<br>";
	}
	*/
?>

</body>
</html>