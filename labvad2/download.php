<?php
$acaoLog = 'Download do código';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.classe/util.php';

function download($fileName) {
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
    header('Content-Type: ');
//    header('Content-Transfer-Encoding: binary');
//    header('Content-Length: ' . filesize($fileName));
//    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//    header('Pragma: public');
//    header('Expires: 0');
    readfile($fileName);
}

$iId = (isset($_GET['id'])) ? $_GET['id'] : 0;
$tipo = (isset($_GET['tipo'])) ? $_GET['tipo'] : 'ino';

if ($iId > 0) {
    try {
        $conn      = TConnection::open();
        $sql       = $conn->prepare("SELECT
                                        experimentos.id,
                                        experimentos.codigo,
                                        experimentos.nome                                         
                                    FROM
                                        experimentos
                                    WHERE
                                        (experimentos.fk_pessoa = :fk_pessoa)
                                        and (experimentos.id = :id)
                                    LIMIT 1");
        $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
        $sql->bindParam(':id', $iId);
        $sql->execute();
        $resultado      = $sql->fetchObject();
        if ((isset($resultado->id)) && ($resultado->id > 0)) {
            $telaCodigo     = trim($resultado->codigo);
            $telaNomeCodigo = str_replace(' ', '_', $resultado->nome);
            $telaNomeCodigo = str_replace('-', '_', $telaNomeCodigo);
            $telaNomeCodigo = removeAcentos($telaNomeCodigo);

            //Criando o arquivo novamente
            $arquivo = fopen("download/{$telaNomeCodigo}.{$tipo}", 'a');
            fwrite($arquivo, $telaCodigo);
            fclose($arquivo);

            download("download/{$telaNomeCodigo}.{$tipo}");
            unlink("download/{$telaNomeCodigo}.{$tipo}");
        }
        else {
            echo "<script>window.close();</script>";
        }
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
}
else {
    echo "Codigo nao selecionado para download!";
}
