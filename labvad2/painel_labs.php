<?php

$acaoLog = '';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.classe/TData.class.php';

require_once 'app.include/header.inc.php';
?>

<h1>Painel de laboratórios</h1>

<div class="row">
    <div class="col-md-12">
    <?php
        $sql = $conn->prepare("SELECT L.id, L.url_video, L.nome, L.placa_arduino, L.fk_lab_tipo AS lab_tipo, T.nome_tipo
                                FROM laboratorios L
                                JOIN laboratorios_tipo T
                                ON L.fk_lab_tipo = T.id
                                WHERE (L.estado = 'A')");
        $sql->execute();
        while ($resultado = $sql->fetchObject()) {
            $nome = strtoupper($resultado->nome);
            echo '<div class="col-md-6 .col-xs-6 panel">';
            if ($resultado->lab_tipo == 1) {
                echo "{$nome} - {$resultado->nome_tipo} (placa {$resultado->placa_arduino})";
            }
            else {
                echo "{$nome} - {$resultado->nome_tipo}";
            }
            echo '<br/>';
            // acrescenta parâmetro aleatório na url do stream para evitar cache do browser
            echo '<video width="95%" controls="" autoplay=""> <source src="' . $resultado->url_video.'?'.md5(uniqid('')) . '" type="video/ogg"></video>';
            echo '</div>';
        }
    ?>
    </div>
</div>

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

<?php require_once 'app.include/footer.inc.php'; ?>


