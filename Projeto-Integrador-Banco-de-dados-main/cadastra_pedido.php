<?php
require 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados gerais do pedido
    $id_cliente = $_POST['id_cliente'];
    $status_entrega = $_POST['status_entrega'] ?? 'Pendente';
    $descricao_pedido = $_POST['descricao_pedido'] ?? '';
    $endereco_pedido = trim($_POST['endereco_pedido'] ?? ''); // trim remove espaços vazios acidentais
    $contato_pedido = $_POST['contato_pedido'] ?? '';

    // Recebe os arrays do carrinho de compras
    $produtos_selecionados = $_POST['produtos'] ?? [];
    $quantidades = $_POST['quantidades'] ?? [];

    if (empty($id_cliente) || empty($produtos_selecionados)) {
        die("Erro: Selecione um cliente e pelo menos um produto!");
    }

    try {
        // LÓGICA DO ENDEREÇO VAZIO: Busca o endereço padrão do cliente se o campo foi deixado em branco
        if (empty($endereco_pedido)) {
            $sql_busca_end = "SELECT endereco FROM cliente WHERE id_cliente = :id_cliente";
            $stmt_end = $pdo->prepare($sql_busca_end);
            $stmt_end->execute([':id_cliente' => $id_cliente]);
            $cliente = $stmt_end->fetch(PDO::FETCH_ASSOC);

            // Se o cliente possuir endereço no cadastro, utiliza ele
            if ($cliente && !empty(trim($cliente['endereco']))) {
                $endereco_pedido = $cliente['endereco'];
            } else {
                $endereco_pedido = 'Não Informado'; // Fallback caso o cliente também não tenha endereço
            }
        }

        // Inicia uma transação no banco de dados (segurança para o carrinho)
        $pdo->beginTransaction();

        // 1. Insere o pedido principal na tabela 'pedido'
        $sql_pedido = "INSERT INTO pedido (id_cliente, status_entrega, descricao_pedido, endereco_pedido, contato_pedido) 
                       VALUES (:id_cliente, :status_entrega, :descricao_pedido, :endereco_pedido, :contato_pedido)";
        
        $stmt_pedido = $pdo->prepare($sql_pedido);
        $stmt_pedido->execute([
            ':id_cliente' => $id_cliente,
            ':status_entrega' => $status_entrega,
            ':descricao_pedido' => $descricao_pedido,
            ':endereco_pedido' => $endereco_pedido,
            ':contato_pedido' => $contato_pedido
        ]);

        // Pega o ID do pedido que acabou de ser gerado no banco
        $id_pedido_gerado = $pdo->lastInsertId();

        // 2. Prepara a busca do preço na tabela correta 'produto' (CORRIGIDO: singular)
        $sql_preco = "SELECT preco FROM produto WHERE id_produto = :id_produto";
        $stmt_preco = $pdo->prepare($sql_preco);

        // 3. Salva cada item do carrinho na tabela intermediária 'itens_pedido'
        $sql_item = "INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) 
                     VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario)";
        $stmt_item = $pdo->prepare($sql_item);

        foreach ($produtos_selecionados as $index => $id_produto) {
            if (empty($id_produto)) continue; // Pula se houver algum campo vazio

            $qtd = (int)$quantidades[$index];
            
            // Busca o preço atual do produto no banco para registrar no histórico do pedido
            $stmt_preco->execute([':id_produto' => $id_produto]);
            $produto_info = $stmt_preco->fetch(PDO::FETCH_ASSOC);
            
            $preco_unitario = $produto_info ? $produto_info['preco'] : 0.00;

            // Executa a inserção do item do carrinho
            $stmt_item->execute([
                ':id_pedido' => $id_pedido_gerado,
                ':id_produto' => $id_produto,
                ':quantidade' => $qtd,
                ':preco_unitario' => $preco_unitario
            ]);
        }

        // Se tudo deu certo, confirma as alterações no banco de dados
        $pdo->commit();

        // CORRIGIDO: Redirecionamento alterado para 'pedido.php' para bater com o nome real do seu arquivo
        echo "<script>alert('Pedido cadastrado com sucesso com os itens do carrinho!'); window.location.href='pedido.php';</script>";

    } catch (PDOException $e) {
        // Se algo falhar, desfaz tudo o que foi feito para não deixar dados órfãos
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Erro ao salvar o pedido: " . $e->getMessage());
    }
}
?>