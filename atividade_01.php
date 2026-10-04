<?php


function contarLetrasMaiusculas(string $senha): int {
    return preg_match_all('/[A-Z]/', $senha);
}


function contarLetrasMinusculas(string $senha): int {
    return preg_match_all('/[a-z]/', $senha);
}


function contarNumeros(string $senha): int {
    return preg_match_all('/[0-9]/', $senha);
}


function contarCaracteresEspeciais(string $senha): int {
    return preg_match_all('/[^a-zA-Z0-9]/', $senha);
}


function classificarSenha(string $senha): string {
    $temMaiuscula  = contarLetrasMaiusculas($senha) > 0;
    $temMinuscula  = contarLetrasMinusculas($senha) > 0;
    $temNumero     = contarNumeros($senha) > 0;
    $temEspecial   = contarCaracteresEspeciais($senha) > 0;
    $tamanhoMinimo = strlen($senha) >= 8;

    $pontuacao = $temMaiuscula + $temMinuscula + $temNumero + $temEspecial + $tamanhoMinimo;

    if ($pontuacao === 5) {
        return "Muito Forte";
    } elseif ($pontuacao === 4) {
        return "Forte";
    } elseif ($pontuacao >= 2) {
        return "Média";
    } else {
        return "Fraca";
    }
}


function analisarSenha(string $senha): array {
    return [
        'quantidade_maiusculas' => contarLetrasMaiusculas($senha),
        'quantidade_minusculas' => contarLetrasMinusculas($senha),
        'quantidade_numeros'    => contarNumeros($senha),
        'quantidade_especiais'  => contarCaracteresEspeciais($senha),
        'tamanho_senha'         => strlen($senha),
        'nivel_seguranca'       => classificarSenha($senha)
    ];
}



$minhaSenha = "MAriaClaRADEBrito1!!!";
$relatorio = analisarSenha($minhaSenha);

echo "<h3>Análise da Senha: " . ($minhaSenha) . "</h3>";
echo "Quantidade de maiúsculas: " . $relatorio['quantidade_maiusculas'] . "<br>";
echo "Quantidade de minúsculas: " . $relatorio['quantidade_minusculas'] . "<br>";
echo "Quantidade de números: " . $relatorio['quantidade_numeros'] . "<br>";
echo "Quantidade de caracteres especiais: " . $relatorio['quantidade_especiais'] . "<br>";
echo "Tamanho da senha: " . $relatorio['tamanho_senha'] . "<br>";
echo "Nível de segurança: " . $relatorio['nivel_seguranca'] . "<br>";
?>
