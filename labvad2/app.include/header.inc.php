<?php set_time_limit(20); ?>
<?php
// busca tipos de experimentos que possuam pelo menos um laboratório ativo, para criar o menu de experimentos
$sql = $conn->prepare("SELECT DISTINCT laboratorios_tipo.id, laboratorios_tipo.nome_tipo FROM laboratorios_tipo
                       JOIN laboratorios ON laboratorios_tipo.id=laboratorios.fk_lab_tipo
                       WHERE laboratorios.estado='A'");

$sql->execute();

$menu_experimento = '';
while ($resultado = $sql->fetchObject()) {
if ($menu_experimento == '') $menu_experimento = "<ul class='dropdown-menu'>";
$menu_experimento .= "<li><a href='select_tipo.php?tipo={$resultado->id},{$resultado->nome_tipo}' >{$resultado->nome_tipo}</a></li>";
}
if ($menu_experimento != '') $menu_experimento .= "</ul>";

// busca laboratorios ativos do tipo selecionado
$sql = $conn->prepare("SELECT laboratorios.id, laboratorios.nome, nome_tipo
               FROM laboratorios
               JOIN laboratorios_tipo 
               ON laboratorios.fk_lab_tipo = laboratorios_tipo.id
               WHERE laboratorios.estado='A'
               AND fk_lab_tipo = :tipo");
$sql->bindParam(":tipo", $_SESSION['id_tipo']);
$sql->execute();

$menu_agendamento = '';
while ($resultado = $sql->fetchObject()) {
if ($menu_agendamento == '') $menu_agendamento = "<ul class='dropdown-menu'>";
$menu_agendamento .= "<li><a href='agendamentos.php?lab={$resultado->id}' >{$resultado->nome}</a></li>";
}
if ($menu_agendamento != '') $menu_agendamento .= "</ul>";

$menu_execucao = '';
if ($_SESSION['id_tipo']=='1') {
    $menu_execucao = "<ul class='dropdown-menu'>";
    $menu_execucao .= "<li><a href='laboratorio.php' >Programação Arduíno</a></li>";
    $menu_execucao .= "<li><a href='db4k.php' >DuínoBlocks for Kids</a></li>";
    $menu_execucao .= "</ul>";
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>LabVad: Laboratório Virtual de Atividades Didáticas em Ciências e Robótica</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/labvab.css" rel="stylesheet">
    
    <script src="js/jquery.min.js"></script>
    <script src="js/json.js"></script>
    <script src="js/jquery.form.js"></script>
 
    <script src="js/bootstrap.min.js"></script>

    <script src="js/bootstrap.file-input.js"></script>
    
    <link href='css/calendario/fullcalendar.css' rel='stylesheet' />
    <link href='css/calendario/fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src='js/calendario/lib/moment.min.js'></script>
    <script src='js/calendario/fullcalendar.min.js'></script>
    <script src='js/calendario/lang-all.js'></script>

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->



  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="menu-navegacao-horizontal">
      <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Navegação</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="labvad.php">LabVad</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#" class="btAcao dropdown-toggle" title="Selecionar um tipo de experimento" data-toggle="dropdown">
                Experimento<span class="caret"></span></a>
                <?= $menu_experimento; ?>	
            </li>

            <li><a href="#" class="btAcao dropdown-toggle" title="Agendar horário num dos laboratórios" data-toggle="dropdown">
                Agenda<span class="caret"></span></a>
                <?= $menu_agendamento; ?>		
            </li>

            <?php if ($_SESSION['id_tipo']=='1') { ?>
            <li><a href="#" class="btAcao dropdown-toggle" title="Selecionar ambiente de execução" data-toggle="dropdown">
                Execu&ccedil;&atilde;o<span class="caret"></span></a>
                <?= $menu_execucao; ?>	
            </li>	
            <?php } else { ?>
            <li><a href="laboratorio.php" id="lkExperimento">Execu&ccedil;&atilde;o</a></li>
            <?php } ?>

            <li><a href="usuarios.php">Configurações</a></li>
            <li><a href="ajuda.php" title="Ajuda">?</a></li>
            <li><a href="logout.php" id="logout" title="Sair">Sair</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container" id="conteudo">

      <div class="starter-template">
