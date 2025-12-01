
  <?php
  require 'config.php';
  session_start();

  // Faz o menu receber o item ativo
  $pagina = basename($_SERVER['PHP_SELF'], ".php");
  
  if (!isset($_SESSION['usuario_id'])) {
      header("Location: index.php");
      exit;
  }

  $id = $_GET['id'] ?? null;
  if (!$id) {
      die("ID inválido.");
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

  $stmt = $pdo->prepare("SELECT * FROM voluntario WHERE id = ?");
  $stmt->execute([$id]);
  $voluntario = $stmt->fetch(PDO::FETCH_ASSOC);

  $diasMarcados = !empty($voluntario['disponibilidade'])
    ? explode(', ', $voluntario['disponibilidade'])
    : [];

  $erro = '';
  $sucesso = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nome = trim($_POST['nome'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $idade = trim($_POST['idade'] ?? '');
      $telefone = trim($_POST['telefone'] ?? '');
      $data_inicio = $_POST['data_inicio'] ?? '';
      $horario_inicio = $_POST['horario_inicio'] ?? '';
      $horario_termino = $_POST['horario_termino'] ?? '';
      $observacao = $_POST['observacao'] ?? '';
      
      if (isset($_POST['disponibilidade'])) {
          $disponibilidade = implode(', ', $_POST['disponibilidade']);
      } else {
          $disponibilidade = '';
      }

      if ($nome === '' || $telefone === '') {
          $erro = "Preencha ao menos nome e telefone.";
      } else {

          $sql = "UPDATE voluntario 
                  SET nome=?, email=?, idade=?, telefone=?, disponibilidade=?,
                      data_inicio=?, horario_inicio=?, horario_termino=?, observacao=? 
                  WHERE id=?";

          $stmt = $pdo->prepare($sql);
          $stmt->execute([
              $nome, $email, $idade, $telefone, $disponibilidade,
              $data_inicio, $horario_inicio, $horario_termino, $observacao, $id
          ]);

          $_SESSION['mensagem_sucesso'] = "Voluntário atualizado com sucesso.";
          header("Location: voluntario.php");
          exit;

          
      }
  }
  ?>

  <!DOCTYPE html>
  <html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="img/iconLogo.png">
    <title>Editar Voluntário</title>

    <link rel="stylesheet" href="css/menu.css" />
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
      <link rel="stylesheet" href="css/voluntario-edit.css" />
  </head>
  
  <body>
  <?php 
    include 'includes/menu.php';
    include 'includes/ajuste-config.php';
  ?>
  <main>
      <div class="voluntario-container-form">

        <?php if ($erro): ?>
            <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <h2>Atualizar Voluntário</h2>

          <div class="form-grid">

            <div>
              <label>Nome</label>
              <input type="text" name="nome" required value="<?= htmlspecialchars($voluntario['nome']) ?>">
            </div>

            <div>
              <label>Idade</label>
              <input type="text" name="idade" required value="<?= htmlspecialchars($voluntario['idade']) ?>">
            </div>

            <div>
              <label>Email</label>
              <input type="email" name="email" required value="<?= htmlspecialchars($voluntario['email']) ?>">
            </div>

            <div>
              <label>Telefone</label>
              <input type="text" name="telefone" required value="<?= htmlspecialchars($voluntario['telefone']) ?>">
            </div>

            <div>
              <label>Data início</label>
              <input type="date" name="data_inicio" required value="<?= htmlspecialchars($voluntario['data_inicio']) ?>">
            </div>

            <div class="checkbox-grid">
          <label class="label-full">Disponibilidade</label>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Segunda"
              <?= in_array("Segunda", $diasMarcados) ? "checked" : "" ?>>
            <span>Segunda-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Terça"
              <?= in_array("Terça", $diasMarcados) ? "checked" : "" ?>>
            <span>Terça-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Quarta"
              <?= in_array("Quarta", $diasMarcados) ? "checked" : "" ?>>
            <span>Quarta-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Quinta"
              <?= in_array("Quinta", $diasMarcados) ? "checked" : "" ?>>
            <span>Quinta-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Sexta"
              <?= in_array("Sexta", $diasMarcados) ? "checked" : "" ?>>
            <span>Sexta-Feira</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Sábado"
              <?= in_array("Sábado", $diasMarcados) ? "checked" : "" ?>>
            <span>Sábado</span>
          </div>

          <div class="checkbox-item">
            <input type="checkbox" name="disponibilidade[]" value="Domingo"
              <?= in_array("Domingo", $diasMarcados) ? "checked" : "" ?>>
            <span>Domingo</span>
          </div>
        </div>

        <div>
          <label>Horário início</label>
          <input type="time" name="horario_inicio" required 
            value="<?= htmlspecialchars($voluntario['horario_inicio']) ?>">
        </div>

        <div>
          <label>Horário término</label>
          <input type="time" name="horario_termino" 
            value="<?= htmlspecialchars($voluntario['horario_termino']) ?>">
        </div>
        </div>
      <div class="form-actions">
        <button class="btn" type="submit">Salvar</button>
        <a class="btn btn-cancelar" href="voluntario.php">Cancelar</a>
      </div>
    </form>

  </div>
</main>
</body>
</html>
