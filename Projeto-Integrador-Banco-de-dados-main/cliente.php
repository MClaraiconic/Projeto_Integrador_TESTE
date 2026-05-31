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
$sql = "SELECT * FROM cliente";
	$stmt = $pdo->query($sql);
	//Com loop usando while
	
	while ($row = $stmt->fetch()) {
        echo "ID: " . $row['id_cliente'] . "<br>";
        echo "Nome: " . $row['nome'] . "<br>";
        echo "cpf: " . $row['cpf'] . "<br>";
        echo "e-mail: " . $row['email'] . "<br>";
		echo "telefone: " . $row['telefone'] . "<br>";
		
        echo "-----------------------<br>";
    }



?>
</html>