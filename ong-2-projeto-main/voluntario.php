
<?php
require 'config.php';
session_start();

 // Faz o menu receber o item ativo
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

//Crud voluntario

$voluntario = [];


try {
    $stmt = $pdo->query("SELECT * FROM voluntario ORDER BY nome ASC");
    $voluntario = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <title>Lista de Voluntários</title>

  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/voluntario.css" />
</head>

<body>
 <?php 
    include 'includes/menu.php';
  ?>
<main>
  <div class="titulo-btn-voluntario">
      <p class="txtTitle">Voluntários</p>

    <?php if (!empty($erro)): ?>
      <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <a class="btn-add" href="voluntario_create.php">+ Adicionar novo voluntário</a>
   </div>

 <div class="voluntario-container">
    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Idade</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Data</th>
          <th>Início</th>
          <th>Término</th>
          <th>Disponibilidade</th>
          <th>Ações</th>
        </tr>
      </thead>

      <tbody>
        <?php if (empty($voluntario)): ?>
          <tr>
            <td colspan="9" class="small">Nenhum voluntario cadastrado.</td>
          </tr>

        <?php else: ?>
          <?php foreach ($voluntario as $vol): ?>
            <tr>
                <td><?php echo htmlspecialchars($vol['nome']); ?></td>
                <td><?php echo htmlspecialchars($vol['idade']); ?></td>
                <td><?php echo htmlspecialchars($vol['email']); ?></td>
                <td><?php echo htmlspecialchars($vol['telefone']); ?></td>
                
                
                <td>
                    <?php
                    $di = date('d/m/Y', strtotime($vol['data_inicio']));
                    echo $di ;
                    ?>
                </td>
                <td>
                    <?php
                     echo "<span class='small'>" . substr($vol['horario_inicio'],0,5);
                    ?>
                </td>
                <td>
                    <?php
                    if ($vol['horario_termino']) {
                        echo "<span class='small'>" . substr($vol['horario_termino'],0,5);
                    }
                    echo "</span>";
                   ?>
                </td>  
                <td><?php echo htmlspecialchars($vol['disponibilidade']); ?></td>

              <td class="actions">
                <a class="btn" href="voluntario_edit.php?id=<?php echo $vol['id']; ?>">Editar</a>
                <a class="btn-danger" href="voluntario_delete.php?id=<?php echo $vol['id']; ?>"
                   onclick="return confirm('Deseja realmente excluir este voluntario?');">Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
</div>
   
</main>
</body>
</html>
