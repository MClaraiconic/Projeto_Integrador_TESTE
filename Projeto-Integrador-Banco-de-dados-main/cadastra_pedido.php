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
	
</header>
<body>
<header>
	<?php include 'menu.php' ?>
</header>
<main>
<?php

$cliente_pedido =  $_POST['cliente_pedido'];
$status_entrega= $_POST['status_entrega'];
$descricao_pedido = $_POST['descricao_pedido'];
$endereco_pedido = $_POST['endereco_pedido'];
$contato_pedido = $_POST['contato_pedido'];

if(trim($endereco_pedido) == ""){
    die("Endereço não pode ser nulo");
    //header("Location: admin.php?erro=123");
}

$sql = "INSERT INTO pedido (cliente_pedido, status_entrega, descricao_pedido, endereco_pedido, contato_pedido) 
        VALUES (:cliente_pedido, :status_entrega, :descricao_pedido, :endereco_pedido, :contato_pedido)";
$stmt = $pdo->prepare($sql);


try {
    $stmt->execute([
        ':cliente_pedido'   => $cliente_pedido,
        ':status_entrega'   => $status_entrega, 
        ':descricao_pedido'  => $descricao_pedido,
        ':endereco_pedido'  => $endereco_pedido,
        ':contato_pedido'  => $contato_pedido

    ]);
    echo "Pedido inserido com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao inserir: " . $e->getMessage();
}


?>
</main>
</body>
</html>