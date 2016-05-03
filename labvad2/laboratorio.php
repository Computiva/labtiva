<?php
$LIMITE_EXEMPLOS = 30;  # indice máximo da tabela 'expermentos' que contem códigos-exemplo

$acaoLog = '';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.classe/TData.class.php';

function getFormEditar($telaId, $telaNomeCodigo, $telaCodigo, $acao) {
    $desabilita = ($acao == 'editar') ? ' disabled ' : '';
    return '<form id="formCodigo" name="formCodigo" method="post" action="laboratorio.php?acao=gravar">
                <input type="hidden" name="idCodigo" id="idCodigo" value="' . $telaId . '">
                <input type="text" maxlength="70" id="txtNomeCodigo" ' . $desabilita . ' name="txtNomeCodigo" value="' . $telaNomeCodigo . '" 
                    placeholder="Informe o nome do seu programa">
                <textarea id="txtCodigo" name="txtCodigo" class="abc" placeholder="Codifique aqui...">' . $telaCodigo . '</textarea>
            </form>';
}


// Se tipo não é Programação arduino, manda para o laboratório de ciências
if ($_SESSION['id_tipo']!='1'){
    header("Location: labciencias.php");
    exit;
}

// Verifica se o usuário tem laboratório agendado nesse instante e pega as urls do lab e do vídeo
$permissaoExecutar = horario_agendado($_SESSION['id_usuario'], $_SESSION['id_tipo'], $url_lab, $url_video, $lab_id, $lab_nome, $incluir_multiplexacao, $hora_inicio, $hora_fim);

$ip_lab = "";
if ($permissaoExecutar) {
    $parts =  explode("/", $url_lab);
    $ip_lab =  $parts[0]."/".$parts[1]."/".$parts[2]; // http://xxx.xxx.xx.xx
}



// ========== Salva o código em arquivo ==========

