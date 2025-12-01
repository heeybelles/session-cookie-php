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

//Edicao Agendaa
 $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: dashboard.php");
    exit;
}

$erro = '';

$stmt = $pdo->prepare("SELECT * FROM agenda_eventos WHERE id = ?");
$stmt->execute([$id]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_termino = $_POST['data_termino'] ?? null;
    $horario_inicio = $_POST['horario_inicio'] ?? '';
    $horario_termino = $_POST['horario_termino'] ?? null;
    $status = $_POST['status'] ?? 'A concluir';
    $local = trim($_POST['local'] ?? '');

    if ($titulo === '' || $data_inicio === '' || $horario_inicio === '') {
        $erro = "Preencha ao menos título, data e horário de início.";
    } else {
        $sql = "UPDATE agenda_eventos SET titulo=?, descricao=?, data_inicio=?, data_termino=?, horario_inicio=?, horario_termino=?, status=?, local=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $descricao, $data_inicio, $data_termino ?: null, $horario_inicio, $horario_termino ?: null, $status, $local, $id]);

        $_SESSION['mensagem_sucesso'] = "Evento atualizado com sucesso.";
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
  <title>Editar Evento</title>

  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/agenda-edit.css" />
</head>

<body>
  <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
  ?>
<main>
 <div class="container">

  <form method="post" action="">
    
    <h2>Editar evento</h2>

    <label>Título</label>
    <input type="text" name="titulo" required value="<?= htmlspecialchars($evento['titulo']) ?>">

    <label>Descrição</label>
    <textarea name="descricao"><?= htmlspecialchars($evento['descricao']) ?></textarea>

    <div class="linha-dupla">
        <div>
            <label>Data início</label>
            <input type="date" name="data_inicio" required value="<?= $evento['data_inicio'] ?>">
        </div>

        <div>
            <label>Data término (opcional)</label>
            <input type="date" name="data_termino" value="<?= $evento['data_termino'] ?>">
        </div>
    </div>

    <div class="linha-dupla">
        <div>
            <label>Horário início</label>
            <input type="time" name="horario_inicio" required value="<?= $evento['horario_inicio'] ?>">
        </div>

        <div>
            <label>Horário término (opcional)</label>
            <input type="time" name="horario_termino" value="<?= $evento['horario_termino'] ?>">
        </div>
    </div>

    <div class="linha-dupla">
        <div>
            <label>Status</label>
            <select name="status">
                <option value="A concluir" <?= $evento['status'] === 'A concluir' ? 'selected' : '' ?>>A concluir</option>
                <option value="Em breve" <?= $evento['status'] === 'Em breve' ? 'selected' : '' ?>>Em breve</option>
                <option value="Concluido" <?= $evento['status'] === 'Concluido' ? 'selected' : '' ?>>Concluido</option>
            </select>
        </div>

        <div>
            <label>Local</label>
            <input type="text" name="local" value="<?= htmlspecialchars($evento['local']) ?>">
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

