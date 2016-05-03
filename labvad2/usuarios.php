<?php
$acaoLog = 'Controle de usuário';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.include/header.inc.php';

$acao = filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_STRING); 
if ($acao == 'ok') {
    echo '<div class="alert alert-danger">Exclusão realizada com sucesso!</div>';
}

//Montando grid
try {
    $TGrid   = '';
    $sFiltro = '';
    if ($_SESSION['tipo'] == 'a') {
        $sFiltro = "";          //  WHERE ((pessoas.id = " . $_SESSION['id_usuario'] . ") OR (pessoas.tipo <> 'a')) ";
    } else {
        $sFiltro = " WHERE (pessoas.id = " . $_SESSION['id_usuario'] . ") ";
    }
    $conn      = TConnection::open();
    $sql       = $conn->prepare("SELECT
                                    pessoas.id,
                                    pessoas.nome,
                                    pessoas.email,
                                    pessoas.dt_cadastro,
                                    if(pessoas.ativo = 's', '<b>Sim</b>', '<i>Não</i>') AS ativo,
                                    if(pessoas.tipo = 'a', '<b>Administrador</b>', 'Usuário') AS tipo
                                FROM
                                    pessoas   
                                    {$sFiltro}
                                ORDER BY
                                    pessoas.nome ");
    $sql->execute();
    while ($resultado = $sql->fetchObject()) {
        $TGrid .= "<tr>
                        <td>{$resultado->id}</td>
                        <td>{$resultado->nome}</td>
                        <td>{$resultado->email}</td>
                        <td>{$resultado->tipo}</td>
                        <td>{$resultado->ativo}</td>
                        <td><a href=\"usuarios_cadastro.php?id={$resultado->id}\" title=\"Editar\">Editar</a></td>
                        <td><a href=\"usuarios_log.php?id={$resultado->id}\" class=\"lkLog\" title=\"Editar\">Visualizar log</a></td>";                      
        if ($resultado->id != $_SESSION['id_usuario']) {
            $TGrid .= "<td><a href=\"usuarios_excluir.php?id={$resultado->id}\" title=\"Excluir\">Excluir</a></td></tr>";
        }
        else {
            $TGrid .= "<td>&nbsp;</td></tr>";
        }
    }
} 
catch (Exception $e) {
    
}
//fim Montando grid    
?>

<h1>Configurações</h1>
<?php if ($_SESSION['tipo'] == 'a') { ?>
<p class="texto-direita"><a class="btn btn-primary" href="usuarios_cadastro.php" title="Cadastrar novo usuário">Cadastrar</a></p>
<?php } ?>
<div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ativo</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $TGrid; ?>
        </tbody>
    </table>

</div>
<?php if ($_SESSION['tipo'] == 'a') { ?>
<p class="texto-direita"><a class="btn btn-primary" href="usuarios_cadastro.php" title="Cadastrar novo usuário">Cadastrar</a></p>
<?php } ?>
<div class="modal fade boxEsp">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Preparando o ambiente de Experimentos...</p>
            </div>    
        </div>
    </div>
</div>	

<div class="modal fade boxLog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body" id="conteudoLog">
        
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btDwonloadLog" data-dismiss="modal">Download log</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    $(document).ready(function() {
        var lkAcesso = '';
        $('a.lkLog').on('click', function() {  
            var idLog = $(this).attr('href');
            lkAcesso = idLog;
            $("#conteudoLog").load(idLog);
            $(".boxLog").modal('show');
            return false;
        });  
        
        $('#btDwonloadLog').on('click', function() {
            document.location.href = lkAcesso + '&acao=txt';
        });
    });    
</script>

<?php require_once 'app.include/footer.inc.php'; ?>
