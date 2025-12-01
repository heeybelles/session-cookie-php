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


$sqlAnimais = $pdo->query("SELECT id, nome FROM animal WHERE status != 'Adotado'");
$animais = $sqlAnimais->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $animal_id = $_POST['animal_id'] ?? "";
    $adotante = trim($_POST['adotante_nome']);
    $contato = trim($_POST['adotante_contato']);
    $data = $_POST['data_adocao'];
    $processo = trim($_POST['processo_adaptacao']);

    $ins = $pdo->prepare("INSERT INTO adocao 
        (animal_id, adotante_nome, adotante_contato, data_adocao, processo_adaptacao)
        VALUES (?, ?, ?, ?, ?)");
    $ins->execute([$animal_id, $adotante, $contato, $data, $processo]);

    $upd = $pdo->prepare("UPDATE animal SET status='Adotado' WHERE id=?");
    $upd->execute([$animal_id]);

    echo "<script>
        alert('Adoção registrada com sucesso!');
    </script>";
    exit;
}


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="img/iconLogo.png">
  <title>Cadastrar Adoção </title>

  <link rel="stylesheet" type="text/css" href="css/menu.css">
  <link rel="stylesheet" type="text/css" href="css/animal-create.css">
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
      <p class="txtTitle">Registrar Adoção</p>

      <div class="colunas-container">

          <div class="coluna-esq">
              <label>Animal adotado:</label>
             <select name="animal_id" required>
            <option value="">Selecione um animal</option>
             <?php foreach ($animais as $a): ?>
                 <option value="<?= $a['id'] ?>">
             <?= htmlspecialchars($a['nome']) ?>
           </option>
             <?php endforeach; ?>
         </select>
          <br>

              <label>Nome do adotante:</label>
              <input type="text" name="adotante_nome" required><br>

              <label>Contato:</label>
              <input type="text" name="adotante_contato"><br>
          </div>

          <div class="coluna-dir">
              <label>Data da adoção:</label>
              <input type="date" name="data_adocao" required><br>

              <label>Processo de adaptação:</label>
              <textarea name="processo_adaptacao" rows="5"></textarea>
          </div>

      </div>

      <div class="form-actions">
        <button class="btn" type="submit">Salvar</button>
        <a class="btn btn-cancelar" href="adocao.php">Cancelar</a>
    </div>
  </form>
</main>
</body>
</html>