if ((isset($_GET['acao'])) && ($_GET['acao'] == 'gravar')) {
    try {
        $iIdCodigo     = isset($_POST['idCodigo']) ? $_POST['idCodigo'] : 0;
        $iIdCodigo     = empty($iIdCodigo) ? 0 : $iIdCodigo;
        $txtNomeCodigo = isset($_POST['txtNomeCodigo']) ? $_POST['txtNomeCodigo'] : '';
        $txtCodigo     = isset($_POST['txtCodigo']) ? $_POST['txtCodigo'] : '';
        $tipo_arquivo  = isset($_GET['tipo']) ? $_GET['tipo'] : 'ino';

        if ((is_numeric($iIdCodigo)) && ($iIdCodigo == 0)) {

            //verificando se existe algum código na conta do usuário com o mesmo nome e tipo;
            $sql = $conn->prepare("SELECT COUNT(id) AS total FROM experimentos 
                                   WHERE ((fk_pessoa = :fk_pessoa) AND (nome = :nome) AND (fk_lab_tipo = :fk_lab_tipo)) ");
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->bindParam(':nome', $txtNomeCodigo);
            $sql->bindParam(':fk_lab_tipo', $_SESSION['id_tipo']);
            $sql->execute();
            $resultado = $sql->fetchColumn();

            if ($resultado > 0) {
                $rJson   = array("id" => $iIdCodigo,
                                 "msg" => "Seu programa não pode ser gravado. Você já possui um arquivo com este nome ({$txtNomeCodigo})!",
                                 "erro" => 1);
                echo json_encode($rJson);
                exit;
            }

            $sql = $conn->prepare("INSERT INTO experimentos (
                                        nome,
                                        codigo,
                                        dt_envio,
                                        publico,
                                        tipo_arquivo,
                                        fk_pessoa,
                                        fk_lab_tipo
                                    )
                                    VALUES (
                                        :nome,
                                        :codigo,
                                        NOW(),
                                        'N',
                                        :tipo_arquivo,
                                        :fk_pessoa,
                                        :fk_lab_tipo
                                    ) ");
            $sql->bindParam(':nome', $txtNomeCodigo);
            $sql->bindParam(':codigo', $txtCodigo);
            $sql->bindParam(':tipo_arquivo', $tipo_arquivo);
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->bindParam(':fk_lab_tipo', $_SESSION['id_tipo']);
            $sql->execute();
            $iIdCodigo = $conn->lastInsertId();
            $rJson     = array('id' => $iIdCodigo,
                                'nome' => $txtNomeCodigo,
                                'msg' => 'Seu programa foi gravado com sucesso!',
                                'erro' => 0);
            echo json_encode($rJson);
            exit;
        } 
        else if ((is_numeric($iIdCodigo)) && ($iIdCodigo > 0)) {

            //Verificando se o nome já esta me uso
            $sql       = $conn->prepare("SELECT COUNT(id) AS total FROM experimentos 
                                            WHERE (fk_pessoa = :fk_pessoa) AND (nome = :nome) AND (fk_lab_tipo = :fk_lab_tipo) AND (id <> :id) ");
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->bindParam(':nome', $txtNomeCodigo);
            $sql->bindParam(':fk_lab_tipo', $_SESSION['id_tipo']);
            $sql->bindParam(':id', $iIdCodigo);
            $sql->execute();
            $resultado = $sql->fetchColumn();

            if ($resultado > 0) {
                $rJson   = array("id" => $iIdCodigo,
                                 "msg" => "Seu programa não pode ser gravado. Você já possui um arquivo com este nome ({$txtNomeCodigo})!",
                                 "erro" => 1);
                echo json_encode($rJson);
                exit;
            }

            if ($iIdCodigo <= $LIMITE_EXEMPLOS){
                $rJson   = array("id" => $iIdCodigo,
                                 "msg" => "Não é possível salvar um programa exemplo. Utilize a opção 'salvar como'!",
                                 "erro" => 2);
                echo json_encode($rJson);
                exit;

            }

            $sql   = $conn->prepare("UPDATE experimentos SET
                                        nome = :nome,
                                        codigo = :codigo
                                     WHERE
                                        (fk_pessoa = :fk_pessoa)
                                        AND (id = :id)
                                        AND (id NOT BETWEEN 1 AND $LIMITE_EXEMPLOS) 
                                     LIMIT 1 ");
            $sql->bindParam(':id', $iIdCodigo);
            $sql->bindParam(':nome', $txtNomeCodigo);
            $sql->bindParam(':codigo', $txtCodigo);   // trim($txtCodigo) ????????
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->execute();
            gravaLog('Gravando a alteração no experimento ' . $txtNomeCodigo);

            $rJson = array("id" => $iIdCodigo,
                "nome" => $txtNomeCodigo,
                "msg" => 'Seu programa foi gravado com sucesso!',
                "erro" => 0);
            echo json_encode($rJson);
            exit;
            
        } 
        else {
            $rJson = array("id" => $iIdCodigo,
                "msg" => 'Dados não informados corretamente!',
                "erro" => 1);
            echo json_encode($rJson);
            exit;
        }
    
    } 
    catch (Exception $ex) {
        $rJson = array("id" => 0,
                       "msg" => $ex->getMessage(),
                       "erro" => 2);
        echo json_encode($rJson);
        exit;
    }
    
}

// ========== Executa o codigo ==========

else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'executar')) {

    try {
        $iIdCodigo      = isset($_POST['idCodigo']) ? $_POST['idCodigo'] : 0;
        $txtNomeCodigo  = isset($_POST['txtNomeCodigo']) ? $_POST['txtNomeCodigo'] : '';
        $txtCodigo      = isset($_POST['txtCodigo']) ? $_POST['txtCodigo'] : '';
        $tipoCodigo     = isset($_GET['tipo']) ? $_GET['tipo'] : '';
        $telaCodigo     = trim($txtCodigo);
        $telaId         = $iIdCodigo;
        $telaNomeCodigo = $txtNomeCodigo;
        $r              = '';

        if ($telaCodigo != '') {

            gravaLog('Executando o experimento '.$tipoCodigo.' no '.$lab_nome);
            if ($incluir_multiplexacao == 'S') {
                switch ($tipoCodigo) {
                    case 'leds':
                        $base     = 'void setup()';
                        $posicao1 = stripos($telaCodigo, $base);
                        $blocoA   = substr($telaCodigo, 0, $posicao1);

                        $posicao2 = stripos($telaCodigo, '{', $posicao1);
                        $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                        $blocoC   = substr($telaCodigo, $posicao2 + 1);

                        $declaracaoParaBlocoA = "// **** inicio Decodificador do MUX ****\n" .
                                "int  PIN_MUX_0 = 2; \n" .
                                "int PIN_MUX_1 = 4; \n" .
                                "// **** fim Decodificador do MUX ****\n";
                        $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                        $declaracaoParaBlocoB = "// **** Habilita MUX - 01 - Conjunto de LEDs - LED0 a LED7 ****\n" .
                                "pinMode(LED_VM1, OUTPUT); \n" .
                                "pinMode(LED_AM1, OUTPUT); \n" .
                                "pinMode(LED_VD1, OUTPUT); \n" .
                                "pinMode(LED_AZ1, OUTPUT); \n" .
                                "pinMode(LED_VM2, OUTPUT); \n" .
                                "pinMode(LED_AM2, OUTPUT); \n" .
                                "pinMode(LED_VD2, OUTPUT); \n" .
                                "pinMode(LED_AZ2, OUTPUT); \n" .
                                "// **** Decodificador do MUX. ****\n" .
                                "pinMode(PIN_MUX_0,OUTPUT); // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                                "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                                "digitalWrite(LED_VM1, LOW); \n" .
                                "digitalWrite(LED_AM1, LOW); \n" .
                                "digitalWrite(LED_VD1, LOW); \n" .
                                "digitalWrite(LED_AZ1, LOW); \n" .
                                "digitalWrite(LED_VM2, LOW); \n" .
                                "digitalWrite(LED_AM2, LOW); \n" .
                                "digitalWrite(LED_VD2, LOW); \n" .
                                "digitalWrite(LED_AZ2, LOW); \n" .
                                "digitalWrite(PIN_MUX_0, HIGH);   // MUX_0 \n" .
                                "digitalWrite(PIN_MUX_1, HIGH);   // MUX_1 \n";
                        $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                        $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                        break;


                    case 'display_caracteres':
                        $cabecalho = "#include \"Arduino.h\"  \n" .
                                "extern \"C\" void __cxa_pure_virtual() \n" .
                                "{  \n" .
                                " cli(); \n " .
                                " for (;;);  \n" .
                                "}  \n ";

                        $base      = 'void setup()';
                        $posicao1  = stripos($telaCodigo, $base);
                        $blocoA    = substr($telaCodigo, 0, $posicao1);

                        $posicao2 = stripos($telaCodigo, '{', $posicao1);
                        $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                        $blocoC   = substr($telaCodigo, $posicao2 + 1);

                        $declaracaoParaBlocoA = "// **** inicio Decodificador do MUX ****\n" .
                                "int PIN_MUX_0 = 2; \n" .
                                "int PIN_MUX_1 = 4; \n" .
                                "// **** fim Decodificador do MUX ****\n";
                        $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                        $declaracaoParaBlocoB = "// **** Decodificador do MUX. **** \n" .
                                "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                                "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                                "// **** Habilita MUX - 00 - Display de Caracteres ****\n" .
                                "digitalWrite(PIN_MUX_0, LOW);   // MUX_0\n" .
                                "digitalWrite(PIN_MUX_1, LOW);   // MUX_1\n";
                        $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                        $codigoMontado = $cabecalho . $blocoA_alterado . $blocoB_alterado . $blocoC;
                        break;

                    case 'display_7':
                        $base     = 'void setup()';
                        $posicao1 = stripos($telaCodigo, $base);
                        $blocoA   = substr($telaCodigo, 0, $posicao1);

                        $posicao2 = stripos($telaCodigo, '{', $posicao1);
                        $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                        $blocoC   = substr($telaCodigo, $posicao2 + 1);

                        $declaracaoParaBlocoA = "// **** inicio Decodificador do MUX ****\n" .
                                "int PIN_MUX_0 = 2; \n" .
                                "int PIN_MUX_1 = 4; \n" .
                                "// **** fim Decodificador do MUX ****\n";
                        $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                        $declaracaoParaBlocoB = "// **** Decodificador do MUX. ****\n" .
                                "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                                "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                                "// **** Habilita MUX 7 Segmentos ****\n" .
                                "digitalWrite(PIN_MUX_0, HIGH); // MUX_0 \n" .
                                "digitalWrite(PIN_MUX_1, LOW);  // MUX_1 \n";
                        $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                        $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                        break;

                    case 'ledrgb':
                        $base     = 'void setup()';
                        $posicao1 = stripos($telaCodigo, $base);
                        $blocoA   = substr($telaCodigo, 0, $posicao1);

                        $posicao2 = stripos($telaCodigo, '{', $posicao1);
                        $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                        $blocoC   = substr($telaCodigo, $posicao2 + 1);

                        $declaracaoParaBlocoA = "// **** inicio Decodificador do MUX ****\n" .
                                "int PIN_MUX_0 = 2; \n" .
                                "int PIN_MUX_1 = 4; \n" .
                                "// **** fim Decodificador do MUX ****\n";
                        $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                        $declaracaoParaBlocoB = "// **** Decodificador do MUX. ****\n" .
                                "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                                "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                                "//Habilita MUX LED RGB. Servo e Relé \n" .
                                "digitalWrite(PIN_MUX_0, LOW);   // MUX_0\n" .
                                "digitalWrite(PIN_MUX_1, HIGH);  // MUX_1\n" .
                                "// **** Reset das saidas NAO utilizadas neste experimento ****\n" .
                                "pinMode(MOTOR_DC, OUTPUT); \n" . 
                                "pinMode(SERVO,    OUTPUT); \n" . 
                                "pinMode(RELE,     OUTPUT); \n" .  
                                "pinMode(BUZZER,   OUTPUT); \n";  
                        $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                        $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                        break;

                    case 'servo':
                        $base     = 'void setup()';
                        $posicao1 = stripos($telaCodigo, $base);
                        $blocoA   = substr($telaCodigo, 0, $posicao1);

                        $posicao2 = stripos($telaCodigo, '{', $posicao1);
                        $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                        $blocoC   = substr($telaCodigo, $posicao2 + 1);

                        $declaracaoParaBlocoA = "// **** inicio Decodificador do MUX ****\n" .
                                "int PIN_MUX_0 = 2; \n" .
                                "int PIN_MUX_1 = 4; \n" .
                                "// **** fim Decodificador do MUX ****\n";
                        $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                        $declaracaoParaBlocoB = "// **** Decodificador do MUX. ****\n" .
                                "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                                "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                                "// **** Habilita MUX LED RGB. Servo e Relé ****\n" .
                                "digitalWrite(PIN_MUX_0, LOW);   // MUX_0\n" .
                                "digitalWrite(PIN_MUX_1, HIGH);  // MUX_1\n" .
                                "// **** Reset das saidas NAO utilizadas neste experimento ****\n" .
                                "pinMode(MOTOR_DC, OUTPUT); \n" . 
                                "pinMode(RELE,     OUTPUT); \n" .  
                                "pinMode(RGB_VM,   OUTPUT); \n" . 
                                "pinMode(RGB_VD,   OUTPUT); \n" . 
                                "pinMode(RGB_AZ,   OUTPUT); \n" . 
                                "pinMode(BUZZER,   OUTPUT); \n";  
                        $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                        $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                        break;

                    case 'motordc':
                        $base     = 'void setup()';
                        $posicao1 = stripos($telaCodigo, $base);
                        $blocoA   = substr($telaCodigo, 0, $posicao1);

                        $posicao2 = stripos($telaCodigo, '{', $posicao1);
                        $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                        $blocoC   = substr($telaCodigo, $posicao2 + 1);

                        $declaracaoParaBlocoA = "// **** inicio Decodificador do MUX ****\n" .
                                "int PIN_MUX_0 = 2; \n" .
                                "int PIN_MUX_1 = 4; \n" .
                                "// **** fim Decodificador do MUX ****\n";
                        $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                        $declaracaoParaBlocoB = "// **** Decodificador do MUX. ****\n" .
                                "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                                "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                                "//Habilita MUX LED RGB. Servo e Relé \n" .
                                "digitalWrite(PIN_MUX_0, LOW);   // MUX_0\n" .
                                "digitalWrite(PIN_MUX_1, HIGH);  // MUX_1\n" .
                                "// **** Reset das saidas NAO utilizadas neste experimento ****\n" .
                                "pinMode(SERVO,  OUTPUT); \n" . 
                                "pinMode(RELE,   OUTPUT); \n" .  
                                "pinMode(RGB_VM, OUTPUT); \n" . 
                                "pinMode(RGB_VD, OUTPUT); \n" . 
                                "pinMode(RGB_AZ, OUTPUT); \n" . 
                                "pinMode(BUZZER, OUTPUT); \n";  
                        $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                        $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                        break;

                    case 'rele':
                        $base     = 'void setup()';
                        $posicao1 = stripos($telaCodigo, $base);
                        $blocoA   = substr($telaCodigo, 0, $posicao1);

                        $posicao2 = stripos($telaCodigo, '{', $posicao1);
                        $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                        $blocoC   = substr($telaCodigo, $posicao2 + 1);

                        $declaracaoParaBlocoA = "// **** inicio Decodificador do MUX ****\n" .
                                "int PIN_MUX_0 = 2; \n" .
                                "int PIN_MUX_1 = 4; \n" .
                                "// **** fim Decodificador do MUX ****\n";
                        $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                        $declaracaoParaBlocoB = "// **** Decodificador do MUX. ****\n" .
                                "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                                "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                                "// **** Habilita MUX LED RGB. Servo e Relé ****\n" .
                                "digitalWrite(PIN_MUX_0, LOW);   // MUX_0\n" .
                                "digitalWrite(PIN_MUX_1, HIGH);  // MUX_1\n" .
                                "// **** Reset das saidas NAO utilizadas neste experimento ****\n" .
                                "pinMode(MOTOR_DC, OUTPUT); \n" . 
                                "pinMode(SERVO,    OUTPUT); \n" . 
                                "pinMode(RGB_VM,   OUTPUT); \n" . 
                                "pinMode(RGB_VD,   OUTPUT); \n" . 
                                "pinMode(RGB_AZ,   OUTPUT); \n" . 
                                "pinMode(BUZZER,   OUTPUT); \n";  
                        $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                        $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                        break;

                    default:
                        $base     = 'void setup()';
                        $posicao1 = stripos($telaCodigo, $base);
                        $blocoA   = substr($telaCodigo, 0, $posicao1);

                        $posicao2 = stripos($telaCodigo, '{', $posicao1);
                        $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                        $blocoC   = substr($telaCodigo, $posicao2 + 1);

                        $declaracaoParaBlocoA = "// **** inicio Decodificador do MUX ****\n" .
                                "int PIN_MUX_0 = 2; \n" .
                                "int PIN_MUX_1 = 4; \n" .
                                "// **** fim Decodificador do MUX ****\n";
                        $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                        $declaracaoParaBlocoB = "// **** Decodificador do MUX. ****\n" .
                                "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                                "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                                "// **** Habilita MUX ****\n" .
                                "digitalWrite(PIN_MUX_0, LOW);   // MUX_0 \n" .
                                "digitalWrite(PIN_MUX_1, HIGH);  // MUX_1\n" ;
                        $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                        $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                        break;
                }
            }
            else {
                $codigoMontado = $telaCodigo;
            }



            // =========================================================================================
            // faz uma requisição assíncrona (POST) para o servidor do laboratório reservado 
            // enviando o código montado.
            // o servidor então executa o código na placa arduíno, retornando Json com resultado da execução.

            $arquivo = fopen('debug.txt', 'w+');

            //$filename = md5(uniqid(""));
            //$filename = md5(uniqid(rand(), true));

            $data = array(
                        'codigo' => urlencode($codigoMontado), 
                        'verbose' => 'T'
            );
            fwrite($arquivo, date('Y-m-d H:i:s').": enviando: ".json_encode($data)."\r\n\r\n");

            // use key 'http' even if you send the request to https://...
            $options = array(
	            'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
	            'content' => http_build_query($data),
	            ),
            );
            $context  = stream_context_create($options);
    
            // @ in front of the file_get_contents to supress the warning "failed to open stream"
            $r = @file_get_contents($url_lab, false, $context);
            fwrite($arquivo, date('Y-m-d H:i:s').": recebendo: ".$r."\r\n\r\n");

            if ($r===FALSE) {
                $rJson = array(
                               "msg" => 'O servidor não responde ('.$url_lab.'). Provavelmente o laboratório está fora do ar',
                               "debug" => '',
                               "erro" => 5);
            }
            else {
                $rJson = json_decode($r, true);
            }

            fwrite($arquivo, "json: ".json_encode($rJson)."\r\n\r\n");
            fclose($arquivo);


            // =========================================================================================

        } 
        else {
            $rJson = array(
                            "msg" => 'Não existe código para ser compilado!',
                            "debug" => $r,
                            "erro" => 1);
        }
    } 
    catch (Exception $e) {
        $rJson = array(
                        "msg" => $ex->getMessage(),
                        "debug" => $r,
                        "erro" => 2);
    }

    echo json_encode($rJson);
    exit;
}

