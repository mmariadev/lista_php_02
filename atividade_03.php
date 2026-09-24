<?php
// Exercício 18 – Gerenciador de Agenda


function ordenarConsultasPorHorario(array $consultas): array {
    usort($consultas, function ($a, $b) {
        $dataHoraA = $a['data'] . ' ' . $a['horario'];
        $dataHoraB = $b['data'] . ' ' . $b['horario'];
        return strcmp($dataHoraA, $dataHoraB);
    });
    return $consultas;
}


function contarPacientesUnicos(array $consultas): int {
    $pacientes = array_column($consultas, 'paciente');
    return count(array_unique($pacientes));
}


function contarConsultasPorEspecialidade(array $consultas): array {
    $especialidades = array_column($consultas, 'especialidade');
    return array_count_values($especialidades);
}


function pesquisarPaciente(array $consultas, string $nomePaciente): array {
    $encontrados = [];
    foreach ($consultas as $consulta) {
        if (mb_stripos($consulta['paciente'], $nomePaciente, 0, 'UTF-8') !== false) {
            $encontrados[] = $consulta;
        }
    }
    return $encontrados;
}


function verificarHorariosDuplicados(array $consultas): bool {
    $datasEHorarios = [];
    foreach ($consultas as $c) {
        $chave = $c['data'] . ' ' . $c['horario'];
        if (in_array($chave, $datasEHorarios)) {
            return true; // Existe duplicado
        }
        $datasEHorarios[] = $chave;
    }
    return false;
}


function obterExtremosAtendimento(array $consultasOrdenadas): array {
    if (empty($consultasOrdenadas)) {
        return ['primeiro' => null, 'ultimo' => null];
    }
    return [
        'primeiro' => $consultasOrdenadas[0],
        'ultimo'   => $consultasOrdenadas[count($consultasOrdenadas) - 1]
    ];
}


function organizarAgenda(array $consultas, string $pacienteParaPesquisar = ''): array {
    $consultasOrdenadas = ordenarConsultasPorHorario($consultas);
    $extremos = obterExtremosAtendimento($consultasOrdenadas);

    return [
        'total_consultas'       => count($consultas),
        'total_pacientes'       => contarPacientesUnicos($consultas),
        'por_especialidade'     => contarConsultasPorEspecialidade($consultas),
        'primeiro_atendimento'  => $extremos['primeiro'],
        'ultimo_atendimento'    => $extremos['ultimo'],
        'agenda_ordenada'       => $consultasOrdenadas,
        'pesquisa_paciente'     => pesquisarPaciente($consultas, $pacienteParaPesquisar),
        'tem_horario_duplicado' => verificarHorariosDuplicados($consultas)
    ];
}


$agenda = [
    ['paciente' => 'Carlos Silva',  'especialidade' => 'Cardiologia',  'data' => '2026-03-30', 'horario' => '14:30'],
    ['paciente' => 'Ana Souza',     'especialidade' => 'Dermatologia', 'data' => '2026-03-30', 'horario' => '08:00'],
    ['paciente' => 'Beatriz Lima',  'especialidade' => 'Cardiologia',  'data' => '2026-03-30', 'horario' => '10:15'],
    ['paciente' => 'Carlos Silva',  'especialidade' => 'Ortopedia',    'data' => '2026-03-30', 'horario' => '16:00'],
    ['paciente' => 'João Pedro',    'especialidade' => 'Dermatologia', 'data' => '2026-03-30', 'horario' => '11:00']
];

$pacienteBuscado = "Carlos Silva";
$relatorioAgenda = organizarAgenda($agenda, $pacienteBuscado);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Agenda</title>

</head>
<body>

    <p>Relatório Geral da Agenda</p>

    <p>Quantidade total de consultas:<?php echo $relatorioAgenda['total_consultas']; ?></p>
    <p>Quantidade de pacientes diferentes:<?php echo $relatorioAgenda['total_pacientes']; ?></p>
    
    <p>Quantidade de consultas por especialidade:</p>
    
        <?php foreach ($relatorioAgenda['por_especialidade'] as $especialidade => $qtd): ?>
            <li><?php echo $especialidade; ?>:<?php echo $qtd; ?> consulta(s)</li>
        <?php endforeach; ?>
   

    <p>Primeiro atendimento do dia:
        <?php echo $relatorioAgenda['primeiro_atendimento']['paciente']; ?> às <?php echo $relatorioAgenda['primeiro_atendimento']['horario']; ?> (<?php echo $relatorioAgenda['primeiro_atendimento']['especialidade']; ?>)
    </p>

    <p>Último atendimento do dia:
        <?php echo $relatorioAgenda['ultimo_atendimento']['paciente']; ?> às <?php echo $relatorioAgenda['ultimo_atendimento']['horario']; ?> (<?php echo $relatorioAgenda['ultimo_atendimento']['especialidade']; ?>)
    </p>

    <p>Existem horários duplicados no mesmo dia?
        <?php echo $relatorioAgenda['tem_horario_duplicado'] ? '<span">Sim</span>' : '<span ">Não</span>'; ?>
    </p>
    <br>
    <hr>
    <br>
    <p>Lista Ordenada por Horário: </p>
    <table>
        <tr>
            <td>Horário</td>
            <td>Paciente</td>
            <td>Especialidade</td>
            <td>Data</td>
        </tr>
        <?php foreach ($relatorioAgenda['agenda_ordenada'] as $consulta): ?>
            <tr>
                <td><?php echo $consulta['horario']; ?></td>
                <td><?php echo $consulta['paciente']; ?></td>
                <td><?php echo $consulta['especialidade']; ?></td>
                <td><?php echo $consulta['data']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <hr>
    <br>

    <p>Resultado da Pesquisa: "<?php echo ($pacienteBuscado); ?>"</p>
    <?php if (!empty($relatorioAgenda['pesquisa_paciente'])): ?>
        
            <?php foreach ($relatorioAgenda['pesquisa_paciente'] as $c): ?>
                <li><?php echo $c['data']; ?> às <?php echo $c['horario']; ?> - <?php echo $c['especialidade']; ?></li>
            <?php endforeach; ?> 
       
    <?php else: ?>
        <p>Nenhuma consulta encontrada para este paciente.</p>
    <?php endif; ?>

</body>
</html>