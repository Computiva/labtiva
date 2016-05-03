<?php
$acaoLog = '';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.classe/TData.class.php';
require_once 'app.include/header.inc.php';

// Verifica se o usuário tem laboratório agendado nesse instante e pega as urls do lab e do vídeo
$permissaoExecutar = horario_agendado($_SESSION['id_usuario'], $_SESSION['id_tipo'], $url_lab, $url_video, $lab_id, $lab_nome, $incluir_multiplexacao, $hora_inicio, $hora_fim);
$ip_lab = "";
if ($permissaoExecutar) {
    $parts =  explode("/", $url_lab);
    $ip_lab =  $parts[0]."/".$parts[1]."/".$parts[2]; // http://xxx.xxx.xx.xx
}
?>

<h1>Experimentos</h1>

Laboratório: 
<?
if ($permissaoExecutar) {
    echo "$lab_nome ({$_SESSION['nome_tipo']})";
    echo " - Seu horário agendado: ".substr($hora_inicio,0,5)." às ".substr($hora_fim,0,5)." horas";
}
else
    echo "não há laboratório reservado no momento";
?>

<div class="row">
    <div class="col-md-12">

        <!-- Área do streaming de vídeo -->
        <div id="video" class="col-md-8 .col-xs-8 panel">
            <?
            if ($permissaoExecutar) {
                // acrescenta parâmetro aleatório na url do stream para evitar cache do browser
                echo '<video width="95%" height="95%" controls="" autoplay=""> <source src="' . $url_video.'?'.md5(uniqid('')) . '" type="video/ogg"></video><br/>';
            ?>

                <div class="videocontroller">
                    Gravador de Vídeo: <span id="record_msg">Parado.</span>

                    <button type="button" class="btn btn-default" id="record_button">
                      <span class="glyphicon glyphicon-film"></span> Iniciar Gravação
                    </button>
                </div>
                <br/>

            <?
            }
            ?>
        </div>

        <!-- Área com a lista de vídeos salvos -->
        <div id="lista-codigo"  class="col-md-4 .col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading">Meus Vídeos</div>
                <div class="panel-body box-codigo">

                    <ul class="list-group listagem-video">
                    </ul>

                </div>
            </div>
        </div>

    </div>

    <!-- Área do experimento -->
    <div class="col-md-12 clearfix">
        <div class="panel panel-default box-visualiza-codigo clearfix">

            <?php
            if ($permissaoExecutar) {
                echo "<iframe src='{$url_lab}' width='100%' height='560'></iframe>";
            }
            else {
                echo "Agende um horário para executar este experimento.";
            }
            ?>

        </div>

    </div>
</div>
</div>


<!-- modal dialogs -->

<!-- boxAlert -->

<div class="modal boxAlert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- boxPergunta -->

<div class="modal boxPergunta">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btExclusaoConfirmar" class="btn btn-primary" data-dismiss="modal">Sim</button>
                <button type="button" id="btExclusaoFechar" class="btn btn-danger" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>

<!-- boxVideo -->

<div class="modal boxVideo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Meus Vídeos!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"></p>
                <span id="video-tag"></span>
                <br/>
            </div>  
            <div class="modal-footer">
                <button type="button" id="btRenomearFechar" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>	
        </div>
    </div>
</div>

<!-- msgCarregando -->              
<div id="msg-carregando">Carregando...</div>


