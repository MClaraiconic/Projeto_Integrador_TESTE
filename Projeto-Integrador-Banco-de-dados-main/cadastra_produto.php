<?php

require 'security.php';
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
<header>
	<?php include 'menu.php' ?>
</header>
<main>
<?php

$descricao =  $_POST['descricao'];
$sabor= $_POST['sabor'];
$preco = $_POST['preco'];

if(trim($preco) == ""){
    die("Preço não pode ser nulo");
    //header("Location: admin.php?erro=123");
}

$sql = "INSERT INTO produto (descricao, sabor, preco) 
        VALUES (:descricao, :sabor, :preco)";
$stmt = $pdo->prepare($sql);


try {
    $stmt->execute([
        ':descricao'   => $descricao,
        ':sabor'   => $sabor, 
        ':preco'       => $preco

    ]);
    echo "Produto inserido com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao inserir: " . $e->getMessage();
}


?>
</main>
</body>
</html>