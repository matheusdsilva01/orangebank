<?php

$valorInicial = 1000.00;
$taxaAnual = 0.12;
$diasSimulados = 0;
$valorAtual = $valorInicial;

function avancarDia(): void
{
    global $diasSimulados, $taxaAnual, $valorAtual;
    $diasSimulados += 1;
    $taxaDiaria = pow(1.0 + $taxaAnual, (1.0 / 365.0)) - 1.0;
    $valorAtual = $valorAtual * (1 + $taxaDiaria);
}

while ($diasSimulados < 365) {
    avancarDia();
}

$rentabilidadeTotal = $valorAtual - $valorInicial;
echo 'Valor atual do CDB Banco A: R$'.$valorAtual.PHP_EOL;
echo 'Rentabilidade acumulada: R$'.$rentabilidadeTotal.PHP_EOL;
