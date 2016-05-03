<?php
$acaoLog = 'Acesso ao sistema';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.include/header.inc.php';

$ok = isset($_GET['ok']) ? $_GET['ok'] : "";

//Obtendo lista de grupos, que possuam pelo menos um laboratório ativo, para criar o menu do agendamento
$sql = $conn->prepare("SELECT DISTINCT laboratorios_tipo.id, laboratorios_tipo.nome_tipo FROM laboratorios_tipo
                       JOIN laboratorios ON laboratorios_tipo.id=laboratorios.fk_lab_tipo
                       WHERE laboratorios.estado='A'");
$sql->execute();
$menu_text = '';
while ($resultado = $sql->fetchObject()) {
        if ($menu_text == '') $menu_text = "<ul>";
        if (isset($_SESSION['id_tipo']) && $_SESSION['id_tipo']==$resultado->id)
                $menu_text .= "<input type='radio' name='tipo' value='{$resultado->id},{$resultado->nome_tipo}' checked='checked' />&nbsp;{$resultado->nome_tipo}<br/>";
        else
                $menu_text .= "<input type='radio' name='tipo' value='{$resultado->id},{$resultado->nome_tipo}' />&nbsp;{$resultado->nome_tipo}<br/>";
}
if ($menu_text != '') $menu_text .= "</ul><input type='submit' value='Selecionar Experimento...' />";

?>

<style type="text/css">
  #map {
    height: 400px;
    width: 70%;
  }
</style>

