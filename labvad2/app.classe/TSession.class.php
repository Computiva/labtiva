<?php
/**
 * Description of TSession
 *
 * @author eek
 */
final class TSession {
    
    function iniciaSessao() {
        if (! session_id() > 0) {
            session_start();
        }
    }
    
    function addSessao($chave, $valor) {
        $_SESSION[$chave] = $valor;
    }
    
    function getSessao($chave) {
        if (isset($_SESSION[$chave])) {
            return $_SESSION[$chave];
        }
        //else print_r($_SESSION);
    }
    
    function fechaSessao() {
        session_destroy();
    }
}

?>
