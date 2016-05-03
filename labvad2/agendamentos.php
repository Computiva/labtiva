<?php
$acaoLog = 'Agendamentos';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.classe/TData.class.php';
require_once 'app.include/header.inc.php';

$dataSelecionada = Date('Y-m-d');
if (isset($_SESSION['dataSelecionadaAgenda'])) {
    $dataSelecionada = $_SESSION['dataSelecionadaAgenda'];
    $_SESSION['dataSelecionadaAgenda'] = Date('Y-m-d');
}

// recebe laboratório da querystring
if (isset($_GET['lab'])) {
        $id_laboratorio = $_GET['lab'];
} 
else {
        echo "Laboratório não especificado.";
        require_once 'app.include/footer.inc.php';
        exit;
}

//Obtendo o nome do laboratório
$sql = $conn->prepare("SELECT nome FROM laboratorios
                       WHERE laboratorios.id = :id_laboratorio");
$sql->bindParam(':id_laboratorio', $id_laboratorio);
$sql->execute();
$resultado = $sql->fetchObject();
$nome_laboratorio = $resultado->nome;
?>

<h1>Agendamento: <?= $_SESSION['nome_tipo'] ?> - <?= $nome_laboratorio ?></h1>

<div class="well">Clique em um dia no calendário abaixo para agendar um horário.</div>

<div class="row">
    <div id="calendar"></div>
</div>

<script>
    $(document).ready(function () {

        var currentLangCode = 'pt-br';

        function dataAtualFormatada() {
            var data = new Date();
            var dia = data.getDate();
            if (dia.toString().length == 1)
                dia = "0" + dia;
            var mes = data.getMonth() + 1;
            if (mes.toString().length == 1)
                mes = "0" + mes;
            var ano = data.getFullYear();
            return ano + "-" + mes + "-" + dia;
        }

        $('#calendar').fullCalendar({
            header: {
                left: '',
                center: 'prev,title,next',
                right: '' //'month' //,agendaWeek,agendaDay
            },
            defaultDate: '<?php echo $dataSelecionada; ?>',
            selectable: true,
            selectHelper: true,
            lang: 'pt-br',
            dayClick: function (date, jsEvent, view) {
                selecionada = date.format();
                atual = dataAtualFormatada();
                if ((selecionada == atual) || (selecionada > atual))
                    document.location.href = "agendamentos_dia.php?data=" + date.format() + "&lab=<?= $id_laboratorio ?>";
            },
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: []
        });
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
    .fc-center { text-transform: capitalize; width: auto  }
    .fc-prev-button, .fc-center h2 { float: left }
    .fc-center h2 { margin: auto 30px }
    .fc-next-button { float: right; }
</style>

<?php require_once 'app.include/footer.inc.php'; ?>