<script type="text/javascript">

    function carregaVideos() {
        // Refresh na lista meus vídeos
        $("ul.listagem-video").append('Carregando...').load('videos.php?acao=lista');
    }

    function playVideo (idPlay, dtPlay, nomeLab) {
        $(".boxVideo #mensagem-local").html('[<b>'+nomeLab+'</b>] '+dtPlay);

        var video_path = '/videos/'+nomeLab+'/'+nomeLab+'_cam1_'+idPlay+'.ogg';
        $(".boxVideo #video-tag").html('<video width="95%" height="95%" controls="" autoplay=""><source src="'+video_path+'" type="video/ogg"></video>');
        $(".boxVideo").modal('show');
        return false;
    }

    /* relógio do cliente
    function getMinutesUntilNextHour()   { return 60 - new Date().getMinutes(); } 
    function getSecondsUntilNextMinute() { return 60 - new Date().getSeconds(); } 
    */
    /* relógio do servidor */
    function getMinutesUntilNextHour()   { return 60 - <?= date('i') ?> }
    function getSecondsUntilNextMinute() { return 60 - <?= date('s') ?> }

    <?php if ($permissaoExecutar) { ?>
        var tos = (getMinutesUntilNextHour() * 60) + getSecondsUntilNextMinute(); // seconds to timeout
        var idInt=''; 
    <?php } ?>

    // teste para verificar se a reserva do laboratório ainda está ativa
    function ctrlTimeout() { 
        if(tos==0) { 
            window.clearInterval(idInt); 
            location.href = "labvad.php"; 
        } 
        tos--; 
    } 

    function aguarde(exibir) {
        if (exibir)
            $("#msg-carregando").show();
        else
            $("#msg-carregando").hide(5000);
    }

    $(document).ready(function () {

        var idCodigoExclusao = 0, caminhoExclusao = "";        
        var idVideoInclusao = 0;

        // testa a cada segundo se atingiu a hora limite da sessão reservada no laboratório remoto,
        // redirecionando a página caso isso aconteça.
        <?php if ($permissaoExecutar) { ?>
        idInt = window.setInterval(ctrlTimeout, 1000); 
        <?php } ?>

        // Inicializa a lista de vídeos salvos
        carregaVideos();


        // Callback: Iniciar e terminar de gravar vídeo

        var paused = true;   
        var gPid;
        var gRNameOf;
        var gExtOf;
         
        $("#record_button").click(function() { 
            if (paused) {

                // Primeiro clique: inicia a gravação...
                paused = false;
                $("#record_button").html('<span class="glyphicon glyphicon-pause"></span> Parar Gravação');
                $("#record_msg").text("Gravando.");
                $("#record_msg").css('color', '#F00');

                // chama o servidor central para salvar o descritor do vídeo no banco de dados
			    // alert ("videos.php?acao=save");
                $.post("videos.php?acao=save",
                    {},
                    function (retorno1) {

                        // alert ("retorno1="+retorno1.erro);
                        if (retorno1.erro === 0) {
                            idVideoInclusao = retorno1.id; // salva id do video recem incluído

                            //emite mensagem dizendo que o descritor do vídeo foi salvo com sucesso.
                            //$("#mensagem-local").html(retorno1.msg);
                            //$('.boxAlert').modal('show');

                            // chama o servidor remoto para iniciar gravação do arquivo de vídeo
                            // alert ("chamando: <?=$ip_lab?>/labvad-ciencias/streaming/php/recordStream.php");
                            $.post("<?=$ip_lab?>/labvad-ciencias/streaming/php/recordStream.php",
                                { id: retorno1.id },
                                function (retorno2) {
                                    //alert ("retorno2="+retorno2.erro);
                                    if (retorno2.erro === 0) {
                                        // recebe info do processo que está salvando o vídeo
                                    	gPid = retorno2.pid;
                                    	gRNameOf = retorno2.rNameOf;
                                    	gExtOf = retorno2.extOf;

                                        $(".boxAlert #mensagem-local").html(retorno2.msg);
                                        $('.boxAlert').modal('show');
                                    }
                                    else if (retorno2.erro > 0) {
                                        $(".boxAlert #mensagem-local").html(retorno2.msg);
                                        $('.boxAlert').modal('show');
                                    }
                                },
                                'json'
                            );
                        }
                        else if (retorno.erro > 0) {
                            $(".boxAlert #mensagem-local").html(retorno.msg);
                            $('.boxAlert').modal('show');
                        }

                    },
                    'json'
                );
            }
            else {

                // Segundo clique: finaliza a gravação...
                paused = true;
                $("#record_button").html('<span class="glyphicon glyphicon-film"></span> Iniciar Gravação');
                $("#record_msg").text("Parado.");
                $("#record_msg").css('color', '#000');

                // chama o servidor remoto para finalizar gravação do arquivo de vídeo
                // devolve info do processo que está salvando o vídeo.

                // alert ("chamando: <?=$ip_lab?>/labvad-ciencias/streaming/php/stopStreamRecording.php");
                $.post("<?=$ip_lab?>/labvad-ciencias/streaming/php/stopStreamRecording.php",
                    { pid: gPid, rnameof: gRNameOf, extof: gExtOf },
                    function (retorno) {
                        // alert ("retorno="+retorno.erro);
                        if (retorno.erro === 0) {
                            $(".boxAlert #mensagem-local").html(retorno.msg);
                            $('.boxAlert').modal('show');
                            carregaVideos();
                        }
                        else if (retorno.erro > 0) {
                            $(".boxAlert #mensagem-local").html(retorno.msg);
                            $('.boxAlert').modal('show');

                            // se deu erro ao salvar o arquivo com o video, remove o descritor no banco de dados
                            // alert ("videos.php?acao=exclusao&id="+idVideoInclusao);
                            $.post("videos.php?acao=exclusao&id="+idVideoInclusao,
                                {},
                                function (retorno3) {
                                    // debug indicando se o descritor foi removido
                                    alert(retorno3+" "+idVideoInclusao);
                                    idVideoInclusao = 0;
                                }
                            );

                        }
                    },
                    'json'
                );
            }

        });
         

        // Callback: Excluir vídeo

        $('ul.listagem-video').on('click', '.lkExclusaoVideo a', function (){
            myUrl            = $(this).attr('href');
            parametros       = myUrl.split("?")[1];
            caminhoExclusao  = myUrl;
            idCodigoExclusao = parametros.split("=")[2];

            if ((idCodigoExclusao === 0) || (idCodigoExclusao === "")) {
                $(".boxAlert #mensagem-local").html('Selecione o vídeo para realizar a exclusão!');
                $('.boxAlert').modal('show');
                return false;
            }
            else {
                $(".boxPergunta #mensagem-local").html('Deseja realmente excluír esse vídeo?');
                $(".boxPergunta").modal('show');
            }
            
            return false;
        });

        // Callback: Respostas do popup para confirmar exclusão do vídeo

        $(".boxPergunta #btExclusaoFechar").on('click', function () {
            $(".boxPergunta").modal('hide');
        });

        $(".boxPergunta #btExclusaoConfirmar").on('click', function () {
            caminho     = caminhoExclusao; //$("#excluir-codigo").attr('href');
            mIdCodigo   = idCodigoExclusao; //$('#idCodigo').val();
            //mNomeCodigo = $('#txtNomeCodigo').val();

            $.post(caminho,
                {
                    idCodigo: mIdCodigo//,txtNomeCodigo: mNomeCodigo
                },
                function (retorno) {
                    $(".boxPergunta").modal('hide');
                    if (retorno) {
                        $(".boxAlert #mensagem-local").html(retorno);
                        $('.boxAlert').modal('show');
                    }
                    carregaVideos();
                }
            );

            return false;
        });

    });
</script>

<?php require_once 'app.include/footer.inc.php'; ?>