// ========== Retorna a listagem dos arquivos (chamado por ajax) ==========

else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'listagem')) {
    try {
        $tipo_arquivo = (isset($_GET['tipo']) ? $_GET['tipo'] : 'ino');
        $sql = $conn->prepare("SELECT
                                    experimentos.id,
                                    experimentos.nome,
                                    experimentos.codigo
                                FROM
                                    experimentos
                                WHERE
                                    (experimentos.fk_pessoa = :fk_pessoa) 
                                AND (tipo_arquivo = :tipo_arquivo)
                                ORDER BY
                                    experimentos.nome ");
        $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
        $sql->bindParam(':tipo_arquivo', $tipo_arquivo);
        $sql->execute();
        $TGrid     = '';
        while ($resultado = $sql->fetchObject()) {
            $TGrid .= "<li class=\"list-group-item\">"
                    . "<a href=\"laboratorio.php?id={$resultado->id}&acao=editar\" class=\"lkDiretoCodigo itemCodigo\">{$resultado->nome}</a>"
                    . "<span class=\"lkExclusaoCodigo\"><a href=\"laboratorio.php?acao=exclusao&id={$resultado->id}\" title=\"Excluír o código {$resultado->nome}\">&nbsp;</a></span>"
                    . "</li>";                  
        }
    } 
    catch (Exception $e) {
        echo $e->getMessage();
    }

    echo $TGrid;
    exit;
} 


// ========== Remove um arquivo ==========

else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'exclusao')) {
    $iId = isset($_POST['idCodigo']) ? $_POST['idCodigo'] : 0;
    if ($iId == 0) {
        $telaRetorno = 'Erro ao receber o código do arquivo a ser excluído.';
    }
    else {
        try {
            $sql = $conn->prepare("SELECT nome FROM experimentos WHERE (id = :id) AND (fk_pessoa = :fk_pessoa) LIMIT 1 ");
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->bindParam(':id', $iId);
            $sql->execute();
            if ($sql->rowCount() > 0) {
                $resultado = $sql->fetchObject();
                gravaLog('Exclusão do experimento ' . $resultado->nome);
            }        
            
            $sql = $conn->prepare("DELETE FROM experimentos WHERE (id = :id) AND (fk_pessoa = :fk_pessoa) LIMIT 1 ");
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->bindParam(':id', $iId);
            $sql->execute();
            $telaRetorno = '';  // 'Arquivo excluído com sucesso!';
        } 
        catch (Exception $e) {
            $telaRetorno = 'Erro ao excluír o arquivo: ' . $e->getMessage();
        }
    }

    echo $telaRetorno;
    exit;

} 


