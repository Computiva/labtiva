<?php set_time_limit(20); ?>
<?php
// busca laboratorios ativos do tipo selecionado
$sql = $conn->prepare("SELECT laboratorios.id, laboratorios.nome, nome_tipo
               FROM laboratorios
               JOIN laboratorios_tipo 
               ON laboratorios.fk_lab_tipo = laboratorios_tipo.id
               WHERE laboratorios.estado='A'
               AND fk_lab_tipo = :tipo");
$sql->bindParam(":tipo", $_SESSION['id_tipo']);
$sql->execute();

$menu_text = '';
while ($resultado = $sql->fetchObject()) {
if ($menu_text == '') $menu_text = "<ul class='dropdown-menu'>";
$menu_text .= "<li><a href='agendamentos.php?lab={$resultado->id}' >{$resultado->nome}</a></li>";
}
if ($menu_text != '') $menu_text .= "</ul>";
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
          <a class="navbar-brand" href="labvad.php">LabVad</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#" class="btAcao dropdown-toggle" title="Agendar horário num dos laboratórios" data-toggle="dropdown">
                Agendamento<span class="caret"></span></a>
                <?= $menu_text; ?>
						
            </li>
            <li><a href="laboratorio.php" id="lkExperimento">Experimento</a></li>
            <li><a href="ajuda.php">Ajuda</a></li>
            <li><a href="usuarios.php">Configurações</a></li>
            <li><a href="logout.php" id="logout">Sair</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container" id="conteudo">

      <div class="starter-template">
