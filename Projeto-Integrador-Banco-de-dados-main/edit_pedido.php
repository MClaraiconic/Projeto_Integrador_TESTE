<?php
require 'conecta.php';

function number_with_cents($value) { 
    return number_format($value, 2, ',', '.'); 
}

// Carrega os dados auxiliares para montar a tela
$clientes = $pdo->query("SELECT id_cliente, nome FROM cliente ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
$produtos = $pdo->query("SELECT id_produto, descricao, sabor, preco FROM produto ORDER BY descricao ASC")->fetchAll(PDO::FETCH_ASSOC);

// 1. BUSCA O PEDIDO E SEUS ITENS ATUAIS
if (isset($_GET['id'])) {
    $id_pedido = $_GET['id'];
    
    // Busca dados do Pedido
    $stmt = $pdo->prepare("SELECT * FROM pedido WHERE id_pedido = :id");
    $stmt->bindParam(':id', $id_pedido, PDO::PARAM_INT);
    $stmt->execute();
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) { die("Pedido não encontrado."); }

    // Busca os produtos que já estão dentro desse pedido (Carrinho Atual)
    // Nota: Certifique-se se as colunas da sua tabela 'item_pedido' batem com essas
    $stmt_itens = $pdo->prepare("SELECT id_produto, quantidade FROM itens_pedido WHERE id_pedido = :id");
    $stmt_itens->bindParam(':id', $id_pedido, PDO::PARAM_INT);
    $stmt_itens->execute();
    $itens_cadastrados = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);
}

// 2. SALVA AS ATUALIZAÇÕES DO PEDIDO E DO CARRINHO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pedido = $_POST['id_pedido'];
    $id_cliente = $_POST['id_cliente'];
    $status_entrega = $_POST['status_entrega'];
    $endereco_pedido = $_POST['endereco_pedido'];
    
    $lista_produtos = $_POST['produtos'];
    $lista_quantidades = $_POST['quantidades'];

    try {
        $pdo->beginTransaction();

        // Atualiza a tabela principal do pedido
        $sql_update = "UPDATE pedido SET id_cliente = :id_cliente, status_entrega = :status, endereco_pedido = :endereco WHERE id_pedido = :id_pedido";
        $stmt_up = $pdo->prepare($sql_update);
        $stmt_up->bindParam(':id_cliente', $id_cliente);
        $stmt_up->bindParam(':status', $status_entrega);
        $stmt_up->bindParam(':endereco', $endereco_pedido);
        $stmt_up->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt_up->execute();

        // Remove todos os itens antigos do carrinho deste pedido
        $pdo->prepare("DELETE FROM itens_pedido WHERE id_pedido = ?")->execute([$id_pedido]);

        // Insere a nova lista atualizada vinda do formulário
        $sql_insere_item = "INSERT INTO itens_pedido (id_pedido, id_produto, quantidade) VALUES (:id_pedido, :id_produto, :quantidade)";
        $stmt_item = $pdo->prepare($sql_insere_item);

        for ($i = 0; $i < count($lista_produtos); $i++) {
            if (!empty($lista_produtos[$i])) {
                $stmt_item->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
                $stmt_item->bindValue(':id_produto', $lista_produtos[$i], PDO::PARAM_INT);
                $stmt_item->bindValue(':quantidade', $lista_quantidades[$i], PDO::PARAM_INT);
                $stmt_item->execute();
            }
        }

        $pdo->commit();
        header("Location: pedidos.php?msg=pedido_atualizado");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao atualizar o pedido: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <title>Editar Pedido</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/admin_menu.css">
    <link rel="stylesheet" href="css/buton.css">
    <style>
        .item-carrinho { display: flex; gap: 10px; margin-bottom: 8px; align-items: center; }
        .btn-adicionar { background-color: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; }
        .btn-remover { background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; }
    </style>
    <?php include 'menu.php'; ?>
</head>
<body>
<main>
    <div>
        <form action="editar_pedido.php" method="post">
            <h2>Editar Pedido Nº <?= $pedido['id_pedido'] ?></h2>
            <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
            
            <label for="id_cliente">Cliente:</label>
            <select name="id_cliente" required style="width: 100%; padding: 8px; margin-bottom: 15px;">
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>" <?= $cliente['id_cliente'] == $pedido['id_cliente'] ? 'selected' : '' ?>>
                        <?= $cliente['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="status_entrega">Status da Entrega:</label>
            <input type="text" name="status_entrega" value="<?= htmlspecialchars($pedido['status_entrega']) ?>">

            <label for="endereco_pedido">Endereço de Entrega:</label>
            <input type="text" name="endereco_pedido" value="<?= htmlspecialchars($pedido['endereco_pedido']) ?>">

            <fieldset style="margin: 15px 0; padding: 15px; border: 1px solid #ccc; border-radius: 5px;">
                <legend><strong>Carrinho de Produtos</strong></legend>
                
                <div id="container-produtos">
                    <?php if (!empty($itens_cadastrados)): ?>
                        <?php foreach ($itens_cadastrados as $index => $item_atual): ?>
                            <div class="item-carrinho">
                                <select name="produtos[]" required style="padding: 5px; width: 60%;">
                                    <option value="">-- Selecione o Produto --</option>
                                    <?php foreach ($produtos as $prod): ?>
                                        <option value="<?= $prod['id_produto'] ?>" <?= $prod['id_produto'] == $item_atual['id_produto'] ? 'selected' : '' ?>>
                                            <?= $prod['descricao'] ?> (<?= $prod['sabor'] ?>) - R$ <?= number_with_cents($prod['preco']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" name="quantidades[]" value="<?= $item_atual['quantidade'] ?>" min="1" style="width: 15%; padding: 5px;">
                                <?php if ($index > 0): ?>
                                    <button type="button" class="btn-remover" onclick="this.parentElement.remove()">X</button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="item-carrinho">
                            <select name="produtos[]" required style="padding: 5px; width: 60%;">
                                <option value="">-- Selecione o Produto --</option>
                                <?php foreach ($produtos as $prod): ?>
                                    <option value="<?= $prod['id_produto'] ?>"><?= $prod['descricao'] ?> (<?= $prod['sabor'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" name="quantidades[]" value="1" min="1" style="width: 15%; padding: 5px;">
                        </div>
                    <?php endif; ?>
                </div>
                
                <button type="button" class="btn-adicionar" onclick="adicionarProduto()">+ Adicionar outro item</button>
            </fieldset>
            
            <input type="submit" value="Atualizar Pedido">
            <a href="pedido.php" style="margin-left:10px; color:gray;">Cancelar</a>
        </form>
    </div>

    <div id="modelo-clone" style="display: none;">
        <div class="item-carrinho">
            <select name="produtos[]" style="padding: 5px; width: 60%;">
                <option value="">-- Selecione o Produto --</option>
                <?php foreach ($produtos as $prod): ?>
                    <option value="<?= $prod['id_produto'] ?>">
                        <?= $prod['descricao'] ?> (<?= $prod['sabor'] ?>) - R$ <?= number_with_cents($prod['preco']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="quantidades[]" value="1" min="1" style="width: 15%; padding: 5px;">
            <button type="button" class="btn-remover" onclick="this.parentElement.remove()">X</button>
        </div>
    </div>

    <script>
    function adicionarProduto() {
        const container = document.getElementById('container-produtos');
        // Clona a partir do nosso modelo oculto estático para evitar herdar valores já selecionados
        const modelo = document.getElementById('modelo-clone').firstElementChild;
        const novoItem = modelo.cloneNode(true);
        container.appendChild(novoItem);
    }
    </script>
</main>
</body>
</html>