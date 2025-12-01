
<?php
require 'config.php';
session_start();

  // Faz o menu receber o item ativo
  $pagina = basename($_SERVER['PHP_SELF'], ".php");
  
// Impede acesso sem login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$nivel = $_SESSION['usuario_nivel'] ?? "";
$nome  = $_SESSION['usuario_nome'] ?? "";
$foto  = $_SESSION['usuario_foto'] ?? "";

$usuario = [
    'nome'  => $nome,
    'nivel' => $nivel,
    'foto'  => $foto
];

// Foto padrão
$fotoUsuario = !empty($usuario['foto'])
    ? htmlspecialchars($usuario['foto'])
    : "https://static.vecteezy.com/system/resources/previews/036/280/651/original/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-illustration-vector.jpg";

//Crud estoque

$estoque = [];


try {
    $stmt = $pdo->query("SELECT * FROM estoque ORDER BY id ASC");
    $estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na query: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="img/iconLogo.png">
  <title>Estoque</title>

  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/estoque.css" />
</head>

<body>
  <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
  ?>
<main>
  
  <div class="titulo-btn-estoque">
      <p class="txtTitle">Estoque</p>

    <?php if (!empty($erro)): ?>
      <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <a class="btn-add" href="estoque_create.php">+ Adicionar novo item</a>
   </div>

 <div class="estoque-container">
    <table>
      <thead>
        <tr>
        <th>ID</th>
        <th>Item</th>
        <th>Tipo</th>
        <th>Quantidade</th>
        <th>Validade</th>
        <th>Observação</th>
        <th>Ações</th>
        </tr>
        </thead>

        <tbody>
        <?php if (empty($estoque)): ?>
            <tr>
                <td colspan="7" class="small">Nenhum item no estoque.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($estoque as $est): ?>
                <tr>
                    <td><?= htmlspecialchars($est['id']); ?></td>
                    <td><?= htmlspecialchars($est['item_nome']); ?></td>
                    <td><?= htmlspecialchars($est['tipo']); ?></td>
                    <td><?= htmlspecialchars($est['quantidade']); ?></td>

                    <td>
                        <?= $est['validade'] ? date('d/m/Y', strtotime($est['validade'])) : '—'; ?>
                    </td>

                    <td><?= htmlspecialchars($est['observacao']); ?></td>

                    <td class="actions">
                        <a class="btn" href="estoque_edit.php?id=<?= $est['id']; ?>">Editar</a>
                        <a class="btn-danger"
                        href="estoque_delete.php?id=<?= $est['id']; ?>"
                        onclick="return confirm('Deseja realmente excluir este item?');">
                        Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>

    </table>
</div>
   
</main>
</body>
</html>

