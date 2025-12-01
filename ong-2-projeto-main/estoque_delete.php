<?php
require 'config.php';
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) { header("Location: estoque.php"); exit; }

try {
    $stmt = $pdo->prepare("DELETE FROM estoque WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['mensagem_sucesso'] = "Item excluído.";
} catch (Exception $e) {
    $_SESSION['mensagem_erro'] = "Erro ao excluir item: " . $e->getMessage();
}
header("Location: estoque.php");
exit;
