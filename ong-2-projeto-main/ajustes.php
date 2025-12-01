<?php 
require 'config.php';
session_start();

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

$fotoUsuario = !empty($usuario['foto'])
    ? htmlspecialchars($usuario['foto'])
    : "https://static.vecteezy.com/system/resources/previews/036/280/651/original/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-illustration-vector.jpg";


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="img/iconLogo.png">
  <title>Ajustes</title>
  <link rel="stylesheet" type="text/css" href="css/menu.css">
  <link rel="stylesheet" type="text/css" href="css/ajustes.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>
<body class="tema-<?php echo $_SESSION['tema']; ?>">
    <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
    ?>
   
   
   <main class="container-ajustes">

    <h1 class="titulo-ajustes">Ajustes</h1>
    <legend class="subtitulo-ajustes">
        Configurações do sistema e preferências do usuário
    </legend>

    <fieldset class="caixa-ajustes">
        <form action="includes/ajuste-config.php" method="post" class="form-ajustes">

            <div class="ajuste-item">
                <label for="tema"><i class="fa-regular fa-moon"></i><i class="fa-solid fa-sun"></i> Tema do Sistema:</label>
                <select id="tema" name="tema">
                    <option value="claro" <?php if($_SESSION['tema'] == "claro") echo "selected"; ?>>Claro</option>
                    <option value="escuro" <?php if($_SESSION['tema'] == "escuro") echo "selected"; ?>>Escuro</option>
                </select>
            </div>

            <div class="ajuste-item">
                <label for="notificacoes"><i class="fa-solid fa-bell"></i> Notificações:</label>
                <select id="notificacoes" name="notificacoes">
                    <option value="ativado"  <?php if($_SESSION['notificacoes'] == "ativado") echo "selected"; ?>>Ativado</option>
                    <option value="desativado"  <?php if($_SESSION['notificacoes'] == "desativado") echo "selected"; ?>>Desativado</option>
                </select>
            </div>

            <div class="ajuste-item">
                <label><i class="fa-solid fa-text-height" ></i> Preferências de Exibição:</label>

                <div class="controle-fonte">
                    <button id="diminuirFonte" type="button" class="btn-fonte">−</button>
                    <input type="text" id="tamanhoFonte" value="<?php echo $_SESSION['fonte']; ?>px" disabled>
                    <button id="aumentarFonte" type="button" class="btn-fonte">+</button>
                </div>
            </div>

            <div class="ajuste-item">
                <button type="submit" class="btn-salvar">Salvar</button>
            </div>

        </form>
    </fieldset>

</main>
</body>
</html>