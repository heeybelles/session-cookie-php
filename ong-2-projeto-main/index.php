<?php 
require 'config.php';
session_start();

$erro = ''; 

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    

   
    $query = $pdo->prepare('SELECT * FROM usuario WHERE email = ?');
    $query->execute([$email]);
    $usuario = $query->fetch(PDO::FETCH_ASSOC);

        
     if ($usuario && password_verify($senha, $usuario['senha'])) {

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_foto'] = $usuario['foto'];
    $_SESSION['usuario_nivel'] = $usuario['nivel'];

    // Ambos vão para o dashboard
    header("Location: dashboard.php");
    exit;
} else {
    $erro = "E-mail e/ou senha incorretos.";
}
}
    
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/iconLogo.png">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <h3>Faça seu login para acessar o sistema</h3><br><br>

    <form action="#" method="post"><br><br>
        <label for="email">E-mail:</label>
        <input type="email" name="email" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>

        <?php if (!empty($erro)) : ?>
            <p style="color: red; margin-top: 10px;"><?= $erro ?></p>
        <?php endif; ?>

        <input type="submit" value="Entrar"><br><br>
    </form>
</body>
</html>
