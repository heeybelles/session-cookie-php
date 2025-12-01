<?php
require 'config.php';
session_start();

$sql = "
SELECT 
    a.id,
    a.nome,
    a.tipo_animal,
    a.raca,
    a.descricao,
    a.status,
    a.data_chegada,
    a.vacinado,

    (SELECT p.padrinho_nome 
     FROM padrinho p 
     WHERE p.animal_id = a.id 
     ORDER BY p.id DESC 
     LIMIT 1) AS padrinho_nome,

    (SELECT p.padrinho_contato
     FROM padrinho p 
     WHERE p.animal_id = a.id 
     ORDER BY p.id DESC 
     LIMIT 1) AS padrinho_contato,

    (SELECT ado.adotante_nome
     FROM adocao ado 
     WHERE ado.animal_id = a.id 
     ORDER BY ado.id DESC 
     LIMIT 1) AS adotante_nome,

    (SELECT ado.adotante_contato
     FROM adocao ado 
     WHERE ado.animal_id = a.id 
     ORDER BY ado.id DESC
     LIMIT 1) AS adotante_contato,

    (SELECT ado.data_adocao
     FROM adocao ado 
     WHERE ado.animal_id = a.id 
     ORDER BY ado.id DESC
     LIMIT 1) AS data_adocao,

    (SELECT ado.processo_adaptacao
     FROM adocao ado 
     WHERE ado.animal_id = a.id 
     ORDER BY ado.id DESC
     LIMIT 1) AS processo_adaptacao

FROM animal a
ORDER BY a.nome ASC
";

$stmt = $pdo->query($sql);
$animais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatório de Animais</title>
<style>
body { font-family: Arial, sans-serif; margin: 25px; }
h1 { text-align: center; color: #233a63; }
.table { width: 100%; border-collapse: collapse; margin-top: 25px; }
.table th { background: #233a63; color: #fff; padding: 10px; }
.table td { padding: 10px; border: 1px solid #ccc; vertical-align: middle; }
.foto { width: 70px; height: 70px; border-radius: 8px; object-fit: cover; }
</style>
</head>
<body>

<h1>Relatório Geral de Animais</h1>

<table class="table">
<thead>
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Tipo</th>
    <th>Raça</th>
    <th>Status</th>
    <th>Vacinado</th>
    <th>Chegada</th>
    <th>Padrinho</th>
    <th>Adotante</th>
</tr>
</thead>
<tbody>
<?php foreach ($animais as $a): ?>
<tr>

    <td><?php echo htmlspecialchars($a['id']); ?></td>
    <td><?php echo htmlspecialchars($a['nome']); ?></td>
    <td><?php echo htmlspecialchars($a['tipo_animal']); ?></td>
    <td><?php echo htmlspecialchars($a['raca']); ?></td>
    <td><?php echo htmlspecialchars($a['status']); ?></td>
    <td><?php echo $a['vacinado'] ? "✔ Sim" : "✘ Não"; ?></td>
    <td><?php echo !empty($a['data_chegada']) ? date("d/m/Y", strtotime($a['data_chegada'])) : "—"; ?></td>
    <td>
        <?php 
            echo !empty($a['padrinho_nome'])
                ? "<strong>" . htmlspecialchars($a['padrinho_nome']) . "</strong><br>📞 " . htmlspecialchars($a['padrinho_contato'])
                : "Sem padrinho";
        ?>
    </td>
    <td>
        <?php 
            echo !empty($a['adotante_nome'])
                ? "<strong>" . htmlspecialchars($a['adotante_nome']) . "</strong><br>📞 " . htmlspecialchars($a['adotante_contato']) 
                  . "<br>📅 " . (!empty($a['data_adocao']) ? date("d/m/Y", strtotime($a['data_adocao'])) : "—") 
                  . "<br>📝 " . htmlspecialchars($a['processo_adaptacao'])
                : "Não adotado";
        ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<script>
window.onload = function() {
    window.print(); 
};
</script>

</body>
</html>
