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

  //Crud Agenda
  $dataFiltro = $_GET['data'] ?? null;
    if ($dataFiltro) {
        $d = DateTime::createFromFormat('Y-m-d', $dataFiltro);
        if (!$d || $d->format('Y-m-d') !== $dataFiltro) {
            
            $dataFiltro = null;
        }
    }

  try {
      if ($dataFiltro) {
          $sql = "
              SELECT id, titulo, descricao, data_inicio, data_termino, horario_inicio, horario_termino, status, local
              FROM agenda_eventos
              WHERE (DATE(data_inicio) = :data)
                OR (data_termino IS NOT NULL AND :data BETWEEN DATE(data_inicio) AND DATE(data_termino))
              ORDER BY data_inicio, horario_inicio
          ";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([':data' => $dataFiltro]);
      } else {
    
          $stmt = $pdo->prepare("
              SELECT id, titulo, descricao, data_inicio, data_termino, horario_inicio, horario_termino, status, local
              FROM agenda_eventos
              ORDER BY data_inicio, horario_inicio
          ");
          $stmt->execute();
      }

      $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
      $eventos = [];
      $erro = "Erro ao carregar agenda: " . $e->getMessage();
  }

  ?>
  <!DOCTYPE html>
  <html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="img/iconLogo.png">
    <title>Painel Administrativo</title>

    <link rel="stylesheet" href="css/menu.css" />
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
      <link rel="stylesheet" href="css/agenda.css" />
  </head>
  <?php 
    include 'includes/menu.php';
  ?>

 <?php 
    include 'includes/ajuste-config.php';
  ?>
 <body class="tema-<?php echo $_SESSION['tema']; ?>">
 
  <main>
    <div class="titulo-btn-agenda">
        <p class="txtTitle">Agenda de Eventos</p>

      <?php if (!empty($erro)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
      <?php endif; ?>

      <a class="btn-add" href="agenda_create.php">+ Adicionar novo evento</a>
      <a class="btn-mostrar" href="dashboard.php">Mostrar todos</a>
    </div>

  <div class="agenda-container">
      <table>
        <thead>
          <tr>
            <th>Título</th>
            <th>Datas / Horários</th>
            <th>Local</th>
            <th>Status</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>

        <tbody>
          <?php if (empty($eventos)): ?>
            <tr><td colspan="6" class="small">Nenhum evento cadastrado.</td></tr>

          <?php else: ?>
            <?php foreach ($eventos as $ev): ?>
              <tr>
                <td><?php echo htmlspecialchars($ev['titulo']); ?></td>

                <td>
                  <?php
                    $di = date('d/m/Y', strtotime($ev['data_inicio']));
                    $dt = $ev['data_termino'] ? date('d/m/Y', strtotime($ev['data_termino'])) : null;

                    echo $di . ($dt ? " — {$dt}" : "");
                    echo "<br><span class='small'>" . substr($ev['horario_inicio'],0,5);

                    if ($ev['horario_termino']) {
                        echo " — " . substr($ev['horario_termino'],0,5);
                    }
                    echo "</span>";
                  ?>
                </td>

                <td><?php echo htmlspecialchars($ev['local']); ?></td>
                <td><?php echo htmlspecialchars($ev['status']); ?></td>
                <td class="descricao"><?php echo nl2br(htmlspecialchars($ev['descricao'])); ?></td>

                <td class="actions">
                  <a class="btn" href="agenda_edit.php?id=<?php echo $ev['id']; ?>">Editar</a>
                  <a class="btn-danger" href="agenda_delete.php?id=<?php echo $ev['id']; ?>"
                    onclick="return confirm('Deseja realmente excluir este evento?');">Excluir</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <div class="calendario">
          <?php include "includes/calendario.php"; ?>
      </div>
  </div>
    
  </main>
  </body>
  </html>
