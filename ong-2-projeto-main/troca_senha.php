<?php

require 'config.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']);
$mensagem = '';

try {
    $colCheck = $pdo->prepare("SELECT COUNT(*) as cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usuario' AND COLUMN_NAME = 'primeiro_acesso'");
    $colCheck->execute();
    $hasCol = $colCheck->fetch(PDO::FETCH_ASSOC)['cnt'] > 0;

    if (!$hasCol) {
       
        $pdo->exec("ALTER TABLE usuario ADD COLUMN primeiro_acesso TINYINT(1) NOT NULL DEFAULT 1");
    
        $pdo->exec("UPDATE usuario SET primeiro_acesso = 1");
    }
} catch (Exception $e) {

}


$stmt = $pdo->prepare("SELECT id, nome, email, senha, primeiro_acesso FROM usuario WHERE id = ?");
$stmt->execute([$usuario_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

$forcar_troca = (isset($user['primeiro_acesso']) && intval($user['primeiro_acesso']) === 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirma = $_POST['confirma'] ?? '';

    if (strlen(trim($nova_senha)) < 4) {
        $mensagem = "A nova senha deve ter ao menos 4 caracteres.";
    } elseif ($nova_senha !== $confirma) {
        $mensagem = "A confirmação não confere com a nova senha.";
    } else {
        $exige_senha_atual = !$forcar_troca;

        if ($exige_senha_atual) {
            if (empty($senha_atual)) {
                $mensagem = "Informe a senha atual.";
            } else {
               
                if (!password_verify($senha_atual, $user['senha'])) {
                    $mensagem = "Senha atual incorreta.";
                }
            }
        }

        if (empty($mensagem)) {
           
            $novoHash = password_hash($nova_senha, PASSWORD_DEFAULT);

            $updateSql = "UPDATE usuario SET senha = ?, primeiro_acesso = 0 WHERE id = ?";
            $upd = $pdo->prepare($updateSql);
            $upd->execute([$novoHash, $usuario_id]);

            $_SESSION['mensagem_sucesso'] = "Senha alterada com sucesso.";

            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Alterar Senha</title>
  <link rel="icon" type="image/png" href="img/iconLogo.png">
  <link rel="stylesheet" href="css/trocarSenha.css"> 
</head>
<body>

    <h1><?php echo $forcar_troca ? 'Primeiro acesso' : 'Alterar senha'; ?></h1>
    <h3>Olá, <?php echo htmlspecialchars($user['nome']); ?>.<?php if ($forcar_troca) echo " Por segurança, altere sua senha antes de continuar."; ?></h3>

    <div class="container">

    <?php if (!empty($mensagem)): ?>
      <p class="mensagem"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <?php if (!$forcar_troca): ?>
        <label for="senha_atual">Senha atual</label>
        <input type="password" name="senha_atual" id="senha_atual" required>
      <?php endif; ?>

      <label for="nova_senha">Nova senha</label>
      <input type="password" name="nova_senha" id="nova_senha" required minlength="4">

      <label for="confirma">Confirme a nova senha</label>
      <input type="password" name="confirma" id="confirma" required minlength="4">

      <button class="btn btn-primary" type="submit">Alterar senha</button>
    </form>

   <?php if (isset($_SESSION['usuario_id']) && !$forcar_troca): ?>
        <div class="voltar-dashboard">
            <a href="dashboard.php" class="btn-voltar">Voltar</a>
        </div>
    <?php endif; ?>

  </div>
</body>
</html>
