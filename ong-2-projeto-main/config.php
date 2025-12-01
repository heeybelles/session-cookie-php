<?php

$host = 'localhost';
$banco = 'bd_associacao';
$usuario = 'root';
$senha = '';


 try{
    $pdo = new PDO("mysql:host=$host;dbname=$banco", $usuario, $senha);

} catch(PDOException $e){
    die("Erro na conexão " . $e->getMessage());
}
?>