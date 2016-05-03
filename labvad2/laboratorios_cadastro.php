<?php
$acaoLog = 'Cadastro do laboratório';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.include/header.inc.php';

//Montando grid
try {
    $TGrid       = '';
    $iId         = 0;
    $editarSenha = '';
    $txtEmail    = '';
    $txtSenha    = '';
    $txtNome     = '';
    $chbAtivo    = ' checked ';

    if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
        $iId      = $_GET['id'];
        $telaAcao = 'Edição ';

        $conn      = TConnection::open();
        $sql       = $conn->prepare("SELECT
                                        pessoas.id,
                                        pessoas.nome,
                                        pessoas.email,
                                        pessoas.dt_cadastro,
                                        pessoas.ativo,
                                        if(pessoas.tipo = 'p', 'Professor', 'Usuário') AS tipo
                                    FROM
                                        pessoas
                                    WHERE 
                                        (pessoas.id = :id)
                                    ORDER BY
                                        pessoas.nome ");
        $sql->bindParam(':id', $iId);
        $sql->execute();
        $resultado = $sql->fetchObject();

        $txtEmail = $resultado->email;
        $txtSenha = '';
        $txtNome  = $resultado->nome;
        $chbAtivo = ($resultado->ativo == 's') ? ' checked ' : '';

        $editarSenha = ' disabled';
    } 
    else {
        $iId      = 0;
        $telaAcao = 'Cadastro ';
    }
} 
catch (Exception $e) {
    
}
//fim Montando grid    


if (isset($_SESSION['msgGravacao']) && $_SESSION['msgGravacao']) {
    echo '<div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Atenção!</strong> ' . $_SESSION['msgGravacao'] . ' 
          </div>';
    $_SESSION['msgGravacao'] = FALSE;
}
?>

<h1><?php echo $telaAcao; ?> de Usuário</h1>
<div style="width: 350px">
    <form role="form" name="formCadastroUsuarios" id="formCadastroUsuarios" method="post" action="usuarios_gravar.php">
        <input type="hidden" name="id" id="id" value="<?php echo $iId; ?>">
        <div class="form-group">
            <label for="txtNome">Nome</label>
            <input type="text" class="form-control" id="txtNome" name="txtNome" maxlength="70" placeholder="Informe o seu nome" value="<?php echo $txtNome; ?>" autofocus>
        </div>
        <div class="form-group">
            <label for="txtEmail">Email</label>
            <input type="email" class="form-control" id="txtEmail" name="txtEmail" maxlength="70" placeholder="Informe o seu email"  value="<?php echo $txtEmail; ?>">
        </div>                
        <div class="form-group">
            <label for="txtSenha">Senha</label>
            <input type="password" class="form-control" id="txtSenha" name="txtSenha" maxlength="70" <?php echo $editarSenha; ?> >                  
        </div>                
        <?php
        if ($iId > 0) {
        ?>
            <div class="form-group">
                <label for="txtSenhaNova">Nova Senha</label>
                <input type="password" class="form-control" id="txtSenhaNova" name="txtSenhaNova" maxlength="70" placeholder="Informe sua nova senha" >                  
            </div>                
        <?php
        }
        ?>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="chbAtivo" id="chbAtivo" value="s" <?php echo $chbAtivo; ?>> Ativo
            </label>
        </div>
        <button type="submit" class="btn btn-primary" title="Gravar o registro de usuário">Gravar</button>
        <a href="usuarios.php" class="btn btn-default" title="Voltar">Voltar</a>
    </form>
</div>

<div class="modal fade boxAlert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"><?php echo $telaMensagem; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<?php require_once 'app.include/footer.inc.php'; ?>
