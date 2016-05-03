<?php
$url_start_recording = "http://146.164.250.39/labvad-remoteserver_0.1.7/streaming/php/recordStream.php";
$url_stop_recording  = "http://146.164.250.39/labvad-remoteserver_0.1.7/streaming/php/stopStreamRecording.php";
$url_video           = "http://146.164.250.39:27896/labvadrj03.ogg";
?>

<html>
<head>
    <meta charset="utf-8">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <style type="text/css">
        .videocontroller {
            border: 1px solid #000;
            padding: 5px;
            max-width: 600px;
            margin-left: 20px;
            margin-top: 5px;
        }
        #record_button {
            margin-left: 150px;
            border: 1px solid #000;
        }
    </style>

    <script type="text/javascript">
        var paused = true;
         
        $(document).ready(function() {
             $("#record_button").click(function() { record(); });
        });
         
        function record() {
            if (paused) {
                paused = false;
                $("#record_button").html('<span class="glyphicon glyphicon-pause"></span> Parar Gravação');
                $("#record_msg").text("Gravando.");
                $("#record_msg").css('color', '#F00');

                // chama o servidor remoto para iniciar gravação do arquivo de vídeo
                $.post("<?=$url_start_recording?>",
                    { id: "1" },        // Parâmetro com o número sequencial do vídeo no banco de dados.
                                        // Deve ser usado para compor o nome do arquivo de video que será transferido posteriormente para 
                                        // o servidor central.
                    function (retorno) {
                        alert (retorno);

                        if (retorno.erro === 0) {
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
            else {
                paused = true;
                $("#record_button").html('<span class="glyphicon glyphicon-film"></span> Iniciar Gravação');
                $("#record_msg").text("Parado.");
                $("#record_msg").css('color', '#000');

                // chama o servidor remoto para finalizar gravação do arquivo de vídeo
                $.post("<?=$url_stop_recording?>",
                    {},
                    function (retorno) {
                        alert (retorno);
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
        }  
    </script>
</head>

<body>
<video width="680" height="380" controls="" autoplay=""> <source src="<?=$url_video?>?<?=md5(uniqid(''))?>" type="video/ogg"></video>
<br/>
<div class="videocontroller">
    Gravador de Vídeo: <span id="record_msg">Parado.</span>

    <button type="button" class="btn btn-default" id="record_button">
      <span class="glyphicon glyphicon-film"></span> Iniciar Gravação
    </button>
</div>
<br/>
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

</body>
</html>

