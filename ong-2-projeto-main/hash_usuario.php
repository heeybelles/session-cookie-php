<?php
//Testando ainda xampp
require 'config.php'; 


function criarUsuario($pdo, $nome, $email, $senha, $nivel, $foto = null, $cargo_funcionario = null, $data_admissao_funcionario = null, $idade = null, $horario_inicio = null, $horario_termino = null) {
  
    $niveis_validos = ['Administrador', 'Funcionário'];
    if (!in_array($nivel, $niveis_validos)) {
        die("Erro: Nível inválido. Use 'Administrador' ou 'Funcionário'.");
    }


    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

  
    if ($nivel === 'Administrador') {
        $query = $pdo->prepare("
            INSERT INTO usuario (nome, email, senha, foto, nivel) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $query->execute([$nome, $email, $senhaHash, $foto, $nivel]);
    } else { 
        if (!$cargo_funcionario) $cargo_funcionario = 'Funcionário'; // padrão
        if (!$data_admissao_funcionario) $data_admissao_funcionario = date('Y-m-d'); // padrão hoje

        $query = $pdo->prepare("
            INSERT INTO usuario (nome, email, senha, foto, nivel, cargo_funcionario, data_admissao_funcionario, idade, horario_inicio, horario_termino)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $query->execute([$nome, $email, $senhaHash, $foto, $nivel, $cargo_funcionario, $data_admissao_funcionario, $idade, $horario_inicio, $horario_termino]);
    }

    echo "$nivel criado(a) com sucesso!";
}


criarUsuario(
    $pdo,
    'Carol',
    'carol@gmail.com',
    '1234@',
    'Administrador',
    'https://cdn.pixabay.com/photo/2020/07/01/12/58/icon-5359554_1280.png'
);

criarUsuario(
    $pdo,
    'João',
    'joao@gmail.com',
    'abcd@123',
    'Funcionário',
    'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png',
    'Atendente',
    date('Y-m-d'),
    28,
    '08:00:00',
    '17:00:00'
);
?>
