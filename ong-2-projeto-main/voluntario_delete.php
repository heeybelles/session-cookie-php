
<?php
require 'config.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: voluntario.php");
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM voluntario WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['mensagem_sucesso'] = "Voluntário excluído com sucesso.";
} catch (Exception $e) {
    $_SESSION['mensagem_erro'] = "Erro ao excluir: " . $e->getMessage();
}

header("Location: voluntario.php");
exit;
