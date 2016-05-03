<?php
try {
    $acaoLog = 'Exclusão do usuário';
    require_once 'app.ado/TConnection.class.php';    
    require_once 'app.include/verificasessao.inc.php';
    $retorno = 'no';
    $iId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($iId > 0) {
        $conn = TConnection::open();
        $sql  = $conn->prepare("DELETE FROM pessoas WHERE id = :id LIMIT 1 ");
        $sql->bindParam("id", $iId);
        $sql->execute();
        if ($sql->rowCount()) {
            $retorno = 'ok';
        }
    }
    
    header("Location: usuarios.php?acao={$retorno}");
    exit;
}
catch(Exception $e) {   
    echo $e->getMessage();
}