// ========== Faz upload de um arquivo ==========

else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'upload-arquivo')) {
    try {
        $tipoarq = isset($_GET['tipo']) ? $_GET['tipo'] : "ino";
        $permitidos = array('.' . $tipoarq);

        $nome_arquivo    = $_FILES['arquivo-codigo']['name'];
        $tamanho_arquivo = $_FILES['arquivo-codigo']['size'];

        $ext = strtolower(strrchr($nome_arquivo, "."));

        if (in_array($ext, $permitidos)) {
            $tamanho = round($tamanho_arquivo / 1024); //converte o tamanho para KB

            if ($tamanho < 2024) { //se imagem for até 2MB envia
                $nome_atual = md5(uniqid(time())) . $ext; //nome do arquivo
                $tmp        = $_FILES['arquivo-codigo']['tmp_name']; //caminho temporário da imagem

                if (move_uploaded_file($tmp, 'temp_upload/' . $nome_atual)) {
                    $conteudoArquivo = fopen('temp_upload/' . $nome_atual, 'r');
                    $linha           = '';
                    while (!feof($conteudoArquivo)) {
                        $linha .= fgets($conteudoArquivo, 4069);
                    }
                    fclose($conteudoArquivo);
                    //echo $linha;

                    if ($linha != '') {

                        $somente_nome = explode(".", $nome_arquivo);

                        //Verificando se já existe um código com esse nome
                        $sql       = $conn->prepare("SELECT COUNT(id) AS total FROM experimentos 
                                            WHERE ((fk_pessoa = :fk_pessoa) AND (nome = :nome)) ");
                        $sql->bindParam("fk_pessoa", $_SESSION['id_usuario']);
                        $sql->bindParam("nome", $somente_nome[0]);
                        $sql->execute();
                        $resultado = $sql->fetchObject();
                        if ((isset($resultado->total)) && ($resultado->total > 0)) {
                            $rJson = array("id" => 0,
                                            "msg" => 'Já existe um código com esse nome (' . $somente_nome[0] . ')!',
                                            "nome" => '',
                                            "codigo" => '',
                                            "erro" => 1);
                        } 
                        else {
                            $sql = $conn->prepare("INSERT INTO experimentos (
                                                        nome,
                                                        codigo,
                                                        dt_envio,
                                                        publico,
                                                        fk_pessoa,
                                                        fk_lab_tipo
                                                    )
                                                    VALUES (
                                                        :nome,
                                                        :codigo,
                                                        NOW(),
                                                        'N',
                                                        :fk_pessoa,
                                                        :fk_lab_tipo
                                                    ) ");
                            $sql->bindParam(':nome', $somente_nome[0]);
                            $sql->bindParam(':codigo', $linha);
                            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
                            $sql->bindParam(':fk_lab_tipo', $_SESSION['id_tipo']);
                            $sql->execute();

                            $iIdNovo = $conn->lastInsertId();

                            $rJson = array("id" => $iIdNovo,
                                            "msg" => 'Código enviado com sucesso!',
                                            "nome" => $somente_nome[0],
                                            "codigo" => $linha,
                                            "erro" => 0);
                        }
                    } 
                    else {
                        $rJson = array("id" => 0,
                                        "msg" => 'Código não enviado!',
                                        "nome" => '',
                                        "codigo" => '',
                                        "erro" => 1);
                    }
                } 
                else {
                    $rJson = array("id" => 0,
                                    "msg" => 'Falha ao enviar o código!',
                                    "nome" => '',
                                    "codigo" => '',
                                    "erro" => 2);
                }
            } 
            else {
                $rJson = array("id" => 0,
                                "msg" => 'O Código deve ter no máximo 2MB!',
                                "nome" => '',
                                "codigo" => '',
                                "erro" => 3);
            }
        } 
        else {
            $rJson = array("id" => 0,
                            "msg" => 'O código deve ser do Arduino.',
                            "nome" => '',
                            "codigo" => '',
                            "erro" => 4);
        }

        //exec('abuild zerar.pde', $r);
        //pclose(popen("start /B abuild zerar.pde", "r"));	
        $meuJson = json_encode($rJson);
        echo $meuJson;
        exit;
    }
    catch (Exception $e) {
        $rJson = array("id" => 0,
                            "msg" => 'Erro -> ' . $e->getMessage(),
                            "nome" => '',
                            "codigo" => '',
                            "erro" => 4);   
        
            $meuJson = json_encode($rJson);
        echo $meuJson;
        exit;
    }
} 

// ========== Edita um arquivo ==========

else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'editar') && (isset($_GET['metodo'])) && ($_GET['metodo'] == 'ajax')) {

    // log_error ('1', 'entrei');

    //Montando grid de codigo
    try {
        $telaCodigo     = '';
        $iId            = 0;
        $telaId         = 0;
        $telaNomeCodigo = '';
        if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
            $iId = $_GET['id'];
        }
        
        //se o ID for de 1 a $LIMITE_EXEMPLOS pode liberar para aparecer independente do FK_PESSOA, pois são os códigos de exemplo
        $sFiltro = " AND (experimentos.fk_pessoa = {$_SESSION['id_usuario']}) ";
        if ($iId > 0 && $iId <= $LIMITE_EXEMPLOS) {
            $sFiltro = '';
        }     
        
        $sql       = $conn->prepare("SELECT
                                        experimentos.id,
                                        experimentos.nome,
                                        experimentos.codigo
                                    FROM
                                        experimentos
                                    WHERE
                                        (experimentos.id = :id) {$sFiltro}                   
                                    LIMIT 1 ");                                        
        $sql->bindParam(':id', $iId);
        $sql->execute();
        $resultado = $sql->fetchObject();

        // log_error ("2", "id=".$resultado->id);
        // log_error ("3", "id=".$resultado->codigo);


        if (isset($resultado->id)) {
            $telaCodigo     = trim($resultado->codigo);
            $telaId         = $resultado->id;
            $telaNomeCodigo = $resultado->nome;
            
            gravaLog('Editando o experimento ' . $telaNomeCodigo);

            $rJson = array("codigo" => $telaCodigo,
                            "nome" => $telaNomeCodigo,
                            "id" => $telaId,
                            "erro" => 1);
        } 
        else {
            $rJson = array("codigo" => $telaCodigo,
                            "nome" => $telaNomeCodigo,
                            "id" => $telaId,
                            "erro" => 2);
        }

        echo json_encode($rJson);
    } 
    catch (Exception $e) {
        $rJson = array("codigo" => $telaCodigo,
                        "nome" => $telaNomeCodigo,
                        "id" => $telaId,
                        "erro" => 3);

        echo json_encode($rJson);
    }

    exit;
}


