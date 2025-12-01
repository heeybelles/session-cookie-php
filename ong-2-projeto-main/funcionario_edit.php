
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

//Edicao funcionario

   $query = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
        $id= $_GET['id'];
        $query->execute([$id]);
        $usuario = $query->fetch();

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) {
        header("Location: dashboard.php");
        exit;
    }

$erro = '';

$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
$stmt->execute([$id]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: funcionario.php");
    exit;
}


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
        $sql = "UPDATE usuario SET nome=?, email=?, idade=?, senha=? , cargo_funcionario=?, nivel=?, data_admissao_funcionario=?, horario_inicio=?, horario_termino=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $idade, $senhaHash, $cargo, $nivel, $data_inicio, $horario_inicio, $horario_termino, $id]);
        $_SESSION['mensagem_sucesso'] = "Funcionário atualizado com sucesso.";
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
  <title>Editar Funcionário</title>

  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/funcionario-edit.css" />
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

    <form method="post">
      <h2>Editar Funcionário</h2>

      <div class="form-grid">

        <div>
          <label>Nome</label>
          <input type="text" name="nome" required
                 value="<?= htmlspecialchars($usuario['nome']) ?>">
        </div>

        <div>
          <label>Idade</label>
          <input type="text" name="idade" required
                 value="<?= htmlspecialchars($usuario['idade']) ?>">
        </div>

        <div>
          <label>Email</label>
          <input type="email" name="email" required
                 value="<?= htmlspecialchars($usuario['email']) ?>">
        </div>

        <div>
          <label>Senha</label>
          <input type="password" name="senha" required
                 value="">
        </div>

        <div>
          <label>Cargo</label>
          <input type="text" name="cargo" required
                 value="<?= htmlspecialchars($usuario['cargo_funcionario']) ?>">
        </div>

        <div>
          <label>Data de Início</label>
          <input type="date" name="data_inicio" required
                 value="<?= $usuario['data_admissao_funcionario'] ?>">
        </div>

        <div>
          <label>Nível</label>
          <select name="nivel">
            <option value="Administrador" 
              <?= $usuario['nivel'] === 'Administrador' ? 'selected' : '' ?>>
              Administrador
            </option>

            <option value="Padrão"
              <?= $usuario['nivel'] === 'Padrão' ? 'selected' : '' ?>>
              Padrão
            </option>
          </select>
        </div>

        <div>
          <label>Horário início</label>
          <input type="time" name="horario_inicio" required
                 value="<?= $usuario['horario_inicio'] ?>">
        </div>

        <div>
          <label>Horário término</label>
          <input type="time" name="horario_termino"
                 value="<?= $usuario['horario_termino'] ?>">
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
