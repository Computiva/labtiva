<?php

function gravaLog($acaoLog) {
   global $conn;
   if ((isset($acaoLog)) && (! empty($acaoLog))) {
        try {
            $sql  = $conn->prepare("INSERT INTO log (fk_pessoa, fk_lab_tipo, acao, dt, sessao) VALUES (:fk_pessoa, :fk_lab_tipo, :acao, NOW(), :sessao) ");
            $sql->bindParam(":fk_pessoa", $_SESSION['id_usuario']);
            $sql->bindParam(":fk_lab_tipo", $_SESSION['id_tipo']);
            $sql->bindParam(":acao", $acaoLog);
            $sql->bindParam(":sessao", $_SESSION['id_sessao']);
            $sql->execute();
        }
        catch (Exception $e) {

        }
    } 
}

if (! isset($_SESSION)) {
    session_start();
}


if ((isset($_SESSION['id_usuario'])) && (isset($_SESSION['nome_usuario'])) && (isset($_SESSION['tipo']))) {
    // gravaLog($acaoLog);
}
else {
    header("Location: index.php?no");
    exit;
}

