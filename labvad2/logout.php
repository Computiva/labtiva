<?php
    session_start();
    $_SESSION['id_usuario'] = '';
    $_SESSION['nome_usuario'] = '';
    $_SESSION['tipo'] = '';
    session_destroy();
    header('Location: index.php?acao=logout');
    exit;
?>