require_once 'app.include/header.inc.php';

$telaCodigo     = '';
$iId            = 0;
$telaId         = 0;
$telaNomeCodigo = '';
if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
    $iId = $_GET['id'];
}

$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
/*
if ($acao != 'novo') {
    $sql       = $conn->prepare("SELECT
                                    experimentos.id,
                                    experimentos.nome,
                                    experimentos.codigo
                                FROM
                                    experimentos
                                WHERE
                                    (experimentos.fk_pessoa = :fk_pessoa) 
                                    AND (experimentos.id = :id) ");
    $sql->bindParam(':id', $iId);
    $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
    $sql->execute();
    if ($sql->rowCount() > 0) {
        $resultado = $sql->fetchObject();

        $telaCodigo     = trim($resultado->codigo);
        $telaId         = $resultado->id;
        $telaNomeCodigo = $resultado->nome;
    }
} 
*/
?>

<h1>Experimentos</h1>

Laboratório: 
<?
if ($permissaoExecutar) {
    echo "$lab_nome ({$_SESSION['nome_tipo']})";
    echo " - Seu horário agendado: ".substr($hora_inicio,0,5)." às ".substr($hora_fim,0,5)." horas";
}
else
    echo "Não há laboratório reservado no momento";
?>

<div class="row">
    <div class="col-md-12">

        <!-- Área do streaming de vídeo -->
        <div id="video" class="col-md-8 .col-xs-8 panel">
            <?
            if ($permissaoExecutar) {
                // acrescenta parâmetro aleatório na url do stream para evitar cache do browser
                echo '<video width="95%" height="95%" controls="" autoplay=""> <source src="' . $url_video.'?'.md5(uniqid('')) . '" type="video/ogg"></video><br/>';
            ?>


                <div class="videocontroller">
                    Gravador de Vídeo: <span id="record_msg">Parado.</span>

                    <button type="button" class="btn btn-default" id="record_button">
                      <span class="glyphicon glyphicon-film"></span> Iniciar Gravação
                    </button>
                </div>
                <br/>

            <?
            }
            ?>
      
        </div>

        <!-- Área com as listas de programas e vídeos salvos -->
        <div id="lista-codigo"  class="col-md-4 .col-xs-4">
            <div class="panel panel-default">
                <div role="tabpanel">

                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="rounded active"><a href="#arqs" aria-controls="arqs" role="tab" data-toggle="tab" onclick="localStorage.setItem('tabAtiva', 'arqs');">Meus Arquivos</a></li>
                    <li role="presentation" class="rounded"><a href="#videos" aria-controls="videos" role="tab" data-toggle="tab" onclick="localStorage.setItem('tabAtiva', 'videos');">Meus Vídeos</a></li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="arqs">
                        <div class="panel-body box-codigo">
                            <ul class="list-group listagem-codigo">
                                <!-- ?php echo $TGrid; ? -->
                            </ul>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="videos">
                        <div class="panel-body box-codigo">
                            <ul class="list-group listagem-video">
                                <!-- ?php echo $VGrid; ? -->
                            </ul>
                        </div>
                    </div>
                  </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Menu intermediário e área de edição de texto -->
    <div class="col-md-12 clearfix">
        <div class="panel panel-default box-visualiza-codigo clearfix">
            <div class="panel-heading clearfix" id="menuAcao">
                <ul class="nav nav-pills">
                    <li><a href="#" id="novo-codigo" class="btAcao"  title="Criar novo código">Novo</a></li>
                    <li><a href="#" id="codigo-exemplo" class="btAcao" title="Exemplos" data-toggle="dropdown">Exemplos<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="laboratorio.php?id=1&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-led">LED</a></li>
                            <li><a href="laboratorio.php?id=3&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-display-caracteres">Display de Caracteres</a></li>                         
                            <li><a href="laboratorio.php?id=5&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-display-7-segmentos">Display de 7 Segmentos</a></li>                         
                            <li><a href="laboratorio.php?id=7&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-led-rgb">LED RGB</a></li>                         
                            <li><a href="laboratorio.php?id=9&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-servo">Servo</a></li>                         
                            <li><a href="laboratorio.php?id=11&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-motordc">MotorDC</a></li>                         
                            <li><a href="laboratorio.php?id=13&acao=editar" class="lkDiretoCodigo" id="codigo-exemplo-rele">Relé</a></li>                                                     
                        </ul>   
                    </li>
                    <li><a href="#" id="salvar-codigo" class="btAcao" title="Salvar alterações" data-toggle="dropdown">Salvar<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="salvar">Salvar</a></li>
                            <li><a href="#" id="salvar-como">Salvar como</a></li>                         
                        </ul>   
                    </li>

                    <?php if ($permissaoExecutar) { ?>	
                        <?php if ($incluir_multiplexacao=='S') { ?>	
                        <li><a href="#" id="executar-codigo" class="btAcao dropdown-toggle" 
                               title="Escolha um dispositivo para executar seu experimento!" data-toggle="dropdown">Executar<span class="caret"></span></a>				
                            <ul class="dropdown-menu">
                                <li><a href="laboratorio.php?acao=executar&tipo=leds" class="executar-codigo-como">LED</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=display_caracteres" class="executar-codigo-como">Display de Caracteres</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=display_7" class="executar-codigo-como">Display de 7 segmentos</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=ledrgb" class="executar-codigo-como">LED RGB</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=servo" class="executar-codigo-como">Servo</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=motordc" class="executar-codigo-como">Motor DC</a></li>
                                <li><a href="laboratorio.php?acao=executar&tipo=rele" class="executar-codigo-como">Relé</a></li>
                            </ul>
                        </li>
                        <?php } else { ?>
                        <li><a href="#" id="executar-codigo" class="btAcao dropdown-toggle" 
                               title="Execute seu experimento!"data-toggle="dropdown">Executar<span class="caret"></span></a>				
                            <ul class="dropdown-menu">
                                <li><a href="laboratorio.php?acao=executar" class="executar-codigo-como">Código Arduino</a></li>
                            </ul>
                        </li>

                        <?php } ?>
                    <?php } else { ?>
                    <li><a href="#" id="executar-codigo" class="btAcao dropdown-toggle"  data-toggle="dropdown"
                           title="Agende um horário para executar seus experimentos">Agende um horário<span class="caret"></span></a>
                           <?= $menu_agendamento ?>
                    </li>
                    <?php } ?>

                    <li><a href="#" id="enviar-codigo-arduino" class="btAcao file-inputs"  title="Enviar para Meus Códigos">Upload Arquivo</a>
                        <form name="formUploadCodigo" id="formUploadCodigo" method="post" enctype="multipart/form-data" action="laboratorio.php?acao=upload-arquivo&tipo=ino">
                            <input type="file" name="arquivo-codigo" id="arquivo-codigo">
                        </form>
                    </li>
                    <li><a href="download.php?acao=download&tipo=ino&id=<?php echo $telaId; ?>" target="_blank" 
                         id="dowload-codigo" class="btAcao" title="Download Arquivo">Download Arquivo</a></li>
                </ul>
            </div>
                <?php
                echo getFormEditar($telaId, $telaNomeCodigo, $telaCodigo, $acao);
                ?>
        </div>

    </div>
