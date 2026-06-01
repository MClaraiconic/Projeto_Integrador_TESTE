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
	
</header>
<body>
<header>
	<?php include 'menu.php' ?>
</header>
<main>
<?php

$nome =  $_POST['nome'];
$cpf= $_POST['cpf'];
$email = $_POST['email'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];

if(trim($nome) == ""){
    die("Nome não pode ser nulo");
    //header("Location: admin.php?erro=123");
}

$sql = "INSERT INTO cliente (nome, cpf, email, endereco, telefone) 
        VALUES (:nome, :cpf, :email, :endereco, :telefone)";
$stmt = $pdo->prepare($sql);


try {
    $stmt->execute([
        ':nome'   => $nome,
        ':cpf'   => $cpf, 
        ':email'  => $email,
        ':endereco'  => $endereco,
        ':telefone'  => $telefone

    ]);
    echo "Cliente inserido com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao inserir: " . $e->getMessage();
}


?>
</main>
</body>
</html>