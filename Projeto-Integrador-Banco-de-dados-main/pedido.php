<?php

require 'conecta.php';

?>
<!doctype html>
<html>
<header>
	<title>Login</title>
	<meta lang="pt-br">
    <meta charset="UTF-8">
	<link rel="stylesheet" href="css/admin_menu.css">
	
</header>
<body>
  <?php include 'menu.php' ?>
<h1>Clientes</h1>
<?php
$sql = "SELECT * FROM pedido";
	$stmt = $pdo->query($sql);
	//Com loop usando while
	
	while ($row = $stmt->fetch()) {
        echo "ID: " . $row['id_pedido'] . "<br>";
        echo "Status da Entrega: " . $row['status_entrega'] . "<br>";
        echo "Pagamento: " . $row['id_pagamento'] . "<br>";
        echo "Cliente: " . $row['id_cliente'] . "<br>";
        echo "Produto: " . $row['id_produto'] . "<br>";
		
        echo "-----------------------<br>";
    }



?>
</html>