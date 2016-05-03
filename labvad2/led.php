<?php
$acaoLog = 'Help - LEDs';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.include/header.inc.php';
?>

<title>LEDs</title>

<body>
    <blockquote>
        <h1>LED <img src="img/led.jpg" width="350" height="175"></h1>
        <h2>O que é? O que faz?                    </h2>
        <p><strong>LED</strong> (<strong>L</strong>ight <strong>E</strong>mitting <strong>D</strong>iode). Emite uma luz quando uma pequena corrente o aciona.</blockquote>
    <blockquote>
        <h2>Como funciona no LabVad?</h2>


        <p>Os LEDs estão conectados no LabVad dos pinos 6 ao 13, como na figura abaixo:</p>
        <p><img src="img/led.png" width="540" height="340"></p>
        <p>Portanto para utilizá-los devemos fazer a declaração, conforme o exemplo abaixo:</p>
        <p>int ledVermelho1 = 6;</p>
        <p>int ledAmarelo1 = 7;</p>
        <p>int ledVerde1 = 8;</p>
        <p>int ledBranco1 = 9</p>
        <p>int ledVermelho2 = 10;</p>
        <p>int ledAmarelo2 = 11;</p>
        <p>int ledVerde2 = 12;</p>
        <p>int ledBranco2 = 13;</p>
        <p>&nbsp;</p>
    </blockquote>
    <blockquote>
        <h2>Veja um exemplo de código de domínio público:</h2>
        <p>/*<br>
            Piscar LED<br>
            Acende o LED por um segundo e depois o apaga por um segundo também.<br>
            <br>
            Este exemplo de código é de domínio publico<br>
            */<br>
            <br>
            // O nosso LED 13 é o último LED do canto esquerdo da tela do LabVad.<br>
            // Vamos declarar este LED:<br>
            int led = 13;</p>
        <p>// No função setup escrevemos parte do código que será executado uma vez:<br>
            void setup() { <br>
            // Inicializando LED como saída.<br>
            pinMode(led, OUTPUT); <br>
            }</p>
        <p>// O loop rodará parte do código até que o mesmo seja interrompido ou zerado.<br>
            void loop() {<br>
            digitalWrite(led, HIGH);   //Acende o LED <br>
            delay(1000);               // Espera um segundo. Para esperar meio segundo o valor atribuido seria 500<br>
            digitalWrite(led, LOW);    // Apaga o LED<br>
            delay(1000);               // Espera um segundo<br>
            }</p>
    </blockquote>
    <blockquote>
        <p><span class="animated"><a href="labvad.php"> | Introdução</a> | <a href="caracteres.php">Próxima Lição</a> | <a href="img/LabVad_Guia.pdf">Baixar Guia do LabVad</a> | <a href="laboratorio.php">Experimentos</a> |</span></p>
    </blockquote>

    <?php require_once 'app.include/footer.inc.php'; ?>
