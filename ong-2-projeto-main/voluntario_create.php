
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
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $idade = trim($_POST['idade'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $data_inicio = $_POST['data_inicio'] ?? '';
    $horario_inicio = $_POST['horario_inicio'] ?? '';
    $horario_termino = $_POST['horario_termino'] ?? '';
    $observacao = ($_POST['observacao'] ?? '');

    if (isset($_POST['disponibilidade']) && is_array($_POST['disponibilidade'])) {
    $disponibilidade = implode(', ', $_POST['disponibilidade']);
    } else {
        $disponibilidade = '';
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    if ($nome === '' || $telefone === '') {
        $erro = "Preencha ao menos nome e telefone.";
    } else {
        $sql = "INSERT INTO voluntario (nome, email, idade, telefone , disponibilidade, data_inicio, horario_inicio, horario_termino, observacao)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $idade, $telefone, $disponibilidade, $data_inicio, $horario_inicio, $horario_termino, $observacao]);
        $_SESSION['mensagem_sucesso'] = "voluntario criado com sucesso.";
        header("Location: voluntario.php");
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
  <title>Cadastrar Voluntário</title>

  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/voluntario-create.css" />
</head>

<body>
  <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
  ?>
<main>
  <div class="voluntario-container-form">

    <?php if ($erro): ?>
      <p class="erro-msg"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form method="post">
      <h2>Cadastrar Voluntário</h2>

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
          <label>Telefone</label>
          <input type="text" name="telefone" required>
        </div>

        <div>
          <label>Data início</label>
          <input type="date" name="data_inicio" required>
        </div>

        <!-- CHECKBOXES EM GRADE -->
        <div class="checkbox-grid">
          <label class="label-full">Disponibilidade</label>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Segunda">
            <span class="spanDispo">Segunda-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Terça">
            <span class="spanDispo">Terça-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Quarta">
            <span class="spanDispo">Quarta-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Quinta">
            <span class="spanDispo">Quinta-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Sexta">
            <span class="spanDispo">Sexta-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Sábado">
            <span class="spanDispo">Sábado</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Domingo">
            <span class="spanDispo">Domingo</span>
          </div>
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
        <a class="btn btn-cancelar" href="voluntario.php">Cancelar</a>
      </div>
    </form>

  </div>
</main>
</body>
</html>
