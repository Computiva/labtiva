<?php
$acaoLog = 'Agendamento Help';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.include/header.inc.php';
?>

<title>Agendar - Ajuda</title>

<body>
    <blockquote>
        <h2>Como agendar um horário para usar o LabVad?</h2>
        <p>Basta clicar em Agendamento no menu superior para visualizar nosso calendário de agendamentos, como na imagem abaixo. </p>
        <h2><a href="agendamentos.php"><img src="img/agenda.png" width="800" height="505"></a></h2>
        <p>Para efetuar o agendamento basta clicar no dia desejado, como na figura abaixo:</p>
        <p><img src="img/dia.png" width="950" height="382"></p>
    </blockquote>
    <blockquote>
        <h2>Executar Experimentos</h2>
        <p>O  LabVad possiu várias funcionalidades dispostas de forma bem clara para o usuário. Na função de Executar Código o usuário deve ter atenção para direcionar seu experimento ao dispositivo correto!</p>
        <p><img src="img/exec.PNG" width="900" height="374"></p>
        <p>Além de conseguir visualizar seus experimentos em  tempo real, o LabVad possui um processo muito claro de compilação, retornado  para o usuário se o código foi compilado com sucesso ou não. As funções do menu  Executar só ficam ativadas para o usuário agendado naquele momento, mas vale  ressaltar que o ambiente é multiusuário, ou seja, você pode acompanhar os  experimentos em execução do professor e de outros colegas, bem como: criar, editar,  salvar, deletar, subir (upload) ou baixar (download) dos programas na pasta  meus códigos.</p>
        <h2><img src="img/codigos.PNG" width="334" height="368"></h2>
    </blockquote>
    <blockquote>
        <h2>&nbsp;</h2>
    </blockquote>
    <blockquote>
        <p><span class="animated"><a href="labvad.php">| Introdução</a> | <a href="led.php">Próxima Lição</a> | <a href="img/LabVad_Guia.pdf">Baixar Guia do LabVad</a> | <a href="laboratorio.php">Experimentos</a> ||</span></p>
    </blockquote>		   


<?php require_once 'app.include/footer.inc.php'; ?>
