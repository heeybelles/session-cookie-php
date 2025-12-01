
<?php
require 'config.php';
session_start();

  // Faz o menu receber o item ativo
  $pagina = basename($_SERVER['PHP_SELF'], ".php");
  
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
if (!isset($_SESSION['usuario_nivel']) || $_SESSION['usuario_nivel'] !== 'Administrador') {
    header("Location: dashboard.php");
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
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $idade = trim($_POST['idade'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $cargo = ($_POST['cargo'] ?? '');
    $nivel = ($_POST['nivel'] ?? '');
    $data_inicio = $_POST['data_inicio'] ?? '';
    $horario_inicio = $_POST['horario_inicio'] ?? '';
    $horario_termino = $_POST['horario_termino'] ?? '';
    
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    if ($nome === '' || $data_inicio === '' || $horario_inicio === '') {
        $erro = "Preencha ao menos título, data e horário de início.";
    } else {
        $sql = "INSERT INTO usuario (nome, email, idade, senha , cargo_funcionario, nivel, data_admissao_funcionario, horario_inicio, horario_termino)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $idade, $senhaHash, $cargo, $nivel, $data_inicio, $horario_inicio, $horario_termino]);
        $_SESSION['mensagem_sucesso'] = "Funcionário criado com sucesso.";
        header("Location: funcionario.php");
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
  <title>Cadastrar Funcionário</title>

  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/funcionario-create.css" />
</head>

<body>
  <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
  ?>
<main>
  <div class="funcionario-container-form">

    <?php if ($erro): ?>
      <p class="erro-msg"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <h2>Cadastrar Funcionário</h2>

      <div class="form-grid">
        <div>
          <label>Nome</label>
          <input type="text" name="nome" required>
        </div>

        <div>
          <label>Idade</label>
          <input type="text" name="idade" required>
        </div>

        <div>
          <label>Email</label>
          <input type="email" name="email" required>
        </div>

        <div>
          <label>Senha</label>
          <input type="password" name="senha" required>
        </div>

        <div>
          <label>Cargo</label>
          <input type="text" name="cargo" required>
        </div>

        <div>
          <label>Data início</label>
          <input type="date" name="data_inicio" required>
        </div>

        <div>
          <label>Nível</label>
          <select name="nivel">
            <option value="Administrador">Administrador</option>
            <option value="Padrão">Padrão</option>
          </select>
        </div>

        <div>
          <label>Horário início</label>
          <input type="time" name="horario_inicio" required>
        </div>

        <div>
          <label>Horário término</label>
          <input type="time" name="horario_termino">
        </div>
      </div>

      <div class="form-actions">
        <button class="btn" type="submit">Salvar</button>
        <a class="btn btn-cancelar" href="funcionario.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>
</body>
</html>
