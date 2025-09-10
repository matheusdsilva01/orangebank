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
    $valorAtual = round($valorAtual * (1 + $taxaDiaria), 4);
}

while ($diasSimulados < 365) {
    avancarDia();
}

$rentabilidadeTotal = $valorAtual - $valorInicial;
echo 'Valor atual do CDB Banco A: R$'.round($valorAtual.PHP_EOL, 2);
echo 'Rentabilidade acumulada: R$'.round($rentabilidadeTotal.PHP_EOL, 2);
