<?php
try {
    $acaoLog = 'Gravação de usuário';
    require_once 'app.ado/TConnection.class.php';      
    require_once 'app.include/verificasessao.inc.php';
    
    $iId            = $_POST['id'];
    $txtNome        = $_POST['txtNome'];
    $txtEmail       = $_POST['txtEmail'];
    $txtInstituicao = $_POST['txtInstituicao'];
    $txtSenha       = empty($_POST['txtSenha']) ? '' : $_POST['txtSenha'];
    $txtSenhaNova   = empty($_POST['txtSenhaNova']) ? '' : $_POST['txtSenhaNova'];
    $chbAtivo       = empty($_POST['chbAtivo']) ? 'n' : $_POST['chbAtivo'];

    if ((is_numeric($iId)) && ($iId == 0)) {
        $sql = $conn->prepare("
                    INSERT INTO pessoas (
                        id, 
                        nome,
                        email,
                        escola,
                        dt_cadastro,
                        ativo,
                        tipo,
                        senha
                    )
                    VALUES (
                        NULL, 
                        :nome,
                        :email,
                        :escola,
                        NOW(),
                        :ativo,
                        'u',
                        MD5(:senha)
                    ) ");
        $sql->bindParam(':nome', $txtNome);
        $sql->bindParam(':email', $txtEmail);
        $sql->bindParam(':escola', $txtInstituicao);
        $sql->bindParam(':ativo', $chbAtivo);
        $sql->bindParam(':senha', $txtSenha);
        $sql->execute();
        $iId = $conn->lastInsertId();
        $_SESSION['msgGravacao'] = "Usuário cadastro com sucesso!";
    }
    else if ((is_numeric($iId)) && ($iId > 0)) {
        $sSenha = ($txtSenhaNova != '') ? " senha = MD5('{$txtSenhaNova}'), " : '';
        
        $sql = $conn->prepare("
                    UPDATE pessoas set 
                        nome = :nome,
                        email = :email,
                        escola = :escola,
                        {$sSenha}
                        ativo = :ativo
                    WHERE
                        (id = :id) 
                    LIMIT 1 ");
        $sql->bindParam(':id', $iId);
        $sql->bindParam(':nome', $txtNome);
        $sql->bindParam(':email', $txtEmail);
        $sql->bindParam(':escola', $txtInstituicao);
        $sql->bindParam(':ativo', $chbAtivo);
        $sql->execute();
        $_SESSION['msgGravacao'] = "Usuário atualizado com sucesso!";
    }
    
    header("Location: usuarios_cadastro.php?id={$iId}");
    exit;

}
catch (Exception $e) {
    
    //echo $e->getMessage();
    if ((isset($iId)) && ($iId > 0)) {
        $_SESSION['msgGravacao'] = "Erro ao atualizar o cadastro!".$e->getMessage();
        header("Location: usuarios_cadastro.php?id={$iId}");
    }
    else {
        $_SESSION['msgGravacao'] = "Erro ao inserir o usuário!";
        if (strpos($e->getMessage(), 'UNIQUE') > 0) {
            $_SESSION['msgGravacao'] .= ' Esse email já esta em uso no LabVad.';
        }
        header("Location: usuarios_cadastro.php");
    }
    
    exit;
}

