<?php

$acaoLog = '';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.classe/TData.class.php';

// verifica se o usuário tem laboratório agendado nesse instante e pega as urls do lab e do vídeo
$permissaoExecutar = horario_agendado($_SESSION['id_usuario'], $_SESSION['id_tipo'], $url_lab, $url_video, $lab_id, $lab_nome, $incluir_multiplexacao, $hora_inicio, $hora_fim);
$ip_lab = "";
if ($permissaoExecutar) {
    $parts =  explode("/", $url_lab);
    $ip_lab =  $parts[0]."/".$parts[1]."/".$parts[2]; // http://xxx.xxx.xx.xx
}

// ========== Insere o descritor de um vídeo que será salvo no servidor remoto (chamado por ajax) ==========

if ((isset($_GET['acao'])) && ($_GET['acao'] == 'save')) {
    try {
        $dt_execucao = date('Y-m-d H:i:s');

        // está faltando salvar o código arduino que foi executado, se lab_tipo for igual a 1.
        $sql = $conn->prepare("INSERT INTO videos (
                                    fk_pessoa,
                                    fk_lab_tipo,
                                    fk_laboratorio,
                                    dt_execucao
                                )
                                VALUES (
                                    :fk_pessoa,
                                    :fk_lab_tipo,
                                    :fk_laboratorio,
                                    :dt_execucao
                                ) ");
        $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
        $sql->bindParam(':fk_lab_tipo', $_SESSION['id_tipo']);
        $sql->bindParam(':fk_laboratorio', $lab_id);
        $sql->bindParam(':dt_execucao', $dt_execucao);
        $sql->execute();

        $iIdVideo = $conn->lastInsertId();

        $rJson = array( "id" => $iIdVideo,
                        "msg" => 'Descritor do vídeo foi salvo com sucesso',
                        "erro" => 0 );

    } 
    catch (Exception $e) {
        $rJson = array( "id" => -1,
                        "msg" => $e->getMessage(),
                        "erro" => 1 );
    }


    $meuJson = json_encode($rJson);
    echo $meuJson;
    exit;
}

// ========== Retorna a listagem dos videos (chamado por ajax) ==========

else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'lista')) {
    try {

        $VGrid = '';
        $sql = $conn->prepare("SELECT V.*, L.nome
                                FROM videos AS V
                                JOIN laboratorios AS L 
                                ON V.fk_laboratorio = L.id
                                WHERE
                                    (V.fk_pessoa = :fk_pessoa) AND 
                                    (V.fk_lab_tipo = :fk_lab_tipo)
                                ORDER BY
                                    V.dt_execucao DESC");

        $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
        $sql->bindParam(':fk_lab_tipo', $_SESSION['id_tipo']);
        $sql->execute();
        while ($resultado = $sql->fetchObject()) {
            $nLab = substr($resultado->nome, -6);
            $dDate = DateTime::createFromFormat('Y-m-d H:i:s', $resultado->dt_execucao);
            $sDate = $dDate->format('d/m/Y H:i:s');

            $VGrid .= "<li class=\"list-group-item\">"
                . "<a href=\"#\" class=\"lkDiretoCodigo\" onclick=\"return playVideo('{$resultado->id}','{$sDate}','{$resultado->nome}');\">[{$nLab}] {$sDate}</a>"
                . "<span class=\"lkExclusaoVideo\"><a href=\"videos.php?acao=exclusao&id={$resultado->id}\" title=\"Excluír o vídeo\">&nbsp;</a></span>"
                . "</li>"; 
        }

    } 
    catch (Exception $e) {
        echo $e->getMessage();
    }

    echo $VGrid;
    exit;

}


// ========== Remove um video ==========

else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'exclusao')) {

    $iId = isset($_GET['id']) ? $_GET['id'] : 0;
    $telaRetorno = "";

    if ($iId == 0) {
        $telaRetorno = 'Erro ao receber o código do vídeo a ser excluído.';
    }
    else {
        try {
            $sql = $conn->prepare("SELECT dt_execucao FROM videos WHERE (id = :id) AND (fk_pessoa = :fk_pessoa) LIMIT 1 ");
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->bindParam(':id', $iId);
            $sql->execute();
            if ($sql->rowCount() > 0) {
                $resultado = $sql->fetchObject();
                gravaLog('Exclusão do vídeo ' . $resultado->dt_execucao);
            }        
            
            $sql = $conn->prepare("DELETE FROM videos WHERE (id = :id) AND (fk_pessoa = :fk_pessoa) LIMIT 1 ");
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->bindParam(':id', $iId);
            $sql->execute();
            $telaRetorno = '';  // 'Vídeo excluído com sucesso!';
        } 
        catch (Exception $e) {
            $telaRetorno = 'Erro ao excluír o vídeo: ' . $e->getMessage();
        }
    }

    echo $telaRetorno;
    exit;
} 

?>