</div>
</div>


<!-- modal dialogs -->

<div class="modal boxAlert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal boxPedido">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Informe o novo nome do arquivo: <input type="text" name="txtRenomear" id="txtRenomear" maxlength="70"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btRenomear" class="btn btn-primary" data-dismiss="modal">Gravar</button>
                <button type="button" id="btRenomearFechar" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal boxPergunta">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btExclusaoConfirmar" class="btn btn-primary" data-dismiss="modal">Sim</button>
                <button type="button" id="btExclusaoFechar" class="btn btn-danger" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>

<div class="modal boxPerguntaConfirmacao">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Deseja realmente descartar a alteração do código?</p>
            </div>  
            <div class="modal-footer">
                <button type="button" id="btConfirmaPergunta" class="btn btn-primary" data-dismiss="modal">Sim</button>
                <button type="button" id="btCancelaPergunta" class="btn btn-danger" data-dismiss="modal">Não</button>
            </div>	
        </div>
    </div>
</div>

<!-- boxVideo -->

<div class="modal boxVideo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Meus Vídeos!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"></p>
                <span id="video-tag"></span>
                <br/>
            </div>  
            <div class="modal-footer">
                <button type="button" id="btRenomearFechar" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>	
        </div>
    </div>
</div>

<!-- msgCarregando -->   
<div id="msg-carregando">Carregando...</div>

