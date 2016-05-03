<?php

require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';

if (isset($_GET['tipo'])) {
        $str_param = $_GET['tipo'];
        //echo $str_param;
        $params = explode ( ',', $str_param, 2 );
        //echo '<pre>'; print_r($params); echo '</pre>';

        $_SESSION['id_tipo'] = $params[0];
        $_SESSION['nome_tipo'] = $params[1];
}

header("Location: labvad.php?ok=1");

?>
