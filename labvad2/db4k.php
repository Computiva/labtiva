<?php

$acaoLog = '';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.classe/TData.class.php';

//DB4K - Foi criada uma div antes da textarea para colocar o style como display none

function getFormEditar($telaId, $telaNomeCodigo, $telaCodigo, $acao) {
    $desabilita = ($acao == 'editar') ? ' disabled ' : '';
    return '<form id="formCodigo" name="formCodigo" method="post" action="laboratorio.php?acao=gravar&tipo=xml">
                <input type="hidden" name="idCodigo" id="idCodigo" value="' . $telaId . '">
                <input type="text" maxlength="70" id="txtNomeCodigo" ' . $desabilita . ' name="txtNomeCodigo" value="' . $telaNomeCodigo . '" 
                    placeholder="Informe o nome do seu programa">
		<div style="display: none">
                <textarea id="txtCodigo" name="txtCodigo" class="abc" placeholder="Codifique aqui...">' . $telaCodigo . '</textarea>
		 </div>
            </form>';
}


// Se tipo não é Programação arduino, manda para o laboratório de ciências
if ($_SESSION['id_tipo']!='1'){
    header("Location: labciencias.php");
    exit;
}

// verifica se o usuário tem laboratório agendado nesse instante e pega as urls do lab e do vídeo
$permissaoExecutar = horario_agendado($_SESSION['id_usuario'], $_SESSION['id_tipo'], $url_lab, $url_video, $lab_id, $lab_nome, $incluir_multiplexacao, $hora_inicio, $hora_fim);

$ip_lab = "";
if ($permissaoExecutar) {
    $parts =  explode("/", $url_lab);
    $ip_lab =  $parts[0]."/".$parts[1]."/".$parts[2]; // http://xxx.xxx.xx.xx
}


require_once 'app.include/header.inc.php';

$telaCodigo     = '';
$iId            = 0;
$telaId         = 0;
$telaNomeCodigo = '';
if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
    $iId = $_GET['id'];
}

