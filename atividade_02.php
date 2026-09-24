<?php

function removerEspacosDuplicados(string $texto): string {
    return trim(preg_replace('/\s+/', ' ', $texto));
}

function obterPalavrasLimpas(string $texto): array {
    $textoLimpo = preg_replace('/[^\w\sà-úÀ-Ú]/u', '', mb_strtolower($texto, 'UTF-8'));
    $palavras = explode(' ', $textoLimpo);
    return array_values(array_filter($palavras, function ($p) {
        return trim($p) !== '';
    }));
}

function contarFrases(string $texto): int {
    $frases = preg_split('/[.!?]+/', $texto, -1, PREG_SPLIT_NO_EMPTY);
    return count($frases);
}

function encontrarTamanhoPalavras(array $palavras): array {
    if (empty($palavras)) {
        return ['mais_longa' => '', 'mais_curta' => ''];
    }

    $longa = $palavras[0];
    $curta = $palavras[0];

    foreach ($palavras as $palavra) {
        if (mb_strlen($palavra, 'UTF-8') > mb_strlen($longa, 'UTF-8')) {
            $longa = $palavra;
        }
        if (mb_strlen($palavra, 'UTF-8') < mb_strlen($curta, 'UTF-8')) {
            $curta = $palavra;
        }
    }

    return [
        'mais_longa' => $longa,
        'mais_curta' => $curta
    ];
}

function analisarFrequenciaPalavras(array $palavras): array {
    $frequencias = array_count_values($palavras);
    
   
    $repetidas = array_filter($frequencias, function ($qtd) {
        return $qtd > 1;
    });

    arsort($frequencias);

    $top5 = array_slice($frequencias, 0, 5, true);

    return [
        'qtd_repetidas' => count($repetidas),
        'top_5'         => $top5
    ];
}


function formatarTextoCapitalizado(string $texto): string {
    $textoLimpo = removerEspacosDuplicados($texto);
    return mb_convert_case($textoLimpo, MB_CASE_TITLE, "UTF-8");
}

function processarTexto(string $texto): array {
    $textoSemEspacosExtras = removerEspacosDuplicados($texto);
    $palavras = obterPalavrasLimpas($textoSemEspacosExtras);
    $tamanhos = encontrarTamanhoPalavras($palavras);
    $frequencia = analisarFrequenciaPalavras($palavras);

    return [
        'quantidade_caracteres' => mb_strlen($textoSemEspacosExtras, 'UTF-8'),
        'quantidade_palavras'   => count($palavras),
        'quantidade_frases'     => contarFrases($texto),
        'palavra_mais_longa'    => $tamanhos['mais_longa'],
        'palavra_mais_curta'    => $tamanhos['mais_curta'],
        'palavras_repetidas'    => $frequencia['qtd_repetidas'],
        'top_5_frequentes'      => $frequencia['top_5'],
        'texto_sem_espacos'     => $textoSemEspacosExtras,
        'texto_formatado'       => formatarTextoCapitalizado($texto)
    ];
}


$textoEntrada = "Com grandes poderes, vem grandes responsabilidades";

$relatorio = processarTexto($textoEntrada);

echo "<h2>Relatório do Processador de Texto</h2>";
echo "<p>Texto Original: "  . ($textoEntrada) . "</p>";

echo " Quantidade de caracteres: " . $relatorio['quantidade_caracteres'] . "<br><br>";
echo " Quantidade de palavras: " . $relatorio['quantidade_palavras'] . "<br><br>";
echo " Quantidade de frases: " . $relatorio['quantidade_frases'] . "<br><br>";
echo " Palavra mais longa: " . $relatorio['palavra_mais_longa'] . "<br><br>";
echo " Palavra mais curta: " . $relatorio['palavra_mais_curta'] . "<br><br>";
echo " Quantidade de palavras repetidas: " . $relatorio['palavras_repetidas'] . "<br><br>";

echo " Lista das cinco palavras mais frequentes:<br><br>";
foreach ($relatorio['top_5_frequentes'] as $palavra => $qtd) {
    echo "{$palavra}: {$qtd} vez(es)<br><br>";
}
echo "</ul>";

echo " Texto sem espaços duplicados: " . $relatorio['texto_sem_espacos'] . "<br><br>";
echo " Texto totalmente formatado: " . $relatorio['texto_formatado'] . "<br>";
?>