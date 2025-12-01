<?php

session_start();

$email_preenchido = '';
if (isset($_COOKIE['email'])) {
    $email_preenchido = htmlspecialchars($_COOKIE['email']); 
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

 
    if (!empty($_POST['email']) && !empty($_POST['senha'])) {
        
        $email_form = $_POST['email'];
        $senha_form = $_POST['senha'];
        $autenticado = false;
        $nome_usuario = '';

 
        $dados = fopen("dados.txt", "r");

        if ($dados) {
         
            while (!feof($dados)) {
                $linha = trim(fgets($dados));

                if (!empty($linha)) {
                 
                    list($nome, $email_lido, $senha_lida) = explode(",", $linha); 
                    
                   
                    if ($email_lido === $email_form && $senha_lida === $senha_form) {
                        $autenticado = true;
                        $nome_usuario = $nome;
                        break; 
                    }
                }
            }
            fclose($dados);
        }

        if ($autenticado) {
            $_SESSION['logado'] = true;
            $_SESSION['nome'] = $nome_usuario;
            $_SESSION['email'] = $email_form;

           
            header('Location: usuario.php');
            exit; 
        } else {
            
            echo "<script>alert('Email ou senha incorretos.')</script>";
        
        }
    } else {
        echo "<script>alert('Por favor, preencha todos os campos.')</script>";
        
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
<main>
     <h1>Login</h1>
      <p>Preencha o formulário abaixo para acessar a sua conta:</p><br>
<div class="container">
    <fieldset>
    <form action="#" method="post">
        <br>
        <label for="email">Email:</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            required 
            value="<?php echo $email_preenchido; ?>">

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br>

        <input type="submit" value="Login">
    </form>
</fieldset>
</main>
</body>
</html>