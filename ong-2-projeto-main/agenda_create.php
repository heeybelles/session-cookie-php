
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
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_termino = $_POST['data_termino'] ?? null;
    $horario_inicio = $_POST['horario_inicio'] ?? '';
    $horario_termino = $_POST['horario_termino'] ?? null;
    $status = $_POST['status'] ?? 'A concluir';
    $local = trim($_POST['local'] ?? '');

    // Validações básicas
    if ($titulo === '' || $data_inicio === '' || $horario_inicio === '') {
        $erro = "Preencha ao menos título, data e horário de início.";
    } else {
        $sql = "INSERT INTO agenda_eventos (titulo, descricao, data_inicio, data_termino, horario_inicio, horario_termino, status, local)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $descricao, $data_inicio, $data_termino ?: null, $horario_inicio, $horario_termino ?: null, $status, $local]);
        $_SESSION['mensagem_sucesso'] = "Evento criado com sucesso.";
        header("Location: dashboard.php");
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
  <title>Criar Evento</title>

  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/agenda-create.css" />
</head>

<body>
  <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
  ?>
<main>
  <div class="container">
    
    <?php if ($erro): ?><p style="color:red;"><?php echo htmlspecialchars($erro); ?></p><?php endif; ?>

   <form method="post" action="">
  <h2>Adicionar evento</h2>
      <label>Título</label>
      <input type="text" name="titulo" required>

      <label>Descrição</label>
      <textarea name="descricao" rows="4"></textarea>

      <div class="linha-dupla">
        <div>
          <label>Data início</label>
          <input type="date" name="data_inicio" required>
        </div>

        <div>
          <label>Data término (opcional)</label>
          <input type="date" name="data_termino">
        </div>
      </div>

      <div class="linha-dupla">
        <div>
          <label>Horário início</label>
          <input type="time" name="horario_inicio" required>
        </div>

        <div>
          <label>Horário término (opcional)</label>
          <input type="time" name="horario_termino">
        </div>
      </div>

      <div class="linha-dupla">
        <div>
          <label>Status</label>
          <select name="status">
            <option value="A concluir">A concluir</option>
            <option value="Em breve">Em breve</option>
            <option value="Concluido">Concluido</option>
          </select>
        </div>

        <div>
          <label>Local</label>
          <input type="text" name="local">
        </div>
      </div>

        <div class="form-actions">
          <button class="btn" type="submit">Salvar</button>
          <a class="btn btn-cancelar" href="dashboard.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>
</body>
</html>
