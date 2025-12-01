
<?php
$mes = $_GET['mes'] ?? date('m');
$ano = $_GET['ano'] ?? date('Y');
$hoje = date('Y-m-d');
$diaSelecionado = $_GET['data'] ?? null;

$mesNomes = [
    1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril",
    5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto",
    9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro"
];

$tituloMes = $mesNomes[(int)$mes] . " " . $ano;

$primeiroDiaSemana = date('w', strtotime("$ano-$mes-01"));
$diasNoMes = date('t', strtotime("$ano-$mes-01"));

$mesAnterior = $mes - 1;
$anoAnterior = $ano;
    if ($mesAnterior == 0) {
        $mesAnterior = 12;
        $anoAnterior--;
    }

$mesProximo = $mes + 1;
$anoProximo = $ano;
    if ($mesProximo == 13) {
        $mesProximo = 1;
        $anoProximo++;
    }
?>

<div class="calendario-box">
    
    <div class="cal-nav">
        <a href="?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>&data=<?= $_GET['data'] ?? '' ?>">◀</a>
        <h3><?= $tituloMes ?></h3>
        <a href="?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>&data=<?= $_GET['data'] ?? '' ?>">▶</a>
    </div>

    <div class="semana">
        <span>D</span><span>S</span><span>T</span><span>Q</span><span>Q</span><span>S</span><span>S</span>
    </div>

    <div class="dias">
        <?php

        for ($i = 0; $i < $primeiroDiaSemana; $i++) {
            echo "<span></span>";
        }

        for ($dia = 1; $dia <= $diasNoMes; $dia++) {
            $data = "$ano-$mes-".str_pad($dia, 2, "0", STR_PAD_LEFT);
            $classe = "";

                if ($data == $hoje) {
                    $classe = "hoje";
                }
                if ($diaSelecionado === $data) {
                    $classe .= " selecionado";
                }

            echo "<a class='dia $classe' href='?mes=$mes&ano=$ano&data=$data'> $dia</a>";
        }
        ?>
    </div>
</div>
