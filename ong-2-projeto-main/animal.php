<?php
require 'config.php';
session_start();

// Verifica login
 $pagina = basename($_SERVER['PHP_SELF'], ".php");

// Impede acesso sem login
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

// Foto padrão
$fotoUsuario = !empty($usuario['foto'])
    ? htmlspecialchars($usuario['foto'])
    : "https://static.vecteezy.com/system/resources/previews/036/280/651/original/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-illustration-vector.jpg";

// Buscar animais
$animais = [];
try {
   $stmt = $pdo->query("
    SELECT a.id, a.nome, a.tipo_animal, a.raca, a.descricao, a.status, a.foto,
           (SELECT p.id FROM padrinho p WHERE p.animal_id = a.id LIMIT 1) AS tem_padrinho
    FROM animal a
    ORDER BY a.nome ASC
");

    $animais = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na query: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="icon" type="image/png" href="img/iconLogo.png">
<title>Lista de Animais</title>
<link rel="stylesheet" href="css/menu.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
<link rel="stylesheet" href="css/animais.css" />
</head>
<body class="tema-<?php echo $_SESSION['tema'] ?? 'claro'; ?>" style="font-size:<?php echo $_SESSION['fonte'] ?? 16; ?>px;">

<?php 
include 'includes/menu.php';
include 'includes/ajuste-config.php';
?>

<main>
<div class="titulo-btn-animais">
    <p class="txtTitle">Animais</p>
    <a class="btn-add" href="add_animal.php">+ Adicionar novo animal</a>
</div>

<div id="popup-container"></div>


<div class="animal-container">
<table>
    <thead>
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Raça</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Tem Padrinho?</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($animais)): ?>
        <tr><td colspan="8">Nenhum animal cadastrado.</td></tr>
        <?php else: ?>
        <?php foreach ($animais as $animal): ?>
        <tr data-id="<?php echo $animal['id']; ?>">
           <td>
        <img 
        src="<?php echo !empty($animal['foto']) ? htmlspecialchars($animal['foto']) : 'https://images.vexels.com/media/users/3/144928/isolated/preview/ebbccaf76f41f7d83e45a42974cfcd87-ilustracao-de-cachorro.png'; ?>" 
        alt="Foto de <?php echo htmlspecialchars($animal['nome']); ?>" 
        width="60" style="height: 60px ;border-radius: 100%; border: 1px solid #ccc;"
        onerror="this.onerror=null; this.src='https://images.vexels.com/media/users/3/144928/isolated/preview/ebbccaf76f41f7d83e45a42974cfcd87-ilustracao-de-cachorro.png';">
          </td>
            <td><?php echo htmlspecialchars($animal['nome']); ?></td>
            <td><?php echo htmlspecialchars($animal['tipo_animal']); ?></td>
            <td><?php echo htmlspecialchars($animal['raca']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($animal['descricao'])); ?></td>
            <td><?php echo htmlspecialchars($animal['status']); ?></td>
            <td><?php echo $animal['tem_padrinho'] > 0 ? "Sim" : "Não"; ?></td>
            <td class="actions">
             <a class="btn" href="animal_edit.php?id=<?php echo $animal['id']; ?>">Editar</a>
             <a class="btn-danger" href="animal_delete.php?id=<?php echo $animal['id']; ?>" onclick="return confirm('Deseja realmente excluir este animal?');">Excluir</a>
    
    <?php if ($animal['tem_padrinho'] > 0): ?>
        <a class="btn-padrinho" href="padrinho_edit.php?id=<?php echo $animal['id']; ?>">Alterar Padrinho</a>
    <?php else: ?>
        <a class="btn-padrinho" href="add_padrinho.php?id=<?php echo $animal['id']; ?>">Adicionar Padrinho</a>
    <?php endif; ?>
</td>

        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</div>

<center><button id="gerarRelatorio" class="btn-relatorio"
style="
    margin-top: 20px;
    margin-left: 30px;
    padding: 10px 18px;
    background: linear-gradient(180deg, #384c7a 0%, #1b2744 100%);
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s ease, transform 0.2s ease;
    outline: none;
    border: none;
    display:flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
"
onclick="window.open('relatorio_animal.php', '_blank')">Gerar Relatório</button></center>


</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('.animal-container tbody tr');
    const popupContainer = document.getElementById('popup-container');

    rows.forEach(row => {
        row.addEventListener('click', e => {
            if (e.target.closest('a')) return;

            const animalId = row.getAttribute('data-id');
            fetch('popup_animal.php?id=' + animalId)
                .then(res => res.text())
                .then(html => {
                    popupContainer.innerHTML = html;
                    const popup = popupContainer.querySelector('.popup-animal');
                    if (popup) popup.style.display = 'block';

                    // Fecha o popup
                    popup.querySelector('.close-popup').addEventListener('click', () => {
                        popup.style.display = 'none';
                    });
                });
        });
    });
});
</script>

</body>
</html>
