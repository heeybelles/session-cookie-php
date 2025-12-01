  
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Tela de Cadastro</title>
</head>
<body>
<main>
    <h1>Cadastro de Usuário</h1>
    <p>Preencha o formulário abaixo para criar uma nova conta:</p><br> 
    <div class="container">
    <fieldset>
    <form action="#" method="POST">
        <br>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" >

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" >
        <br>
        <input type="submit" value="Cadastrar">
    </form>
    </fieldset>
    </div>
</main>
  <?php 
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['nome']) && !empty($_POST['email']) && !empty($_POST['senha'])) {

        $nome  = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        
        $dados = fopen("dados.txt", "a");
        fwrite( $dados,"\n". $nome . "," . $email . "," . $senha . "\n");
        fclose($dados);
        setcookie("email", $email, time() + 60 * 60 * 24, "/"); 
        header('Location: login.php');
    }
    else{
         echo "<script>
        alert('Preencha todos os campos!');
        
        </script>";
        exit;
    }
    
}
 ?>
</body>
</html>

