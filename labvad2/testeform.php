<h1>Cadastro  de Usuário</h1>
<div style="width: 350px">
    <form role="form" name="formCadastroUsuarios" id="formCadastroUsuarios" method="post" action="usuarios_gravar.php">
        <input type="hidden" name="id" id="id" value="0">
        <div class="form-group">
            <label for="txtNome">Nome</label>
            <input type="text" class="form-control" id="txtNome" name="txtNome" maxlength="70" placeholder="Informe o seu nome" value="" autofocus>
        </div>
        <div class="form-group">
            <label for="txtEmail">Email</label>
            <input type="email" class="form-control" id="txtEmail" name="txtEmail" maxlength="70" placeholder="Informe o seu email"  value="">
        </div>                
        <div class="form-group">
            <label for="txtEmail">Instituição</label>
            <input type="text" class="form-control" id="txtInstituicao" name="txtInstituicao" maxlength="70" placeholder="Informe a sua instituição"  value="">
        </div>  
                    <!-- cadastrar -->
            <div class="form-group">
                <label for="txtSenhaNova">Senha</label>
                <input type="password" class="form-control" id="txtSenhaNova" name="txtSenhaNova" maxlength="70" placeholder="Informe sua senha" >                  
            </div>                
            <div class="form-group">
                <label for="txtSenha">Confirmar Senha</label>
                <input type="password" class="form-control" id="txtSenhaConf" name="txtSenhaConf" maxlength="70" placeholder="Repita sua senha" >                  
            </div>  

        <button type="submit" class="btn btn-primary" title="Gravar o registro de usuário">Gravar</button>
        <a href="usuarios.php" class="btn btn-default" title="Voltar">Voltar</a>
    </form>
</div>
