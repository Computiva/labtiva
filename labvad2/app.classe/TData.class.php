<?php
function parseDateTime($string, $timezone=null) {
    $date = new DateTime(
        $string,
        $timezone ? $timezone : new DateTimeZone('UTC')
    );
    
    if ($timezone) {
        // If our timezone was ignored above, force it.
        $date->setTimezone($timezone);
    }
    
    return $date;
}


function log_error ($num, $msg) {
    // Mecanismo de depuração em arquivo de log
    // Chamada: log_error ('1', 'entrei');

    $arquivo = fopen('error.log', 'a');
    fwrite($arquivo, "[".date('Y-m-d H:i:s')."] Error $num: $msg\r\n");
    fclose($arquivo);
}


function horario_agendado($usuario, $lab_tipo, &$url_lab, &$url_video, &$lab_id, &$lab_nome, &$incluir_multiplexacao, &$hora_inicio, &$hora_fim) {
    // verifica se o usuário possui um laboratorio agendado do tipo especificado nesse instante
    // retorna true se possuir ou false em caso contrário
    // retorna também as urls do laboratório agendado (caso exista).

    // chamada: if (horario_agendado($_SESSION['id_usuario'], $_SESSION['id_tipo'], $url_lab, $url_video, $lab_nome))

    global $conn;

    $sql = $conn->prepare("SELECT 
                            laboratorios.id, laboratorios.url_lab, laboratorios.url_video , laboratorios.nome, laboratorios.incluir_multiplexacao,
                            agendamentos.hora_inicio, agendamentos.hora_fim
                           FROM 
                            agendamentos
                           JOIN laboratorios on agendamentos.fk_laboratorio = laboratorios.id
                           WHERE 
                            agendamentos.fk_pessoa = :usuario
                            AND laboratorios.fk_lab_tipo = :tipo
                            AND agendamentos.dt_agendamento = CURRENT_DATE()
                            AND (CURRENT_TIME() BETWEEN agendamentos.hora_inicio AND agendamentos.hora_fim) ");
    $sql->bindParam(':usuario', $usuario);
    $sql->bindParam(':tipo', $lab_tipo);
    $sql->execute();
    $resultado = $sql->fetchObject();
    if ($resultado) {
        $lab_id = $resultado->id;
        $lab_nome = $resultado->nome;
        $url_lab = $resultado->url_lab;
        $url_video = $resultado->url_video;
        $incluir_multiplexacao = $resultado->incluir_multiplexacao;
        $hora_inicio = $resultado->hora_inicio;
        $hora_fim = $resultado->hora_fim;
        return TRUE;
    }
    else {
        $lab_id = "";
        $lab_nome = "";
        $url_lab = "";
        $url_video = "";
        $incluir_multiplexacao = "";
        $hora_inicio = "";
        $hora_fim = "";
        return FALSE;
    }

}
?>