$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
if ($acao != 'novo') {
    $sql       = $conn->prepare("SELECT
                                    experimentos.id,
                                    experimentos.nome,
                                    experimentos.codigo
                                FROM
                                    experimentos
                                WHERE
                                    (experimentos.fk_pessoa = :fk_pessoa) 
                                    AND (experimentos.id = :id) ");
    $sql->bindParam(':id', $iId);
    $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
    $sql->execute();
    if ($sql->rowCount() > 0) {
        $resultado = $sql->fetchObject();

        $telaCodigo     = trim($resultado->codigo);
        $telaId         = $resultado->id;
        $telaNomeCodigo = $resultado->nome;
    }
} 

?>

<!--***************************************************** Início HTML Edição Código ***************************************************-->
<!-- <h1><h1>
<!-- BEGIN DB4K**********************************************************************-->
<!----- Ícone DB4K ------>
<!--<div class="card-panel arduino_teal">
<span><img class="responsive-img" src="db4kfiles/ardublockly/img/db4kimages/logo_db4k_inicio_labvad.png" alt="DuinoBlocks for Kids" align="middle"></span> 
</div> -->
<!--Div para jogar o Ícone pra baixo do menu superior-->
<div style= "background-color : transparent; height: 40px">
</div>
<!----- Ícone DB4K ------>
<div class="card-panel" style="padding: 0">
<nav class="nav-fixed arduino_teal">
<div class="nav-wrapper container">
<a id="logo-container" class="brand-logo center">
<span class="app_title"><img src="db4kfiles/ardublockly/img/db4kimages/logo_db4k_inicio.png" alt="DuinoBlocks for Kids" align="middle"></span>
</a>
</div>
</nav>
</div>
<!-- END DB4K**********************************************************************-->
<div style= "background-color : transparent; height: 20px">
Laboratório
</div>
<?
if ($permissaoExecutar) {
    echo "$lab_nome ({$_SESSION['nome_tipo']})";
    echo " - Seu horário agendado: ".substr($hora_inicio,0,5)." às ".substr($hora_fim,0,5)." horas";
}
else
    echo "Não há laboratório reservado no momento";
?>

<div class="row">
    <div class="col-md-12">

        <!-- Área do streaming de vídeo -->
        <div id="video" class="col-md-8 .col-xs-8 panel" align="center">

            <?
            if ($permissaoExecutar) {
                // acrescenta parâmetro aleatório na url do stream para evitar cache do browser
        echo '<video width="86%" height="86%" controls="" autoplay=""> <source src="' . $url_video.'?'.md5(uniqid('')) . '" type="video/ogg"></video><br/>';
            ?>

<!-- Código para testes DB4K************************************************************ -->
<!-- BEGIN DB4K - teste - Código com link direto para a URL da UFRJ -->
<!-- 
     <video  width="86%" height="86%" controls="" autoplay=""> <source src="http://146.164.3.33:27896/labvadrj01.ogg?9ab451670db1e07b3a79f4fb938aa76d" type="video/ogg"></video> 
-->
 <!-- fim código link direto -->

<!-- BEGIN DB4K - teste - Código com link direto para a URL da UFRN-->
<!-- 
   <video width="86%" height="86%" controls="" autoplay=""> <source src="http://dev.labvad-ufrn01.pairg.ufrn.br:27896/streaming.ogg?485d423c0e960ed876091fb82d926e35" type="video/ogg"></video><br/>> 
-->
 <!-- fim código link direto -->
<!-- *********************************************************************************** -->



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

        <!-- Área com as listas de programas e vídeos salvos -->
        <div id="lista-codigo"  class="col-md-4 .col-xs-4">
            <div class="panel panel-default">
                <div role="tabpanel">

                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active rounded"><a href="#arqs" aria-controls="arqs" role="tab" data-toggle="tab" onclick="localStorage.setItem('db4k_tabAtiva', 'arqs');">Meus Arquivos</a></li>
                    <li role="presentation" class="rounded"><a href="#videos" aria-controls="videos" role="tab" data-toggle="tab" onclick="localStorage.setItem('db4k_tabAtiva', 'videos');">Meus vídeos</a></li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="arqs">
                        <!--<div class="panel-body box-codigo" style="display:none"> -->
                            <ul class="list-group listagem-codigo">salvar
                                <!-- ?php echo $TGrid; ? -->
                            </ul>
                      <!--  </div> -->
                    </div>

                    <div role="tabpanel" class="tab-pane" id="videos">
                        <div class="panel-body box-codigo">
                            <ul class="list-group listagem-video">
                                <!-- ?php echo $VGrid; ? -->
                            </ul>
                        </div>
                    </div>
                  </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Menu intermediário e área de edição de texto -->
    <div class="col-md-12 clearfix">
        <div class="panel panel-default box-visualiza-codigo clearfix">
            <div class="panel-heading clearfix" id="menuAcao" style="background-color:#26a69a" >
                <ul class="nav nav-pills">
                  <li><a href="db4k.php?acao=novo" id="novo-codigo" class="btAcao"  title="Criar novo código">Novo</a></li>

<!--Menu originak exemplos -->
                    <li><a href="#" id="codigo-exemplo" class="btAcao" title="Exemplos" data-toggle="dropdown">Exemplos<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="laboratorio.php?id=15&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-led">LED</a></li>                      
                            <li><a href="laboratorio.php?id=16&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-servo">Servo</a></li>                         
                            <li><a href="laboratorio.php?id=17&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-motordc">MotorDC</a></li>                         
                            <li><a href="laboratorio.php?id=18&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-display-caracteres">Display LCD</a></li>                                                     
                        </ul>   
                    </li>
<!-- fim menu originak exeplos -->

<!-- início exemplos DB4k 
                   <li><a href="#" id="codigo-exemplo" class="btAcao" title="Exemplos" data-toggle="dropdown">Exemplos<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" onclick="CarregaExemplo('db4kfiles/examples/piscar_led_vermelho.xml')" class="lkDiretoCodigo" id="exemplo-led">LED</a></li>

                            <li><a href="#" onclick="CarregaExemplo('db4kfiles/examples/mover_servo_motor.xml')" class="lkDiretoCodigo" id="exemplo-servo-motor">Servo Motor</a></li>                         
                            <li><a href="#" onclick="CarregaExemplo('db4kfiles/examples/girar_motor_DC.xml')" class="lkDiretoCodigo" id="exemplo-motor-DC">Motor DC</a></li>                         
                            <li><a href="#" onclick="CarregaExemplo('db4kfiles/examples/escrever_no_LCD.xml')" class="lkDiretoCodigo" id="exemplo-LCD">LCD</a></li>                                                                        
                        </ul>   
                    </li>
fim exemplos DB4K -->
                   <li><a href="#" id="salvar-codigo" class="btAcao" title="Salvar alterações" data-toggle="dropdown">Salvar<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="salvar">Salvar</a></li>
                            <li><a href="#" id="salvar-como">Salvar como</a></li>                         
                        </ul>   
                    </li>
                    <?php if ($permissaoExecutar) { ?>	
                        <?php if ($incluir_multiplexacao=='S') { ?>	
                        <li><a href="#" id="executar-codigo" class="btAcao dropdown-toggle" 
                               title="Escolha um dispositivo para executar seu experimento!" data-toggle="dropdown">Executar<span class="caret"></span></a>				
                            <ul class="dropdown-menu">
                                <li><a href="laboratorio.php?acao=executar&tipo=leds" class="executar-codigo-como">LED</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=display_caracteres" class="executar-codigo-como">Display de Caracteres</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=display_7" class="executar-codigo-como">Display de 7 segmentos</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=ledrgd" class="executar-codigo-como">LED RGB</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=servo" class="executar-codigo-como">Servo</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=motordc" class="executar-codigo-como">Motor DC</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=rele" class="executar-codigo-como">Relé</a></li>
                            </ul>
                        </li>
                        <?php } else { ?>
                        <li><a href="#" id="executar-codigo" class="btAcao dropdown-toggle" 
                               title="Execute seu experimento!"data-toggle="dropdown">Executar<span class="caret"></span></a>				
                            <ul class="dropdown-menu">
                                <li><a href="laboratorio.php?acao=executar" class="executar-codigo-como">Código Arduino</a></li>
                            </ul>
                        </li>

                        <?php } ?>
                    <?php } else { ?>
                    <li><a href="#" id="executar-codigo" class="btAcao dropdown-toggle"  data-toggle="dropdown"
                           title="Agende um horário para executar seus experimentos">Agende um horário<span class="caret"></span></a>
                           <?= $menu_agendamento ?>
                    </li>
                    <?php } ?>

                   <li><a href="#" id="enviar-codigo-arduino" class="btAcao file-inputs"  title="Enviar para Meus Códigos">Upload Arquivo</a>
                        <form name="formUploadCodigo" id="formUploadCodigo" method="post" enctype="multipart/form-data" action="laboratorio.php?acao=upload-arquivo&tipo=xml">
                            <input type="file" name="arquivo-codigo" id="arquivo-codigo">
                        </form>
                    </li>
                    <li><a href="download.php?acao=download&tipo=xml&id=<?php echo $telaId; ?>" target="_blank" 
                         id="dowload-codigo" class="btAcao" title="Download Arquivo">Download Arquivo</a></li>
		   <!--Modificação DB4K -->
 		  <li><a data-toggle="modal" href="#modalSobreDB4K" id="sobre" title="Sobre" style="color: #FFF">Sobre</a> <li>
		   <!--Fim Modificação DB4K -->
                </ul>
            </div>

                <?php
                echo getFormEditar($telaId, $telaNomeCodigo, $telaCodigo, $acao);
                ?>


<!-- ********************8EGIN DB4K ----início da área de blocos e de exibição de código *****************************************->
		
<!-- Content -->
<!--<div class="card-panel arduino_teal"> -->
<div  style="background-image: url(db4kfiles/ardublockly/img/db4kimages/fundoDB4k.jpg);margin-right: 20px; margin-left: 20px;">
<div style= "background-color : transparent; height: 30px">
</div>
    <div class="row"> 
      <div class="col s12 m12 l8" style="position:relative">
        <!-- Toolbox visibility button -->
        <a id="button_toggle_toolbox" class="waves-effect waves-light btn-flat button_toggle_toolbox_off" style="display: none"><i id="button_toggle_toolbox_icon" class="mdi-action-visibility-off"></i></a>
<!-- botão Executar -->
<?php if ($incluir_multiplexacao=='N') { ?>
 <?php if ($permissaoExecutar) { ?>
        <div id="ide_buttons_wrapper" >
          <a  id="button_ide_large" href="laboratorio.php?acao=executar" class="executar-codigo-como waves-effect waves-light waves-circle btn-floating z-depth-1-half arduino_orange"><i id="button_ide_large_icon" class="mdi-av-play-arrow"></i></a>
        </div>
  <?php } ?>
 <?php } ?>	
	<!--*****************************************************************************-->		

        <!-- Blockly Panel -->
        <div class="card-panel white" style="padding: 0">
          <div id="blocks_panel" class="content blocks_panel_large">
            <div id="content_blocks" class="content z-depth-1"></div>
          </div>
        </div>
      </div>
	  <!-- "Acordion" de exibição do código -->
      <div class="col s12 m12 l4">
        <ul class="collapsible z-depth-1" data-collapsible="accordion">
          <li>
			<div class="collapsible-header">
              <span class="collapsible_logo">{ }</span>Código
            </div>
            <div class="collapsible-body">
              <pre id="content_arduino" class="content content_height_transition content_arduino_large"></pre>
            </div>
          </li>
          <li>
            <div class="collapsible-header" id="xml_collapsible_header">
		     <span class="collapsible_logo"  style="display:none"></span>
            </div>
           <div class="collapsible-body" style="display:none" id="xml_collapsible_body">
              <a style="display:none" id="button_load_xml"><i class="mdi-action-extension" style="display:none"></i></a>
            <textarea id="content_xml" class="content content_height_transition content_xml_large" style="display:none" wrap="soft"></textarea>
	    <!--<textarea id="content_xml" class="content content_height_transition content_xml_large"  wrap="soft"></textarea> -->
            </div>
          </li>
        </ul>
      </div>
    </div>
</div>
<!-- ******** AND DB4K -  final área de blocos e exibição de código  ************************************************-->



        </div>

    </div>
</div>
</div>

<!--******************************************************** fim HTML Edição Código ***************************************************-->

<!-- modal dialogs -->

<div class="modal fade boxAlert">
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

<div class="modal fade boxPedido">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Informe o novo nome do arquivo: <input type="text" name="txtRenomear" id="txtRenomear" maxlength="70"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btRenomear" class="btn btn-primary" data-dismiss="modal">Gravar</button>
                <button type="button" id="btRenomearFechar" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade boxPergunta">
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

<div class="modal fade boxPerguntaConfirmacao">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Deseja realmente descartar a alteração do código?</p>
            </div>  
            <div class="modal-footer">
                <button type="button" id="btConfirmaPergunta" class="btn btn-primary" data-dismiss="modal">Sim</button>
                <button type="button" id="btCancelaPergunta" class="btn btn-danger" data-dismiss="modal">Não</button>
            </div>	
        </div>
    </div>
</div>

<!-- Modificação DB4K *****************************************************-->
    <!-- Modal "Sobre o DB4k"" -->
<div id="modalSobreDB4K" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Sobre o DuinoBlocks for Kids</h4>
                (Versão: Beta 1.0)
            </div>
            <div class="modal-body">
	<p>
	O <strong>DuinoBlocks for Kids</strong> (DB4k) é um projeto do <strong>GINAP</strong> 
	- Grupo de Informática Aplica à Educação (<a href="http://www.nce.ufrj.br/ginape" target="_blank">www.nce.ufrj.br/ginape</a>) responsável pela área de Informática, Educação e Sociedade do
	<strong>PPGI</strong> - Programa de Pós-Graduação em Informática da <strong>UFRJ</strong> (<a href="http://www.ppgi.ufrj.br" target="_blank">www.ppgi.ufrj.br</a>) em parceria com a 
	<strong>RNP</strong> – Rede Nacional de Pesquisa (<a href="http://www.rnp.br/" target="_blank">http://www.rnp.br/</a>)
	</p>
	<p>
	<img class="responsive-img" src="db4kfiles/ardublockly/img/db4kimages/logomarcas.png">
	</p>
	<p>
	O <strong>DB4K</strong> é um ambiente de programação em blocos para placas de prototipagem eletrônicas <strong>Arduino</strong>. Trata-se de um software livre ainda em fase de desenvolvimento e que tem como objetivo o apoio ao ensino de conceitos básicos de programação para crianças do ensino fundamental 1.
	</p>
	<p>Em breve estará disponível para download uma versão “cliente-side” do 
	<strong>DB4K</strong> que permite o envio dos programas nele desenvolvidos diretamente para as placas <strong>Arduino</strong>:.
	</p>
	<p>
	O DuinoBlocks for Kids foi desenvolvido com base no Ambiente Ardublockly (<a href="http://www.embeddedlog.com/ardublockly/ardublockly/index.html" target="_blank">http://www.embeddedlog.com/ardublockly/ardublockly/index.html#</a>) e 
	inspirado no DuinoBlocks (<a href="http://www.duinoblocks.com.br" target="_blank">www.duinoblocks.com.br</a>).&nbsp; 
	Ele utiliza as bibliotecas Blockly (<a href="https://developers.google.com/blockly" target="_blank">https://developers.google.com/blockly/</a> ) e Materialize (<a href="http://materializecss.com" target="_blank">http://materializecss.com/</a>). Seu código fonte em breve estará disponível para download no GitHub (<a href="https://github.com/" target="_blank">https://github.com/</a>).
	</p>
	<p>
	</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

 <!-- *********************************************************************-->

<!-- boxVideo -->

<div class="modal fade boxVideo">
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

    var acaoPedida = "", urlChamada = "", idCodigoExclusao = 0, caminhoExclusao = "";        

    function ativaTab(tab){
        // Ativa uma das tabs "Meus videos" ou "Meus Arquivos"
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
    };

    function carregaArqs() {
        // Refresh na lista meus arquivos
        $("ul.listagem-codigo").append('Carregando...').load('laboratorio.php?acao=listagem&tipo=xml');
    }

    function carregaVideos() {
        // Refresh na lista meus vídeos
        $("ul.listagem-video").append('Carregando...').load('videos.php?acao=lista');
    }

    function limpaAreaCodigo() {
        $("#txtNomeCodigo, #idCodigo, #txtCodigo").val('');
        editor.setValue('');
    }

    function aguarde(exibir) {
        if (exibir)
            $("#msg-carregando").show();
        else
            $("#msg-carregando").hide(5000);
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

    $(document).ready(function () {

        var nomeCodigoExclusao = "";        
        //var acaoPedida = "", urlChamada = "", idCodigoExclusao = 0, caminhoExclusao = "";        

        // Testa a cada segundo se atingiu a hora limite da sessão reservada no laboratório remoto.
        <?php if ($permissaoExecutar) { ?>
        idInt = window.setInterval(ctrlTimeout, 1000); 
        <?php } ?>
        
        // Carrega tabs com lista de arquivos e vídeos
        carregaArqs();
        carregaVideos();

        // Inicializa o editor de programas
        var editor = ace.edit("txtCodigo");
        editor.setTheme("ace/theme/crimson_editor");
        editor.getSession().setMode("ace/mode/c_cpp");
        editor.resize();
        editor.getSession().setUseWrapMode(false);
        editor.blockScrolling = Infinity;

        // Inicializa autosave
        localStorage.setItem("db4k_comparaCodigo", localStorage.getItem("db4k_codigo"));

        // Inicializa tab ativa
        ativaTab(localStorage.getItem("db4k_tabAtiva"));


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
                $.post("videos.php?acao=save",
                    {},
                    function (retorno) {

                        if (retorno.erro === 0) {
                            //emite mensagem dizendo que o descritor do vídeo foi salvo com sucesso.
                            //$("#mensagem-local").html(retorno.msg);
                            //$('.boxAlert').modal('show');

                            // chama o servidor remoto para iniciar gravação do arquivo de vídeo
                            // alert ("chamando: <?=$ip_lab?>/labvad-remoteserver/streaming/php/recordStream.php");
                            $.post("<?=$ip_lab?>/labvad-remoteserver/streaming/php/recordStream.php",
                                { id: retorno.id },
                                function (retorno) {
                                    if (retorno.erro === 0) {
                                        // recebe info do processo que está salvando o vídeo
                                    	gPid = retorno.pid;
                                    	gRNameOf = retorno.rNameOf;
                                    	gExtOf = retorno.extOf;

                                        $("#mensagem-local").html(retorno.msg);
                                        $('.boxAlert').modal('show');
                                    }
                                    else if (retorno.erro > 0) {
                                        $("#mensagem-local").html(retorno.msg);
                                        $('.boxAlert').modal('show');
                                    }
                                },
                                'json'
                            );

                        }
                        else if (retorno.erro > 0) {
                            $("#mensagem-local").html(retorno.msg);
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
                $.post("<?=$ip_lab?>/labvad-remoteserver/streaming/php/stopStreamRecording.php",
                    { pid: gPid, rnameof: gRNameOf, extof: gExtOf },

                    function (retorno) {
                        // alert ("retorno="+retorno.erro);
                        if (retorno.erro === 0) {
                            $("#mensagem-local").html(retorno.msg);
                            $('.boxAlert').modal('show');
                            carregaVideos();
                            ativaTab('videos');
                        }
                        else if (retorno.erro > 0) {
                            $("#mensagem-local").html(retorno.msg);
                            $('.boxAlert').modal('show');
                        }
                    },
                    'json'
                );
            }
        });
         

        // Callbacks: Salvar e Salvar como

        $('#salvar-como').on('click', function () {
            $(".boxPedido").modal('show');
            $("#txtRenomear").val($('#txtNomeCodigo').val());
        });

        $("#btRenomearFechar").on('click', function () {
            $(".boxPedido").modal('hide');
            return false;
        });

        $("#btRenomear").on('click', function () {
            var nome = $("#txtRenomear").val();
            $("#idCodigo").val(0);
            if (nome === '') {
                return false;
            }
            else {
                $('#txtNomeCodigo').val(nome);
                $("#salvar").click();
            }
        });

        $('#salvar').on('click', function () {
            caminho   = $('#formCodigo').attr('action');
            mIdCodigo = $('#idCodigo').val();
            //Modificação DB4k Salvar Código
            //mCodigo   = editor.getValue();
	         mCodigo   =  Ardublockly.generateXml();
	        //Fim Modificação DB4K
            mNomeCodigo = $('#txtNomeCodigo').val();

            if (mNomeCodigo === '') {
                $("#mensagem-local").html('Informe o nome do seu programa!');
                $('.boxAlert').modal('show');
                return false;
            }
            $.post(caminho,
                {
                    idCodigo: mIdCodigo,
                    txtCodigo: mCodigo,
                    txtNomeCodigo: mNomeCodigo
                },
                function (retorno) {
                    if (retorno.erro === 0) {
                        $("#idCodigo").val(retorno.id);
                        $('#txtNomeCodigo').val(retorno.nome);

                        $("#mensagem-local").html(retorno.msg);
                        $('.boxAlert').modal('show');
                        localStorage.setItem("db4k_alterado", "n");
			//Modificação DB4k
			codigo_xml = document.getElementById("content_xml").value;
			localStorage.setItem("db4k_comparaCodigo", codigo_xml);
                        //localStorage.setItem("db4k_comparaCodigo", editor.getValue()); //$("#txtCodigo").val());
			//---------------------------------------------
                        carregaArqs();
                    }
                    else if (retorno.erro > 0) {
                        $("#mensagem-local").html(retorno.msg);
                        $('.boxAlert').modal('show');
                        carregaArqs();
                    }

                },
                'json'
            );

            return false;
        });



        // Callback: Executar código arduino

        $('.executar-codigo-como').click(function () {
            caminho     = $(this).attr('href');
            mIdCodigo   = $('#idCodigo').val();
            //mCodigo     = editor.getValue(); //$('#txtCodigo').val();
            //Para o DB4K a linha acima foi substituida pela linha abaixo
	    //Modificação DB4K
 	    mCodigo =  Blockly.Arduino.workspaceToCode(Ardublockly.workspace);
	    //------------------------------------------------------------------
            mNomeCodigo = $('#txtNomeCodigo').val();

            $.post(caminho,
                {
                    idCodigo: mIdCodigo,
                    txtCodigo: mCodigo,
                    txtNomeCodigo: mNomeCodigo,
                    salvar_video: $('#salvar_video').is(":checked") ? 'T' : 'F'
                },
                function (retorno) {
                    if (retorno.erro === 0) {
                        $(".boxAlert #mensagem-local").html(retorno.msg);
                        $('.boxAlert').modal('show');
                    }
                    else if (retorno.erro === 3) {
                        $(".boxAlert #mensagem-local").html("Erro de Compilação. Verifique seu programa.<br><pre>"+retorno.msg+"</pre>");
                        $('.boxAlert').modal('show');
                    }
                    else {
                        $(".boxAlert #mensagem-local").html("Erro.<br><pre>"+retorno.msg+"</pre><br>DEBUG: "+retorno.debug);
                        $('.boxAlert').modal('show');
                    }
                },
                'json'
            );

            return false;
        });


        // Callback: Excluir código arduíno

        $('ul.listagem-codigo').on('click', '.lkExclusaoCodigo a', function (){
            myUrl            = $(this).attr('href');
            parametros       = myUrl.split("?")[1];
            caminhoExclusao  = myUrl;
            idCodigoExclusao = parametros.split("=")[2];

            if ((idCodigoExclusao === 0) || (idCodigoExclusao === "")) {
                $(".boxAlert #mensagem-local").html('Selecione o código para realizar a exclusão!');
                $('.boxAlert').modal('show');
                return false;
            }
            else {
                $(".boxPergunta #mensagem-local").html('Deseja realmente excluír esse código?');
                $(".boxPergunta").modal('show');
            }
            
            return false;
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


        // Callback: Respostas do popup para confirmar exclusão

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
                    $(".boxAlert #mensagem-local").html(retorno);
                    $('.boxAlert').modal('show');

                    //document.location.reload();
                    if (localStorage.getItem("db4k_tabAtiva") == "videos") carregaVideos();
                    if (localStorage.getItem("db4k_tabAtiva") == "arqs") carregaArqs();

                    //limpaAreaCodigo();
                }
            );

            return false;
        });


        // Callback: Upload código arduíno

        $('#enviar-codigo-arduino').on('click', function () {
            acaoPedida = "enviar-codigo";
            if (verificaSalvarCodigo()) {
                localStorage.setItem("db4k_nomeCodigo", "");
                localStorage.setItem("db4k_codigo", "");
                $('#arquivo-codigo').click();
            }
            
            return false;
        });

        $('#arquivo-codigo').on('change', function () {
            $('#formUploadCodigo').ajaxForm({
                dataType: 'json',
                success: function (retorno) {
                    $('#idCodigo').val(retorno.id);
                    $("#txtNomeCodigo").val(retorno.nome);
                    editor.setValue(retorno.codigo); //$("#txtCodigo").val(retorno.codigo);

                    $("#dowload-codigo").attr('href', 'download.php?acao=download&tipo=xml&id=' + retorno.id);
                    $("#exclusao-codigo").attr('href', 'laboratorio.php?acao=exclusao&id=' + retorno.id);

                    $("#mensagem-local").html(retorno.msg);
                    $('.boxAlert').modal('show');

                    carregaArqs();
                }
            }).submit();
        });

        // Callback: Download código arduíno

        $('#dowload-codigo').on('click', function () {
            var urlDownload = $(this).attr('href');
            var parametros  = urlDownload.split('?')[1];
            var id          = parametros.split('&')[1].split('=')[1];
            if (parseInt(id) === 0) {
                $(".boxAlert #mensagem-local").html('Selecione o código para realizar o download!');
                $('.boxAlert').modal('show');
                return false;
            }
        });

        
        // Callback: Abrir código exemplo

        $('a.lkDiretoCodigo').on('click', function () {
           acaoPedida = "abre-codigo";
           urlChamada = $(this).attr('href') + '&metodo=ajax';
            if (verificaSalvarCodigo()) {
                localStorage.setItem("db4k_nomeCodigo", "");
                localStorage.setItem("db4k_codigo", "");
                localStorage.setItem("db4k_alterado", "");
                $("#btConfirmaPergunta").click();
            }
            
            return false;
        });


        // Callback: Abrir código salvo pelo usuário

        $('ul.listagem-codigo').on('click', '.itemCodigo', function (){
            //myUrl = this.href;
            //urlChamada = myUrl + '&metodo=ajax';
            acaoPedida = "abre-codigo";
            urlChamada = $(this).attr('href') + '&metodo=ajax';
            if (verificaSalvarCodigo()) {
                localStorage.setItem("db4k_nomeCodigo", "");
                localStorage.setItem("db4k_codigo", "");
                localStorage.setItem("db4k_alterado", "");
                $("#btConfirmaPergunta").click();    
            }
            return false;
        });


        // Callback: Resposta SIM à pergunta "Deseja realmente descartar a alteração do código?"

        $("#btConfirmaPergunta").on("click", function() {

            localStorage.setItem("db4k_nomeCodigo", "");
            localStorage.setItem("db4k_codigo", "");
            
            if (acaoPedida === "novo") {    
                localStorage.setItem("db4k_nomeCodigo", "");
                localStorage.setItem("db4k_codigo", "");
                localStorage.setItem("db4k_idCodigo", "");
                localStorage.setItem("db4k_comparaCodigo", "");
                localStorage.setItem("db4k_alterado", "n");
                $(".boxEsp").modal('show');    
                document.location.href = $("#novo-codigo").attr('href');
            }
            else if (acaoPedida === "enviar-codigo") {
                localStorage.setItem("db4k_nomeCodigo", "");
                localStorage.setItem("db4k_codigo", "");
                localStorage.setItem("db4k_alterado", "");
                $('#arquivo-codigo').click();
            }
            else if (acaoPedida === "logout") {
                document.location.href = $("#logout").attr('href');
            }
            else if (acaoPedida === "abre-codigo") {
                var url = urlChamada; //$(this).attr('href') + '&metodo=ajax';
                aguarde(true);
                //alert ("chamando:"+urlChamada);

                $.post(
                    url,
                    function (retorno) {
                        if (retorno.erro === 1) {
   			   if (Ardublockly.replaceBlocksfromXml(retorno.codigo)) {
                            $("#idCodigo").val(retorno.id);
                            $("#txtNomeCodigo").val(retorno.nome).attr('disabled', 'disabled');
                            editor.setValue(retorno.codigo); //$("#txtCodigo").val(retorno.codigo);
                            localStorage.setItem("db4k_alterado", "n");
                            localStorage.setItem("db4k_comparaCodigo", retorno.codigo);

                            $("#dowload-codigo").attr('href', 'download.php?acao=download&tipo=xml&id=' + retorno.id);
                            $("#exclusao-codigo").attr('href', 'laboratorio.php?acao=exclusao&id=' + retorno.id);

   			    //Modificação DB4K - Renderiza Código XML
			    //Ardublockly.replaceBlocksfromXml(retorno.codigo);
			    //db4k_replaceBlocksfromXml(retorno.codigo);
    			    Ardublockly.renderContent();
    			    } else {
         			alert('Este não é um Programa de Blocos do DuinoBlocks for Kids',false);
    			    }

                            //----------------------------------------
                        }
                    },
                    'json'
                )
                .done(function () {

                })
                .fail(function () {

                })
                .always(function () {
                    aguarde(false);
                });
            }
        });


        // Callback: Criar novo código

        $("#novo-codigo").on("click", function () {
            acaoPedida = "novo";
            if (verificaSalvarCodigo()) {
		//Modificação DB4K - Limpa Blocos do Editor de Blocos
		Ardublockly.discardAllBlocks();
                localStorage.setItem("db4k_nomeCodigo", "");
                localStorage.setItem("db4k_codigo", "");
                localStorage.setItem("db4k_idCodigo", "");
                localStorage.setItem("db4k_comparaCodigo", "");
                localStorage.setItem("db4k_alterado", "n");
                $(".boxEsp").modal('show');
            }
            else {                
                return false;
            }
        });


        // Callback: Logout

        $("#logout").on("click", function () {
            acaoPedida = "logout";
            if (verificaSalvarCodigo()) {
                $(".boxLogout").modal('show');
                return false;
            }
        });
        
        $("#btLogoutSim").on("click", function () {
            document.location.href = $("#logout").attr('href');
                $("#btConfirmaPergunta").click();
        });

        var lkSaida = "";

        $("#btConfirmarSaida").on("click", function () {
            if (lkSaida !== '') {
		//Modificação DB4k  - limpa localstorage para não carregar o programa de um usuário anterior
                localStorage.setItem("db4k_nomeCodigo", "");
                localStorage.setItem("db4k_codigo", "");
                localStorage.setItem("db4k_alterado", "");
                document.location.href = lkSaida;
            }
        });

        if (editor.getValue() === "") {
            $("#txtNomeCodigo").val(localStorage.getItem("db4k_nomeCodigo"));
            editor.setValue(localStorage.getItem("db4k_codigo")); //$("#txtCodigo").val(localStorage.getItem("db4k_codigo"));
            $("#idCodigo").val(localStorage.getItem("db4k_idCodigo"));
            if ($("#idCodigo").val() > 0) {
                $("#txtNomeCodigo").attr('disabled', 'disabled');
            }
        }

        /*$("#txtCodigo").keydown(function () {
            gravarTemp();
            localStorage.setItem("db4k_alterado", "s");
        });*/
        
        editor.getSession().on('change', function(e) {
            gravarTemp();
            localStorage.setItem("db4k_alterado", "s");
        });

        $("#menu-navegacao-horizontal a").on("click", function () {
            lkSaida = $(this).attr("href");
            gravarTemp();
            return true;
        });

        
        // Funções auxiliares para autosave

        function verificaSalvarCodigo() {

            /* False = pergunta; True = sem pergunta */
            var id       = $("#idCodigo").val();
            var nome     = $("#txtNomeCodigo").val();
            var codigo   = editor.getValue(); //$("#txtCodigo").val();
            var alterado = localStorage.getItem("db4k_alterado");
            var retorno  = true;
            if ((acaoPedida === "novo") || (acaoPedida === "enviar-codigo") || (acaoPedida === "logout") || (acaoPedida === "abre-codigo")) {         
                if ((alterado === "s") || ((id === "") && ((nome !== "") || (codigo !== "")))) {
                    $(".boxPerguntaConfirmacao .mensagem-local").html('Deseja realmente descartar a alteração do código?');
                    $(".boxPerguntaConfirmacao").modal('show');
                    retorno = false;
                }  
                else {
                    retorno = true;
                }
            }            
            
            return retorno;
        }

        function gravarTemp() {
            var nomeCodigo = $("#txtNomeCodigo").val();
	    //Modificação DB4K
            //var codigo     = editor.getValue(); //$("#txtCodigo").val();
	    var codigo = document.getElementById("content_xml").value;
	    //----------------------------------------------
            var idCodigo   = $("#idCodigo").val();
            localStorage.setItem("db4k_nomeCodigo", nomeCodigo);
            localStorage.setItem("db4k_codigo", codigo);
            localStorage.setItem("db4k_idCodigo", idCodigo);
        }
    });
</script>

<style>
.ace_editor { height: 510px}
</style>

<script src="js/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<!-- BEGIN DB4K**********************************************************************-->
 <!--Inicialização dos valores dos pinos dos blocos DB4K -->
 <script src="db4kfiles/blockly/generators/db4k/carrega_valores_pinos.js"></script>
 <script>
	carrega_valores_pinos_arduino();
 </script>	
  <!--Funcoes Para Labvad -->
  <script src="db4kfiles/ardublockly/funcoesParaLabVad.js" charset="UTF-8"> </script>
  <!-- Materialize, Prettify, and Ardublockly CSS -->
  <link href="db4kfiles/ardublockly/materialize/materialize.css" rel="stylesheet" media="screen,projection">
  <link rel="stylesheet" href="db4kfiles/ardublockly/prettify/arduino.css">
  <link href="db4kfiles/ardublockly/ardublockly.css" rel="stylesheet" media="screen,projection">
  <!-- Ardublockly - For now uncompressed files -->
  <!--script src="db4kfiles/blockly/blockly_compressed.js"></script-->
  <script src="db4kfiles/blockly/blockly_uncompressed.js"></script>
  <!--script src="db4kfiles/blockly/blocks_compressed.js"></script-->
  <script src="db4kfiles/blockly/generators/arduino.js"></script>
  <script src="db4kfiles/blockly/generators/arduino/boards.js"></script>
  <script src="db4kfiles/blockly/blocks/logic.js"></script>
  <script src="db4kfiles/blockly/blocks/loops.js"></script>
  <script src="db4kfiles/blockly/blocks/math.js"></script>
  <script src="db4kfiles/blockly/blocks/text.js"></script>
  <script src="db4kfiles/blockly/blocks/lists.js"></script>
  <script src="db4kfiles/blockly/blocks/colour.js"></script>
  <script src="db4kfiles/blockly/blocks/variables.js"></script>
  <script src="db4kfiles/blockly/blocks/procedures.js"></script>
  <!--Blocos DB4K-->
  <script src="db4kfiles/blockly/blocks/db4k/componentes.js"></script>
  <script src="db4kfiles/blockly/blocks/db4k/controles.js"></script> 
  <!--Códigos DB4K-->
  <script src="db4kfiles/blockly/generators/db4k/componentes.js"></script>
  <script src="db4kfiles/blockly/generators/db4k/controles.js"></script>
  <!-- ************-->  
  <script src="db4kfiles/blockly/msg/js/pt-br.js"></script>
  <!-- jQuery and Materialize JS -->
  <!--<script src="db4kfiles/ardublockly/js_libs/jquery-2.1.3.min.js"></script> -->
  <script src="db4kfiles/ardublockly/materialize/materialize.js"></script>
  <!-- FileSaver JS -->
  <script src="db4kfiles/ardublockly/js_libs/FileSaver.min.js"></script>
  <!-- JS Diff -->
  <script src="db4kfiles/ardublockly/js_libs/diff.js"></script>
  <!-- Prettify JS -->
  <script src="db4kfiles/ardublockly/prettify/prettify.js"></script>
  <!-- Ardublockly app -->
  <script src="db4kfiles/ardublockly/ardublocklyserver_ajax.js"></script>
  <script src="db4kfiles/ardublockly/ardublockly_design.js"></script>
  <script src="db4kfiles/ardublockly/ardublockly_blockly.js"></script>
  <script src="db4kfiles/ardublockly/ardublockly.js"></script>

<!-- END DB4K**********************************************************************-->

<?php require_once 'app.include/footer.inc.php'; ?>



