<?php
require 'conecta.php';

// Função para formatar o preço visualmente (Movida para o topo para evitar erros)
function number_with_cents($value) { 
    return number_format($value, 2, ',', '.'); 
}

// 1. Busca todos os clientes para o select
$sql_clientes = "SELECT id_cliente, nome FROM cliente ORDER BY nome ASC";
$stmt_clientes = $pdo->query($sql_clientes);
$clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

// 2. Busca todos os produtos incluindo a descrição, sabor e preço para o carrinho
$sql_produtos = "SELECT id_produto, descricao, sabor, preco FROM produto ORDER BY descricao ASC";
$stmt_produtos = $pdo->query($sql_produtos);
$produtos = $stmt_produtos->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br">
<head>
    <title>Pedidos com Carrinho</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/admin_menu.css">
    <link rel="stylesheet" href="css/buton.css" >
    <style>
        /* Estilos rápidos para organizar o carrinho */
        .item-carrinho { display: flex; gap: 10px; margin-bottom: 8px; align-items: center; }
        .btn-adicionar { background-color: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; }
        .btn-remover { background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; }
    </style>
    <?php include 'menu.php'; ?>
</head>
<body>
<main>
    <div>
        <form action="cadastra_pedido.php" method="post">
            <h2>Cadastro de Pedido</h2>
            
            <label for="id_cliente">Selecione o Cliente:</label>
            <select name="id_cliente" required style="width: 100%; padding: 8px; margin-bottom: 15px;">
                <option value="">-- Escolha um Cliente --</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>"><?= $cliente['nome'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="status_entrega">Status da Entrega:</label>
            <input type="text" name="status_entrega" value="Pendente">

            <label for="endereco_pedido">Endereço de Entrega:</label>
            <input type="text" name="endereco_pedido" placeholder="Se vazio, usa o do cliente">

            <fieldset style="margin: 15px 0; padding: 15px; border: 1px solid #ccc; border-radius: 5px;">
                <legend><strong>Carrinho de Produtos</strong></legend>
                
                <div id="container-produtos">
                    <div class="item-carrinho">
                        <select name="produtos[]" required style="padding: 5px; width: 60%;">
                            <option value="">-- Selecione o Produto --</option>
                            <?php foreach ($produtos as $prod): ?>
                                <option value="<?= $prod['id_produto'] ?>">
                                    <?= $prod['descricao'] ?> (<?= $prod['sabor'] ?>) - R$ <?= number_with_cents($prod['preco']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="quantidades[]" value="1" min="1" style="width: 15%; padding: 5px;" placeholder="Qtd">
                    </div>
                </div>
                
                <button type="button" class="btn-adicionar" onclick="adicionarProduto()">+ Adicionar outro item</button>
            </fieldset>
            
            <input type="submit" value="Salvar Pedido">
        </form>
    </div>

    <script>
    function adicionarProduto() {
        const container = document.getElementById('container-produtos');
        const novoItem = container.firstElementChild.cloneNode(true);
        
        novoItem.querySelector('select').value = "";
        novoItem.querySelector('input').value = "1";
        
        // Adiciona um botão para remover a linha caso o usuário desista do item
        if(!novoItem.querySelector('.btn-remover')){
            const btnRemover = document.createElement('button');
            btnRemover.type = 'button';
            btnRemover.className = 'btn-remover';
            btnRemover.innerText = 'X';
            btnRemover.onclick = function() { this.parentElement.remove(); };
            novoItem.appendChild(btnRemover);
        }
        
        container.appendChild(novoItem);
    }
    </script>

    <section>
        <h1 style="text-align: center;">Tabela de Pedidos</h1>
        <div class="tabela-produtos">
            <?php
            $sql = "SELECT p.id_pedido, p.id_cliente AS id_do_cliente, p.status_entrega, p.endereco_pedido, c.nome AS nome_cliente 
                    FROM pedido p 
                    INNER JOIN cliente c ON p.id_cliente = c.id_cliente 
                    ORDER BY p.id_pedido ASC";
            $stmt = $pdo->query($sql);
            
            echo "<table border=1 style='width:100%; text-align:left; border-collapse: collapse;'>";
            echo " <tr>
                    <th>Nº Pedido</th>
                    <th>ID Cliente</th>
                    <th>Nome do Cliente</th>
                    <th>Status</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                   </tr>";
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo " <tr>";
                echo " <td>" . $row['id_pedido'] . "</td>";
                echo " <td>" . $row['id_do_cliente'] . "</td>"; 
                echo " <td>" . htmlspecialchars($row['nome_cliente']) . "</td>";  
                echo " <td>" . htmlspecialchars($row['status_entrega']) . "</td>";
                echo " <td>" . htmlspecialchars($row['endereco_pedido']) . "</td>";
                echo " <td>
                        <a href='edit_pedido.php?id=" . $row['id_pedido'] . "' ' class='btn-acao btn-editar'>Editar</a>
                        <a href='deleta_pedido.php?id=" . $row['id_pedido'] . "' class='btn-acao btn-excluir' onclick=\"return confirm('Deseja realmente apagar este pedido e todos os seus itens?');\">Deletar</a>
                       </td>";
                echo " </tr>";
            }
            echo "</table>";
            ?>
        </div>
    </section>
</main>
</body>
</html>