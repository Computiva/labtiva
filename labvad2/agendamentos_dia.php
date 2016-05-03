<?php
$acaoLog = 'Visualizando o agedamento do dia ';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.classe/TData.class.php';
require_once 'app.include/header.inc.php';

// recebe laboratorio da querystring
// se veio vazio tem que dar mensagem de erro !!!!
if (isset($_GET['lab'])) {
        $id_laboratorio = $_GET['lab'];
} 
else {
        echo "Laboratório não especificado.";
        require_once 'app.include/footer.inc.php';
        exit;
}


$nome_laboratorio = "";

//Obtendo o nome do laboratório
$sql = $conn->prepare("SELECT nome, nome_tipo FROM laboratorios
                       JOIN laboratorios_tipo
                       ON laboratorios.fk_lab_tipo = laboratorios_tipo.id
                       WHERE laboratorios.estado='A'
                       AND (laboratorios.id = :id_laboratorio)");
$sql->bindParam(':id_laboratorio', $id_laboratorio);
$sql->execute();
$resultado = $sql->fetchObject();
$nome_tipo = $resultado->nome_tipo;
$nome_laboratorio = $resultado->nome;


if (isset($_GET['data'])) {
    $dtSelecionadaLog = filter_input(INPUT_GET, 'data', FILTER_SANITIZE_STRING);
    $dtSelecionadaLog = new DateTime($dtSelecionadaLog, new DateTimeZone('America/Sao_Paulo'));    
    $acaoLog .= $dtSelecionadaLog->format('d/m/Y');
    
    //Caso tenha ID é exclusão
    $iId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($iId > 0) {
        try {
            $conn      = TConnection::open();
            $sql = $conn->prepare("SELECT hora_inicio FROM agendamentos WHERE id = :id AND fk_pessoa = :fk_pessoa LIMIT 1 ");
            $sql->bindParam(":id", $iId);
            $sql->bindParam(":fk_pessoa", $_SESSION['id_usuario']);
            $sql->execute();
            $resultado = $sql->fetchObject();
            if ($sql->rowCount() > 0) {
                $acaoLog = 'Excluíndo o agendamento de ' . $resultado->hora_inicio . ' na data ' . $dtSelecionadaLog->format('d/m/Y');
            }
        }
        catch (Exception $e) {
            
        }
    }
}
if (isset($_GET['data_hora'])) {
    $dtSelecionadaLog = filter_input(INPUT_GET, 'data_hora', FILTER_SANITIZE_STRING);
    $dtSelecionadaLog = new DateTime($dtSelecionadaLog, new DateTimeZone('America/Sao_Paulo'));
    $acaoLog = 'Agendado em ' . $dtSelecionadaLog->format('d/m/Y') . ' às ' .  $dtSelecionadaLog->format('H:i:s');
}

$dataAgendamento = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');
$iId             = isset($_GET['id']) ? $_GET['id'] : FALSE;

$telaMensagem = "";
if ((isset($_SESSION['msg'])) && (!empty($_SESSION['msg']))) {
    $telaMensagem    = $_SESSION['msg'];
    $_SESSION['msg'] = FALSE;
}

if (isset($_GET['data_hora'])) {
    $dataHora = new DateTime($_GET['data_hora'], new DateTimeZone('America/Sao_Paulo'));

    // Guarda dia 'Y-m-d' do agendamento
    $dataAgendamento = $dataHora->format('Y-m-d');

    // Guarda hora de início
    $horaSelecionada = $dataHora->format('H:i:s');

    // Cálculo da hora final 0:59:59 após a hora selecionada
    $dataHora->add(new DateInterval('PT59M59S'));
    $horaFim = $dataHora->format('H:i:s');

    try {
        //Verificando se o horário esta disponivel
        $sql = $conn->prepare("SELECT COUNT(id) AS total FROM agendamentos 
                               WHERE (fk_laboratorio = :fk_laboratorio)
                               AND (dt_agendamento = :dt_agendamento) 
                               AND (hora_inicio = :hora_inicio) ");
        $sql->bindParam(':fk_laboratorio', $id_laboratorio);
        $sql->bindParam(':dt_agendamento', $dataAgendamento);
        $sql->bindParam(':hora_inicio', $horaSelecionada);
        $sql->execute();
        $resultado = $sql->fetchObject();
        if ($resultado->total > 0) {
            $telaMensagem = "Esse horário já foi agendado por outro usuário!";
        } else {

            // Verificando se este usuário já agendou o mesmo horário em outro labolatório do mesmo tipo
            $sql = $conn->prepare("SELECT 
                                    laboratorios.nome
                                   FROM 
                                    agendamentos
                                   JOIN laboratorios on agendamentos.fk_laboratorio = laboratorios.id
                                   WHERE 
                                    agendamentos.fk_pessoa = :usuario
                                    AND laboratorios.fk_lab_tipo = :tipo
                                    AND agendamentos.dt_agendamento = :dt_agendamento
                                    AND agendamentos.hora_inicio = :hora_inicio ");
            $sql->bindParam(':usuario',  $_SESSION['id_usuario']);
            $sql->bindParam(':tipo', $_SESSION['id_tipo']);
            $sql->bindParam(':dt_agendamento', $dataAgendamento);
            $sql->bindParam(':hora_inicio', $horaSelecionada);
            $sql->execute();
            $resultado = $sql->fetchObject();
            if ($resultado) {
                $telaMensagem = "Você já agendou esse mesmo horário no laboratório " . $resultado->nome . "!";
            } else {

                // Insere o agendamento no banco
                $sql = $conn->prepare("INSERT INTO agendamentos (
                            fk_laboratorio,
                            fk_pessoa,
                            dt_agendamento,
                            hora_inicio, 
                            hora_fim,
                            dt_cadastro
                        ) 
                        VALUES (
                            :fk_laboratorio,
                            :fk_pessoa,
                            :dt_agendamento,
                            :hora_inicio,
                            :hora_fim,
                            CURRENT_DATE()  
                        ) ");
                $sql->bindParam(":fk_laboratorio", $id_laboratorio);
                $sql->bindParam(":fk_pessoa", $_SESSION['id_usuario']);
                $sql->bindParam(":dt_agendamento", $dataAgendamento);
                $sql->bindParam(":hora_inicio", $horaSelecionada);
                $sql->bindParam(":hora_fim", $horaFim);
                $sql->execute();
                //$_SESSION['msg'] = 'Agendamento realizado com sucesso!';
                header("Location: agendamentos_dia.php?data={$dataAgendamento}&lab={$id_laboratorio}");
                exit;
            }
        }
        
    } 
    catch (Exception $e) {
        echo $e->getMessage();
    }
}

if ($dataAgendamento) {

    try {
        $_SESSION['dataSelecionadaAgenda'] = $dataAgendamento;

        //Exclusão de agendamento
        if ($iId > 0) {
            $sql = $conn->prepare("DELETE FROM agendamentos WHERE id = :id AND fk_pessoa = :fk_pessoa LIMIT 1 ");
            $sql->bindParam(":id", $iId);
            $sql->bindParam(":fk_pessoa", $_SESSION['id_usuario']);
            $sql->execute();
        }

        $sql = $conn->prepare("SELECT 
                                agendamentos.id,
                                agendamentos.hora_inicio, 
                                agendamentos.hora_fim,
                                pessoas.nome,
                                fk_pessoa
                               FROM 
                                agendamentos
                               LEFT OUTER JOIN pessoas ON (pessoas.id = agendamentos.fk_pessoa)
                               WHERE 
                                agendamentos.dt_agendamento = :dt_agendamento 
                               AND
                                fk_laboratorio = :fk_laboratorio
                               ORDER BY 
                                agendamentos.hora_inicio ");
        $sql->bindParam(":dt_agendamento", $dataAgendamento);
        $sql->bindParam(":fk_laboratorio", $id_laboratorio);
        $sql->execute();
        $vRetorno   = '';
        $sSeparador = '';
        //formato padrão start: '2014-08-16T16:00:00'
        while ($resultado  = $sql->fetchObject()) {
            $iId = 0;
            if ($resultado->fk_pessoa == $_SESSION['id_usuario']) {
                $iId = $resultado->id;
            }

            $vRetorno .= $sSeparador .
                    "{
                            title: '{$resultado->nome}',
                            id: {$iId},    
                            start: '{$dataAgendamento}T{$resultado->hora_inicio}',
                            end: '{$dataAgendamento}T{$resultado->hora_fim}'                            
                          }";
            $sSeparador = ',';
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>


<h1>Agendamento: <?= $nome_tipo ?> - <?= $nome_laboratorio ?></h1>

<div class="well">
<b>Atenção</b>: Esta agenda utiliza o horário de Brasília.
<br> 
<img src="img/brflag.gif" width="20"/> 
<?php
date_default_timezone_set('America/Sao_Paulo');
$utc_offset =  date('Z') / 3600;

$now = new DateTime();
echo "Hora no servidor LabVad central: " . $now->format('H:i:s'); 
echo " (GMT " . $utc_offset . ")";
?>
<br><br>
Clique num horário livre para reservá-lo ou num horário reservado por você se quiser liberar sua reserva.
</div>

<div class="row">
    <div id="calendar"></div>
</div>


<div class="modal fade boxAlert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"><?php echo $telaMensagem; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade boxDialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Deseja realmente excluir esse agendamento?</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btConfirmaExclusao" class="btn btn-primary">Sim</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        var currentLangCode = 'pt-br';
        var idexclusao = 0;

        function dataAtualFormatada() {
            var data = new Date();
            var dia = data.getDate();
            if (dia.toString().length == 1)
                dia = "0" + dia;
            var mes = data.getMonth() + 1;
            if (mes.toString().length == 1)
                mes = "0" + mes;
            var ano = data.getFullYear();

            var hora = data.getHours();
            if (hora.toString().length == 1)
                hora = "0" + hora;
            var minuto = data.getMinutes();
            if (minuto.toString().length == 1)
                minuto = "0" + minuto;
            var segundo = data.getSeconds();
            if (segundo.toString().length == 1)
                segundo = "0" + segundo;

            return ano + "-" + mes + "-" + dia + "T" + hora + ":00:00";
        }

        $('#calendar').fullCalendar({
            header: {
                left: '', //'today',
                center: 'title',
                right: '' //'agendaDay'
            },
            defaultView: 'agendaDay',
            defaultDate: '<?php echo $dataAgendamento; ?>',
            selectable: true,
            selectHelper: true,
            lang: 'pt-br',
            slotDuration: '00:60:00',
            dayClick: function (date, jsEvent, view) {
                selecionada = date.format();
                atual = dataAtualFormatada();
                if ((selecionada == atual) || (selecionada > atual))
                    document.location.href = "agendamentos_dia.php?data_hora=" + date.format() + "&lab=<?php echo $id_laboratorio; ?>";
            },
            eventClick: function (calEvent, jsEvent, view) {
                idexclusao = calEvent.id;
                if (idexclusao > 0) {
                    $(".boxDialog").modal('show');
                }
            },
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: [
                    <?php echo $vRetorno; ?>
            ]
        });

        $("#btConfirmaExclusao").on("click", function () {
            document.location.href = 'agendamentos_dia.php?data=<?php echo $dataAgendamento; ?>&id=' + idexclusao + "&lab=<?php echo $id_laboratorio; ?>";
            $(".boxDialog").modal('hide');
        });

        <?php
        if ((isset($telaMensagem)) && ($telaMensagem != '')) {
            echo "$('.boxAlert').modal('show');";
        }
        ?>
    });
</script>

<style>

    body {
        margin: 40px 10px;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    #calendar {
        max-width: 900px;
        margin: 0 auto;
    }


</style>

<?php require_once 'app.include/footer.inc.php'; ?>
