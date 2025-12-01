
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
if (!isset($_SESSION['usuario_nivel']) || $_SESSION['usuario_nivel'] !== 'Administrador') {
    header("Location: dashboard.php");
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

//Crud funcionario

$funcionario = [];


try {
    $stmt = $pdo->query("SELECT * FROM usuario ORDER BY nome ASC");
    $funcionario = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <title>Lista de Funcionários</title>

  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/funcionario.css" />
</head>

<body>
  <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
  ?>
<main>
  <div class="titulo-btn-funcionario">
      <p class="txtTitle">Funcionários</p>

    <?php if (!empty($erro)): ?>
      <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <a class="btn-add" href="funcionario_create.php">+ Adicionar novo funcionário</a>
   </div>

 <div class="funcionario-container">
    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Idade</th>
          <th>Email</th>
          <th>Cargo</th>
          <th>Nível</th>
          <th>Admissão</th>
          <th>Início</th>
          <th>Saída</th>
          <th>Ações</th>
        </tr>
      </thead>

      <tbody>
        <?php if (empty($funcionario)): ?>
          <tr><td colspan="9" class="small">Nenhum Funcionário cadastrado.</td></tr>

        <?php else: ?>
          <?php foreach ($funcionario as $fun): ?>
            <tr>
                <td><?php echo htmlspecialchars($fun['nome']); ?></td>
                <td><?php echo htmlspecialchars($fun['idade']); ?></td>
                <td><?php echo htmlspecialchars($fun['email']); ?></td>
                <td><?php echo htmlspecialchars($fun['cargo_funcionario']); ?></td>
                <td><?php echo htmlspecialchars($fun['nivel']); ?></td>
                
                <td>
                    <?php
                    $di = date('d/m/Y', strtotime($fun['data_admissao_funcionario']));
                    echo $di ;
                    ?>
                </td>
                <td>
                    <?php
                     echo "<span class='small'>" . substr($fun['horario_inicio'],0,5);
                    ?>
                </td>
                <td>
                    <?php
                    if ($fun['horario_termino']) {
                        echo "<span class='small'>" . substr($fun['horario_termino'],0,5);
                    }
                    echo "</span>";
                   ?>
                </td>  

              <td class="actions">
                <a class="btn" href="funcionario_edit.php?id=<?php echo $fun['id']; ?>">Editar</a>
                <a class="btn-danger" href="funcionario_delete.php?id=<?php echo $fun['id']; ?>"
                   onclick="return confirm('Deseja realmente excluir este funcionario?');">Excluir</a>
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

