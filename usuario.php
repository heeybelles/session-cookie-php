<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="usuario.css">
    <title>Bem-vindo</title>
</head>
<body>
 
<fieldset>
    <form action="logout.php" method="post"><br>
       <?php
    session_start();
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header('Location: login.php');
        exit;
    }
    $nome_usuario = $_SESSION['nome'];
    $email_usuario = $_SESSION['email'];

    echo "<h1>Bem-vindo(a), $nome_usuario!</h1>";
    echo "<p>$email_usuario</p>";
    ?>
    <br>
        <input type="submit" value="Sair">
    </form>
</fildset>
</body>
</html>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Montserrat:wght@100..900&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Inter", sans-serif;
}

body {
    background-color: #f3e8ff;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

fieldset {
    width: 385px;
    height: 230px;
    border: 2px solid #ccc;
    background-color: #fff;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 20px;
}

h1 {
    text-align: center;
    margin-bottom: 10px;
    color: #761ac1;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}


input[type="submit"] {
    width: 330px;
    height: 45px;
    height: 50px;
    border: none;
    background-color: rgb(125, 16, 123);
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: rgb(145, 36, 143);
}

p {
    margin-top: 12px;
    text-align: center;
    font-size: 16px;
    color: #761ac1;
}

</style>