<script type="text/javascript">

    var acaoPedida = "", urlChamada = "", idCodigoExclusao = 0, caminhoExclusao = "";        

    function ativaTab(tab){
        // Ativa uma das tabs "Meus videos" ou "Meus Arquivos"
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
    };

    function carregaArqs() {
        // Refresh na lista meus arquivos
        $("ul.listagem-codigo").append('Carregando...').load('laboratorio.php?acao=listagem&tipo=ino');
    }

    function carregaVideos() {
        // Refresh na lista meus vídeos
        // $("ul.listagem-video").append('Carregando...').load('laboratorio.php?acao=videos');
        $("ul.listagem-video").append('Carregando...').load('videos.php?acao=lista');
    }

    function aguarde(exibir) {
        if (exibir)
            $("#msg-carregando").show();
        else
            $("#msg-carregando").hide(5000);
    }

    function playVideo (idPlay, dtPlay, nomeLab) {
        $(".boxVideo #mensagem-local").html('[<b>'+nomeLab+'</b>] '+dtPlay);

        var video_path = '/videos/'+nomeLab+'/'+nomeLab+'_cam1_'+idPlay+'.ogg';
        $(".boxVideo #video-tag").html('<video width="95%" height="95%" controls="" autoplay=""><source src="'+video_path+'" type="video/ogg"></video>');
        $(".boxVideo").modal('show');
        return false;
    }


    /* relógio do cliente
    function getMinutesUntilNextHour()   { return 60 - new Date().getMinutes(); } 
    function getSecondsUntilNextMinute() { return 60 - new Date().getSeconds(); } 
    */
    /* relógio do servidor */
    function getMinutesUntilNextHour()   { return 60 - <?= date('i') ?> }
    function getSecondsUntilNextMinute() { return 60 - <?= date('s') ?> }

    <?php if ($permissaoExecutar) { ?>
        var tos = (getMinutesUntilNextHour() * 60) + getSecondsUntilNextMinute(); // seconds to timeout
        var idInt=''; 
    <?php } ?>

    // teste para verificar se a reserva do laboratório ainda está ativa
    function ctrlTimeout() { 
        if(tos==0) { 
            window.clearInterval(idInt); 
            location.href = "labvad.php"; 
        } 
        tos--; 
    } 

    $(document).ready(function () {

        var nomeCodigoExclusao = "";    
        var idVideoInclusao = 0;    

        // Testa a cada segundo se atingiu a hora limite da sessão reservada no laboratório remoto.
        <?php if ($permissaoExecutar) { ?>
        idInt = window.setInterval(ctrlTimeout, 1000); 
        <?php } ?>
        
        // Carrega tabs com lista de arquivos e vídeos
        carregaArqs();
        carregaVideos();

        // Inicializa o editor de programas
        var editor = ace.edit("txtCodigo");
        editor.setTheme("ace/theme/crimson_editor");
        editor.getSession().setMode("ace/mode/c_cpp");
        editor.resize();
        editor.getSession().setUseWrapMode(false);
        editor.blockScrolling = Infinity;

        // Inicializa autosave
        localStorage.setItem("comparaCodigo", localStorage.getItem("codigo"));

        // Inicializa tab ativa
        ativaTab(localStorage.getItem("tabAtiva"));


        // Callback: Iniciar e terminar de gravar vídeo

        var paused = true;   
        var gPid;
        var gRNameOf;
        var gExtOf;
     
        $("#record_button").click(function() { 
            if (paused) {

                // Primeiro clique: inicia a gravação...
                paused = false;
                $("#record_button").html('<span class="glyphicon glyphicon-pause"></span> Parar Gravação');
                $("#record_msg").text("Gravando.");
                $("#record_msg").css('color', '#F00');

                // chama o servidor central para salvar o descritor do vídeo no banco de dados
                // $.post("laboratorio.php?acao=save-video",
                $.post("videos.php?acao=save",
                    {},
                    function (retorno) {

                        if (retorno.erro === 0) {
                            //emite mensagem dizendo que o descritor do vídeo foi salvo com sucesso.
                            //$("#mensagem-local").html(retorno.msg);
                            //$('.boxAlert').modal('show');
                            idVideoInclusao = retorno.id; // salva id do video recem incluído

                            // chama o servidor remoto para iniciar gravação do arquivo de vídeo
                            // alert ("chamando: <?=$ip_lab?>/labvad-remoteserver/streaming/php/recordStream.php");
                            $.post("<?=$ip_lab?>/labvad-remoteserver/streaming/php/recordStream.php",
                                { id: retorno.id },
                                function (retorno) {
                                    //alert ("retorno="+retorno.erro);
                                    //alert ("msg="+retorno.msg);
                                    if (retorno.erro === 0) {
                                        // recebe info do processo que está salvando o vídeo
                                    	gPid = retorno.pid;
                                    	gRNameOf = retorno.rNameOf;
                                    	gExtOf = retorno.extOf;

                                        $("#mensagem-local").html(retorno.msg);
                                        $('.boxAlert').modal('show');
                                    }
                                    else if (retorno.erro > 0) {
                                        $("#mensagem-local").html(retorno.msg);
                                        $('.boxAlert').modal('show');
                                    }
                                },
                                'json'
                            );

                        }
                        else if (retorno.erro > 0) {
                            $("#mensagem-local").html(retorno.msg);
                            $('.boxAlert').modal('show');
                        }

                    },
                    'json'
                );

            }
            else {

                // Segundo clique: finaliza a gravação...
                paused = true;
                $("#record_button").html('<span class="glyphicon glyphicon-film"></span> Iniciar Gravação');
                $("#record_msg").text("Parado.");
                $("#record_msg").css('color', '#000');

                // chama o servidor remoto para finalizar gravação do arquivo de vídeo.
                // devolve info do processo que está salvando o vídeo.
                // alert("chamando: "+"<?=$ip_lab?>/labvad-remoteserver/streaming/php/stopStreamRecording.php");
                $.post("<?=$ip_lab?>/labvad-remoteserver/streaming/php/stopStreamRecording.php",
                    { pid: gPid, rnameof: gRNameOf, extof: gExtOf },
                    function (retorno) {
                        // alert ("retorno="+retorno.erro);
                        if (retorno.erro === 0) {
                            $("#mensagem-local").html(retorno.msg);
                            $('.boxAlert').modal('show');
                            carregaVideos();
                            ativaTab('videos');
                        }
                        else if (retorno.erro > 0) {
                            $("#mensagem-local").html(retorno.msg);
                            $('.boxAlert').modal('show');

                            // se deu erro ao salvar o arquivo com o video, remove o descritor no banco de dados
                            // alert ("videos.php?acao=exclusao&id="+idVideoInclusao);
                            $.post("videos.php?acao=exclusao&id="+idVideoInclusao,
                                {},
                                function (retorno3) {
                                    // debug indicando se o descritor foi removido
                                    alert(retorno3+" "+idVideoInclusao);
                                    idVideoInclusao = 0;
                                }
                            );

                        }
                    },
                    'json'
                );
            }
        });
         

        // Callbacks: Salvar e Salvar como

        $('#salvar-como').on('click', function () {
            $(".boxPedido").modal('show');
            $("#txtRenomear").val($('#txtNomeCodigo').val());
        });

        $("#btRenomearFechar").on('click', function () {
            $(".boxPedido").modal('hide');
            return false;
        });

        $("#btRenomear").on('click', function () {
            var nome = $("#txtRenomear").val();
            $("#idCodigo").val(0);
            if (nome === '') {
                return false;
            }
            else {
                $('#txtNomeCodigo').val(nome);
                $("#salvar").click();
            }
        });

        $('#salvar').on('click', function () {

            caminho   = $('#formCodigo').attr('action');
            mIdCodigo = $('#idCodigo').val();
            mCodigo   = editor.getValue();
            mNomeCodigo = $('#txtNomeCodigo').val();

            if (mNomeCodigo === '') {
                $("#mensagem-local").html('Informe o nome do seu programa!');
                $('.boxAlert').modal('show');
                return false;
            }

            $.post(caminho,
                {
                    idCodigo: mIdCodigo,
                    txtCodigo: mCodigo,
                    txtNomeCodigo: mNomeCodigo
                },
                function (retorno) {
                    if (retorno.erro === 0) {
                        $("#idCodigo").val(retorno.id);
                        $('#txtNomeCodigo').val(retorno.nome);

                        $("#mensagem-local").html(retorno.msg);
                        $('.boxAlert').modal('show');

                        localStorage.setItem("alterado", "n");
                        localStorage.setItem("comparaCodigo", editor.getValue()); //$("#txtCodigo").val());
                        carregaArqs();
                    }
                    else if (retorno.erro > 0) {
                        $("#mensagem-local").html(retorno.msg);
                        $('.boxAlert').modal('show');
                        carregaArqs();
                    }

                },
                'json'
            );

            return false;
        });


        // Callback: Executar código arduino

        $('.executar-codigo-como').click(function () {
            caminho     = $(this).attr('href');
            mIdCodigo   = $('#idCodigo').val();
            mCodigo     = editor.getValue(); //$('#txtCodigo').val();
            mNomeCodigo = $('#txtNomeCodigo').val();

            $.post(caminho,
                {
                    idCodigo: mIdCodigo,
                    txtCodigo: mCodigo,
                    txtNomeCodigo: mNomeCodigo,
                    salvar_video: $('#salvar_video').is(":checked") ? 'T' : 'F'
                },
                function (retorno) {
                    if (retorno.erro === 0) {
                        $(".boxAlert #mensagem-local").html(retorno.msg);
                        $('.boxAlert').modal('show');
                    }
                    else if (retorno.erro === 3) {
                        $(".boxAlert #mensagem-local").html("Erro de Compilação. Verifique seu programa.<br><pre>"+retorno.msg+"</pre>");
                        $('.boxAlert').modal('show');
                    }
                    else {
                        $(".boxAlert #mensagem-local").html("Erro.<br><pre>"+retorno.msg+"</pre><br>DEBUG: "+retorno.debug);
                        $('.boxAlert').modal('show');
                    }
                },
                'json'
            );

            return false;
        });


        // Callback: Excluir código arduíno

        $('ul.listagem-codigo').on('click', '.lkExclusaoCodigo a', function (){
            myUrl            = $(this).attr('href');
            parametros       = myUrl.split("?")[1];
            caminhoExclusao  = myUrl;
            idCodigoExclusao = parametros.split("=")[2];

            if ((idCodigoExclusao === 0) || (idCodigoExclusao === "")) {
                $(".boxAlert #mensagem-local").html('Selecione o código para realizar a exclusão!');
                $('.boxAlert').modal('show');
                return false;
            }
            else {
                $(".boxPergunta #mensagem-local").html('Deseja realmente excluír esse código?');
                $(".boxPergunta").modal('show');
            }
            
            return false;
        });


        // Callback: Excluir vídeo

        $('ul.listagem-video').on('click', '.lkExclusaoVideo a', function (){
            myUrl            = $(this).attr('href');
            parametros       = myUrl.split("?")[1];
            caminhoExclusao  = myUrl;
            idCodigoExclusao = parametros.split("=")[2];

            if ((idCodigoExclusao === 0) || (idCodigoExclusao === "")) {
                $(".boxAlert #mensagem-local").html('Selecione o vídeo para realizar a exclusão!');
                $('.boxAlert').modal('show');
                return false;
            }
            else {
                $(".boxPergunta #mensagem-local").html('Deseja realmente excluír esse vídeo?');
                $(".boxPergunta").modal('show');
            }
            
            return false;
        });


        // Callback: Respostas do popup para confirmar exclusão

        $(".boxPergunta #btExclusaoFechar").on('click', function () {
            $(".boxPergunta").modal('hide');
        });

        $(".boxPergunta #btExclusaoConfirmar").on('click', function () {
            caminho     = caminhoExclusao;
            mIdCodigo   = idCodigoExclusao;

            $.post(caminho,
                {
                    idCodigo: mIdCodigo
                },
                function (retorno) {
                    $(".boxPergunta").modal('hide');
                    if (retorno) {
                        $(".boxAlert #mensagem-local").html(retorno);
                        $('.boxAlert').modal('show');
                    }
                    if (localStorage.getItem("tabAtiva") == "videos") carregaVideos();
                    if (localStorage.getItem("tabAtiva") == "arqs") carregaArqs();
                }
            );

            return false;
        });


        // Callback: Upload código arduíno

        $('#enviar-codigo-arduino').on('click', function () {
            acaoPedida = "enviar-codigo";
            if (verificaSalvarCodigo()) {
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                $('#arquivo-codigo').click();
            }
            
            return false;
        });

        $('#arquivo-codigo').on('change', function () {
            $('#formUploadCodigo').ajaxForm({
                dataType: 'json',
                success: function (retorno) {
                    $('#idCodigo').val(retorno.id);
                    $("#txtNomeCodigo").val(retorno.nome);
                    editor.setValue(retorno.codigo); //$("#txtCodigo").val(retorno.codigo);

                    $("#dowload-codigo").attr('href', 'download.php?acao=download&tipo=ino&id=' + retorno.id);
                    $("#exclusao-codigo").attr('href', 'laboratorio.php?acao=exclusao&id=' + retorno.id);

                    $("#mensagem-local").html(retorno.msg);
                    $('.boxAlert').modal('show');

                    carregaArqs();
                }
            }).submit();
        });

        // Callback: Download código arduíno

        $('#dowload-codigo').on('click', function () {
            var urlDownload = $(this).attr('href');
            var parametros  = urlDownload.split('?')[1];
            var id          = parametros.split('&')[1].split('=')[1];
            if (parseInt(id) === 0) {
                $(".boxAlert #mensagem-local").html('Selecione o código para realizar o download!');
                $('.boxAlert').modal('show');
                return false;
            }
        });

        
        // Callback: Abrir código exemplo

        $('a.lkDiretoCodigo').on('click', function () {
           acaoPedida = "abre-codigo";
           urlChamada = $(this).attr('href') + '&metodo=ajax';
            if (verificaSalvarCodigo()) {
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                localStorage.setItem("alterado", "");
                $("#btConfirmaPergunta").click();
            }
            
            return false;
        });


        // Callback: Abrir código salvo pelo usuário

        $('ul.listagem-codigo').on('click', '.itemCodigo', function (){
            //myUrl = this.href;
            //urlChamada = myUrl + '&metodo=ajax';
            acaoPedida = "abre-codigo";
            urlChamada = $(this).attr('href') + '&metodo=ajax';
            if (verificaSalvarCodigo()) {
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                localStorage.setItem("alterado", "");
                $("#btConfirmaPergunta").click();    
            }
            return false;
        });


        // Callback: Resposta SIM à pergunta "Deseja realmente descartar a alteração do código?"

        $("#btConfirmaPergunta").on("click", function() {

            localStorage.setItem("nomeCodigo", "");
            localStorage.setItem("codigo", "");
            
            if (acaoPedida === "novo") {    
                limpaAreaCodigo();
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                localStorage.setItem("idCodigo", "");
                localStorage.setItem("comparaCodigo", "");
                localStorage.setItem("alterado", "n");


                //$(".boxEsp").modal('show');    
                //document.location.href = $("#novo-codigo").attr('href');
            }
            else if (acaoPedida === "enviar-codigo") {
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                localStorage.setItem("alterado", "");
                $('#arquivo-codigo').click();
            }
            else if (acaoPedida === "logout") {
                document.location.href = $("#logout").attr('href');
            }
            else if (acaoPedida === "abre-codigo") {
                var url = urlChamada; //$(this).attr('href') + '&metodo=ajax';
                aguarde(true);
                //alert ("chamando:"+urlChamada);

                $.post(
                    url,
                    function (retorno) {
                        if (retorno.erro === 1) {
                            $("#idCodigo").val(retorno.id);
                            $("#txtNomeCodigo").val(retorno.nome).attr('disabled', 'disabled');
                            editor.setValue(retorno.codigo); //$("#txtCodigo").val(retorno.codigo);

                            localStorage.setItem("alterado", "n");
                            localStorage.setItem("comparaCodigo", retorno.codigo);

                            $("#dowload-codigo").attr('href', 'download.php?acao=download&tipo=ino&id=' + retorno.id);
                            $("#exclusao-codigo").attr('href', 'laboratorio.php?acao=exclusao&id=' + retorno.id);
                        }
                    },
                    'json'
                )
                .done(function () {

                })
                .fail(function () {

                })
                .always(function () {
                    aguarde(false);
                });
            }
        });


        // Callback: Criar novo código

        $("#novo-codigo").on("click", function () {
            acaoPedida = "novo";
            if (verificaSalvarCodigo()) {
                limpaAreaCodigo();

                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                localStorage.setItem("idCodigo", "");
                localStorage.setItem("comparaCodigo", "");
                localStorage.setItem("alterado", "n");

                //$(".boxEsp").modal('show');
            }
            else {                
                return false;
            }
        });


        // Callback: Logout

        $("#logout").on("click", function () {
            acaoPedida = "logout";
            if (verificaSalvarCodigo()) {
                $(".boxLogout").modal('show');
                return false;
            }
        });
        
        $("#btLogoutSim").on("click", function () {
            document.location.href = $("#logout").attr('href');
        });

        var lkSaida = "";

        $("#btConfirmarSaida").on("click", function () {
            if (lkSaida !== '') {
                document.location.href = lkSaida;
            }
        });

        if (editor.getValue() === "") {
            $("#txtNomeCodigo").val(localStorage.getItem("nomeCodigo"));
            editor.setValue(localStorage.getItem("codigo")); //$("#txtCodigo").val(localStorage.getItem("codigo"));
            $("#idCodigo").val(localStorage.getItem("idCodigo"));
            if ($("#idCodigo").val() > 0) {
                $("#txtNomeCodigo").attr('disabled', 'disabled');
            }
        }

        /*$("#txtCodigo").keydown(function () {
            gravarTemp();
            localStorage.setItem("alterado", "s");
        });*/
        
        editor.getSession().on('change', function(e) {
            gravarTemp();
            localStorage.setItem("alterado", "s");
        });

        $("#menu-navegacao-horizontal a").on("click", function () {
            lkSaida = $(this).attr("href");
            gravarTemp();
            return true;
        });

        
        // Funções auxiliares para autosave

        function verificaSalvarCodigo() {

            /* False = pergunta; True = sem pergunta */
            var id       = $("#idCodigo").val();
            var nome     = $("#txtNomeCodigo").val();
            var codigo   = editor.getValue(); 
            var alterado = localStorage.getItem("alterado");
            var retorno  = true;

            if ((acaoPedida === "novo") || (acaoPedida === "enviar-codigo") || (acaoPedida === "logout") || (acaoPedida === "abre-codigo")) {         
                if ((alterado === "s") || ((id === "") && ((nome !== "") || (codigo !== "")))) {
                    $(".boxPerguntaConfirmacao .mensagem-local").html('Deseja realmente descartar a alteração do código?');
                    $(".boxPerguntaConfirmacao").modal('show');
                    retorno = false;
                }  
                else {
                    retorno = true;
                }
            }            
            
            return retorno;
        }

        function limpaAreaCodigo() {
            $("#txtNomeCodigo, #idCodigo, #txtCodigo").val('');
            editor.setValue('');
        }


        function gravarTemp() {
            var nomeCodigo = $("#txtNomeCodigo").val();
            var codigo     = editor.getValue(); //$("#txtCodigo").val();
            var idCodigo   = $("#idCodigo").val();
            localStorage.setItem("nomeCodigo", nomeCodigo);
            localStorage.setItem("codigo", codigo);
            localStorage.setItem("idCodigo", idCodigo);
        }
    });
</script>

<style>
.ace_editor { height: 510px }
</style>

<script src="js/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>

<?php require_once 'app.include/footer.inc.php'; ?>
