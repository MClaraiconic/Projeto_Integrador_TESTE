<?php
session_start();
require 'conecta.php';

// Verifica se os dados foram enviados
if (isset($_POST['email']) && isset($_POST['senha'])) {
    //echo "E-mail ou senha inválidos.";
    $email = $_POST['email'];
    //echo "E-mail ou senha inválidos.";
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE usuario = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Teste de comparação simples (já que você inseriu '123' puro no MySQL)
    if ($usuario && $senha == $usuario['senha']) {
        $_SESSION['usuario_id']   = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        
        // Verifique se o arquivo admin.php ou admin_painel.php existe
        header("Location: admin.php");
        exit;
    } else {
        echo "E-mail ou senha inválidos.";
    }
} else {
    header("Location: admin.php");
    exit;
}
?>