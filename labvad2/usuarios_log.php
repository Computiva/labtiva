<?php
$acaoLog = '';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
//$cabecalho = file('app.include/header.inc.php');
//
//$conteudo = implode('', $cabecalho);
//$conteudo = str_replace('{TITULO}', 'Laboratório', $conteudo);
//echo $conteudo;

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

$sUsuario = '';
$iId      = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); 
$acao     = filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_STRING); 
if ((is_numeric($iId)) && ($iId > 0)) {    
    //Montando grid
    try {
        $TGrid   = '';
        $sFiltro = ''; 
        if ($_SESSION['tipo'] == 'a') {
            $sFiltro = " WHERE (pessoas.id = :id) ";            
        } 
        else {
            $iId     = $_SESSION['id_usuario'];
            $sFiltro = " WHERE (pessoas.id = :id) ";
        }
        $conn      = TConnection::open();
        $sql       = $conn->prepare("SELECT
                                        log.id,
                                        log.acao,
                                        DATE_FORMAT(log.dt, '%d/%m/%Y - %h:%i') AS dt,
                                        pessoas.nome 
                                    FROM
                                        log
                                    LEFT OUTER JOIN pessoas ON (pessoas.id = log.fk_pessoa)
                                        {$sFiltro}
                                    ORDER BY
                                        log.dt ");
        $sql->bindParam(':id', $iId);
        $sql->execute();
        $meuArquivo = '';
        if (($acao == 'txt') && ($sql->rowCount() > 0)) {
            $nomeArquivo = 'download/log_' . session_id() . '.txt';
            $arquivo = fopen($nomeArquivo, 'w+');
            if (! $arquivo) {
                echo "Não foi possível criar o arquivo!";
            }
        }
        while ($resultado = $sql->fetchObject()) {
            $TGrid .= "<tr>
                            <td>{$resultado->acao}</td>
                            <td>{$resultado->dt}</td>
                        </tr>";
            $sUsuario = $resultado->nome;
            
            $meuArquivo .= "\r\n {$resultado->acao} | {$resultado->dt}";
        }
        
        if ($acao == 'txt') {
			if (empty($meuArquivo)) {
				$meuArquivo = 'Sem dados de log para exibir!';
				$nomeArquivo = 'download/log_' . session_id() . '.txt';
				$arquivo = fopen($nomeArquivo, 'w+');
				if (! $arquivo) {
					echo "Não foi possível criar o arquivo!";
				}
			}
			
            fwrite($arquivo, $meuArquivo);
            fclose($arquivo);

            download("{$nomeArquivo}");
            unlink("{$nomeArquivo}");
            exit;
        }
		
		if (empty($TGrid)) {
			echo '<h1>Sem dados de log para exibir!</h1>';
			exit;
		}
    } 
    catch (Exception $e) {
        echo $e->getMessage();
    }
    //fim Montando grid    
}
else {
    $TGrid .= "<tr>
                    <td colspan=\"2\">Sem dados para exibição!</td>
                </tr>";
}

?>

<h1>Visualização de Log do usuário: <?php echo $sUsuario; ?></h1>
<div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Ação</th>
                <th>Data</th>                
            </tr>
        </thead>
        <tbody>
            <?php echo $TGrid; ?>
        </tbody>
    </table>
</div>