<body>
    <blockquote>
        <h1>Introdução</h1>
        <p>Bem vindo ao Cons&oacute;rcio de Laboratórios Virtuais de Atividades Didáticas em Ciências e Robótica (LabVad). Se você chegou até aqui é porque seu 
        cadastro foi validado com sucesso.</p>
        <p>O LabVad é de acesso livre, multiplataforma 
        e não necessita de extensões ou plugins para ser executado no seu computador. 
        Se voc&ecirc; quiser saber mais sobre o LabVad <a href="http://www.nce.ufrj.br/labvad">clique 
        aqui</a>. <!--Para ter acesso &agrave; documenta&ccedil;&atilde;o acad&ecirc;mica 
        e t&eacute;cnica ent&atilde;o <a href="#doc">clique aqui</a>.-->
        </p>
    </blockquote>

    <blockquote>
        <h1>Como utilizar o LabVad?</h1>
        <p>H&aacute; duas maneiras de utilizar o LabVad: </p>

        <ol>
          <li> 
                <p>O usu&aacute;rio quer realizar um experimento 
                  j&aacute; existente na rede LabVad:</p>


                    <p>Neste caso, v&aacute; ao topo desta p&aacute;gina e cumpra as 
                      tr&ecirc;s etapas indicadas em sequ&ecirc;ncia:</p>

            <ol>
              <li> 
                <p><b>EXPERIMENTO</b>. Selecione aqui o tipo de 
                  experimento que voc&ecirc; deseja fazer.</p>
              </li>
              <li> 
                <p><b>AGENDAMENTO</b>. Primeiramente selecione 
                  em uma lista os laborat&oacute;rios da
                  rede LabVad que disponibilizam o experimento escolhido por voc&ecirc;. 
                  Logo em
                  seguida, aparecer&aacute; uma tabela com os hor&aacute;rios 
                  dispon&iacute;veis ao longo do m&ecirc;s. Ao clicar em um hor&aacute;rio, imediatamente aparecer&aacute; 
                  o seu nome de usu&aacute;rio.
                  Para desagendar um hor&aacute;rio e liber&aacute;-lo para outro usu&aacute;rio, 
                  basta clicar novamente.</p>
              </li>
              <li> 

                <p><b>EXECU&Ccedil;&Atilde;O</b>. Caso voc&ecirc; tenha clicado nesta op&ccedil;&atilde;o no hor&aacute;rio 
                  previamente reservado, voc&ecirc; 
                  ser&aacute; direcionado diretamente para o Laborat&oacute;rio agendado,
                  sen&atilde;o receber&aacute; a mensagem "N&atilde;o h&aacute; laborat&oacute;rio reservado no momento". </p>

                <ol>
                  <li> 
                        <p>A tela do Laborat&oacute;rio &eacute; formada na parte superior por 
                          uma imagem de v&iacute;deo onde<br>
                          voc&ecirc; pode ver em 'tempo real' o experimento sendo executado 
                          e, eventualmente,
                          grav&aacute;-la clicando no bot&atilde;o que se encontra logo 
                          abaixo da mesma.</p>
                  </li>
                  <li> 
                        <p>O restante da tela &eacute; composto de informa&ccedil;&otilde;es 
                          (texto, imagem, formul&aacute;rios, etc.) para execu&ccedil;&atilde;o do experimento, nos laborat&oacute;rios de ci&ecirc;ncias, e de
                            uma &aacute;rea para edi&ccedil;&atilde;o de programas, nos laborat&oacute;rios de rob&oacute;tica.
                        </p>
                  </li>
                </ol>
              </li>
            </ol>
              <br/>
              <p><b>Nota</b>: Os dois &uacute;ltimos itens dispon&iacute;veis no topo da p&aacute;gina 
                atual s&atilde;o respectivamente para &quot;AJUDA&quot; 
                e para altera&ccedil;&otilde;es na &quot;CONFIGURA&Ccedil;&Atilde;O&quot; 
                do seu perfil no LabVad (p.ex.: mudan&ccedil;a de senha).</p>
          </li>

          <br/>
          <li> 
                <p>O usu&aacute;rio quer incluir um novo laborat&oacute;rio com um experimento 
                  de sua autoria na rede LabVad:</p>

                <p>Neste caso entre em contato com a coordenação do projeto em <b>ffs@nce.ufrj.br</b>.
                </p>
          </li>
        </ol>
    </blockquote>

    <blockquote>
    <h1>Localização dos laboratórios</h1>
    <!-- mapa com localização dos laboratórios -->
    <div id="map"></div>
    <script type="text/javascript">
        function initMap() {
          var centro = {lat: -13.02596593, lng: -45.48339844};
          var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: centro
          });

          var ufrj01 = {lat: -22.85820311, lng: -43.23223114};
          var ufrn01 = {lat: -5.78102, lng: -35.197327};

          var contentString1 = '<div id="content">'+
              '<div id="siteNotice">'+
              '</div>'+
              '<h1 id="firstHeading" class="firstHeading">Labvad-UFRJ01 e Labvad-UFRJ02</h1>'+
              '<div id="bodyContent">'+
              '<p>Laboratórios de programação Arduíno e do experimento trilho de ar localizados no NCE/UFRJ. </p>' +
              '</div>'+
              '</div>';

          var contentString2 = '<div id="content">'+
              '<div id="siteNotice">'+
              '</div>'+
              '<h1 id="firstHeading" class="firstHeading">Labvad-UFRN01</h1>'+
              '<div id="bodyContent">'+
              '<p>Laboratório de programação Arduíno localizado no PAIRG/UFRN. </p>' +
              '</div>'+
              '</div>';

          var infowindow1 = new google.maps.InfoWindow({
            content: contentString1
          });

          var infowindow2 = new google.maps.InfoWindow({
            content: contentString2
          });

          var marker1 = new google.maps.Marker({
            position: ufrj01,
            map: map,
            title: 'Labvad-UFRJ01 e Labvad-UFRJ02'
          });
          marker1.addListener('click', function() {
            infowindow1.open(map, marker1);
          });

          var marker2 = new google.maps.Marker({
            position: ufrn01,
            map: map,
            title: 'Labvad-UFRN01'
          });
          marker2.addListener('click', function() {
            infowindow2.open(map, marker2);
          });

        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap"></script>


    <br/>Veja a imagem dos laboratórios do LabVAD em tempo real no <a href="painel_labs.php">Painel de laboratórios</a>.



    </blockquote>

    <blockquote>
        <p><span class="animated">| <a href="ajuda.php">Ajuda</a> | <a href="agendamentos.php">Agendamentos </a>| <a href="laboratorio.php">Experimentos</a> |  <a href="img/LabVad_Guia.pdf">Baixar Guia do LabVad </a>|</span></p>
    </blockquote>

    <div class="footer-meta">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </div>
   
<div class="modal boxAlert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">
                Tipo de experimento alterado com sucesso!
		</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
 
<script type="text/javascript">
    $(document).ready(function () {
            if ('<?= $ok ?>' == '1') {
                //alert ("Tipo de experimento alterado com sucesso!");
                $('.boxAlert').modal('show');
            }
    });
</script>

<?php require_once 'app.include/footer.inc.php'; ?>


