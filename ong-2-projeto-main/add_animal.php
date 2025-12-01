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


$tipos_animais = ['Cachorro', 'Gato', 'Coelho', 'Ave'];
$portes = ['Pequeno', 'Médio', 'Grande'];
$vacinas = ['Sim', 'Não'];
$status_animais = ['Chegou', 'Em avaliação', 'Em adoção', 'Adotado'];


$nome = trim($_POST['nome']);
$tipo_animal = $_POST['tipo_animal'] ?? '';
$vacina = $_POST['vacina'] ?? '';
$raca = trim($_POST['raca']);
$descricao = trim($_POST['descricao']);
$status = $_POST['status'] ?? 'Chegou';
$observacao = trim($_POST['observacao_animal']);
$data_chegada = $_POST['data_chegada'] ?? '';
$porte = $_POST['porte'] ?? 'Médio';

if (!in_array($tipo_animal, $tipos_animais)) {
    die("Tipo de animal inválido.");
}

if (!in_array($porte, $portes)) {
    die("Porte inválido.");
}

if (!in_array($vacina, $vacinas)) {
    die("Valor de vacina inválido.");
}

if (!in_array($status, $status_animais)) {
    die("Status inválido.");
}

    $foto = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid('animal_', true) . '.' . $extensao;
        $destino = 'images/animal/' . $novo_nome;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $foto = $destino;
        }
    }

   $sql = 'INSERT INTO animal 
(nome, tipo_animal, raca, descricao, foto, status, vacinado, observacao_animal, data_chegada, porte) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';


    $query = $pdo->prepare($sql);
    $query->execute([$nome, $tipo_animal, $raca, $descricao, $foto, $status,$vacina, $observacao, $data_chegada, $porte]);

    echo "<script>
        alert('Animal cadastrado com sucesso!');
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
  <title>Cadastrar Animal </title>

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

  <form method="post" enctype="multipart/form-data" class="form-grid">
    <p class="txtTitle">Cadastrar Animal</p>
    <div class="colunas-container">
        <div class="coluna-esq">
            <label>Nome do Animal:</label>
            <input type="text" name="nome" required>

            <label>Tipo do Animal:</label>
            <select name="tipo_animal" required>
                <option value="">Selecione</option>
                <option value="Cachorro">Cachorro</option>
                <option value="Gato">Gato</option>
                <option value="Coelho">Coelho</option>
                <option value="Ave">Ave</option>
            </select>

            <label>Raça:</label>
            <input type="text" name="raca" required>


            <label>Porte:</label>
            <select name="porte" required>
                <option value="Pequeno">Pequeno</option>
                <option value="Médio">Médio</option>
                <option value="Grande">Grande</option>
            </select>

            <label>Status:</label>
            <select name="status">
                <option value="Chegou">Chegou</option>
                <option value="Em avaliação">Em avaliação</option>
                <option value="Em adoção">Em adoção</option>
                <option value="Adotado">Adotado</option>
            </select>
            
            <label>Foto do Animal:</label>
            <input type="file" name="foto" accept="image/*">
        </div>

        <div class="coluna-dir">

             <label>Vacinado?</label>
            <select name="vacina">
                <option value="Sim">Sim</option>
                <option value="Não">Não</option>
            </select>

            <label>Data de Chegada:</label>
            <input type="date" name="data_chegada" required>

            <label>Descrição:</label>
            <textarea name="descricao" rows="4" required></textarea>

            <label>Observação:</label>
            <textarea name="observacao_animal" rows="3"></textarea>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn" type="submit">Salvar</button>
        <a class="btn btn-cancelar" href="animal.php">Cancelar</a>
    </div>
</form>
</main>
</body>
</html>
