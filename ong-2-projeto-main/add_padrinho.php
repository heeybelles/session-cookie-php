<?php
require 'config.php';
session_start();

  // Faz o menu receber o item ativo
  $pagina = basename($_SERVER['PHP_SELF'], ".php");
  
// Impede acesso sem login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $animal_id = $_POST['animal_id'] ?? '';
    $padrinho_nome = trim($_POST['padrinho_nome']);
    $padrinho_contato = trim($_POST['padrinho_contato']);
    $data_inicio = $_POST['data_inicio'] ?? '';
    $observacao = trim($_POST['observacao']);

    // Validar
    if (empty($animal_id)) {
        die("Selecione o animal.");
    }

    if (empty($padrinho_nome)) {
        die("O nome do padrinho é obrigatório.");
    }

    if (empty($data_inicio)) {
        die("A data de início é obrigatória.");
    }

    // Insert no banco
    $sql = "INSERT INTO padrinho (animal_id, padrinho_nome, padrinho_contato, data_inicio, observacao)
            VALUES (?, ?, ?, ?, ?)";

    $query = $pdo->prepare($sql);
    $query->execute([
        $animal_id,
        $padrinho_nome,
        $padrinho_contato,
        $data_inicio,
        $observacao
    ]);

    echo "<script>
        alert('Padrinho cadastrado com sucesso!');
    </script>";
    exit;
}

$animais = $pdo->query("SELECT id, nome FROM animal ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="img/iconLogo.png">
  <title>Cadastrar Padrinho </title>

  <link rel="stylesheet" type="text/css" href="css/menu.css">
  <link rel="stylesheet" type="text/css" href="css/padrinho-create.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>

<body>
  <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
  ?>

<!--FORMULARIO-->
<main class="container">
  <form method="post" class="form-grid">
      <p class="txtTitle">Cadastrar Padrinho</p>

      <div class="colunas-container">

          <div class="coluna-esq">
              <label>Animal apadrinhado:</label>
              <select name="animal_id" required>
                  <option value="">Selecione um animal</option>

                  <?php foreach ($animais as $a): ?>
                      <option value="<?= $a['id'] ?>">
                          <?= htmlspecialchars($a['nome']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
              <br>
              <label>Nome do padrinho:</label>
              <input type="text" name="padrinho_nome" required><br>

              <label>Contato:</label>
              <input type="text" name="padrinho_contato" placeholder="Telefone, email etc."><br>
          </div>

          <div class="coluna-dir">
              <label>Data de início:</label>
              <input type="date" name="data_inicio" required><br>

              <label>Observação:</label>
              <textarea name="observacao" rows="5"></textarea>
          </div>

      </div>

      <div class="form-actions">
        <button class="btn" type="submit">Salvar</button>
        <a class="btn btn-cancelar" href="padrinho.php">Cancelar</a>
    </div>

  </form>
</main>
</body>
</html>
