
<?php
require 'config.php';
session_start();

  // Faz o menu receber o item ativo
  $pagina = basename($_SERVER['PHP_SELF'], ".php");
  
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


$erro = '';
$sucesso = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $item_nome   = trim($_POST['item_nome'] ?? '');
    $tipo        = trim($_POST['tipo'] ?? '');
    $quantidade  = trim($_POST['quantidade'] ?? '');
    $validade    = $_POST['validade'] ?? '';
    $observacao  = ($_POST['observacao'] ?? '');

    if ($item_nome === '' || $tipo === '' || $quantidade === '') {
        $erro = "Preencha Item, Tipo e Quantidade.";
    } else {

        $sql = "INSERT INTO estoque (item_nome, tipo, quantidade, validade, observacao)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$item_nome, $tipo, $quantidade, $validade, $observacao]);

        $_SESSION['mensagem_sucesso'] = "Item adicionado ao estoque.";
        header("Location: estoque.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="img/iconLogo.png">
  <title>Cadastrar Estoque</title>

  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/estoque-create.css" />
</head>

<body>
  <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
  ?>
<main>
  <div class="estoque-container-form">

    <?php if ($erro): ?>
      <p class="erro-msg"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <h2 class="titulo-form">Adicionar Item</h2>

            <div class="form-grid">

            <div class="campo">
                <label>Item</label>
                <input type="text" name="item_nome" required>
            </div>

            <div class="campo">
                <label>Tipo</label>
                <select name="tipo" required>
                    <option value="Ração">Ração</option>
                    <option value="Medicamento">Medicamento</option>
                    <option value="Higienicos">Higiênicos</option>
                    <option value="Cobertores">Cobertores</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>

            <div class="campo">
                <label>Quantidade</label>
                <input type="number" name="quantidade" required>
            </div>

            <div class="campo">
                <label>Validade</label>
                <input type="date" name="validade">
            </div>

            <div class="campo">
                <label>Observação</label>
                <textarea name="observacao"></textarea>
            </div>

        </div>

        <div class="botoes">
            <button type="submit" class="btn-salvar">Salvar</button>
            <a class="btn-cancelar" href="estoque.php">Cancelar</a>
        </div>

    </form>
  </div>
</main>
</body>
</html>
