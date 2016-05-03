<?php
$acaoLog = 'Help - Display de Caracteres';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.include/header.inc.php';
?>

<title>Display de Caracteres</title>

<body>
    <blockquote>
        <h1>Display de Caracteres <img src="img/caracteres.png" width="350" height="160"></h1>
        <h2>O que é? O que faz?                    </h2>
        <p>Um <strong>display de cristal líquido</strong>, acrônimo de <strong>LCD</strong> (em inglês <em>liquid crystal display</em>). Mostra dados lidos pelo LabVad em letras e números, de uma  forma inteligível.</blockquote>
    <blockquote>
        <h2>Como funciona no LabVad?</h2>


        <p>Os display está conectado ao LabVad nos pinos 6, 7, 8, 9, 10, 11, 12. Veja na imagem abaixo:</p>
        <p><img src="img/carac.png" width="540" height="340"></p>
    </blockquote>
    <blockquote>
        <p>Vale lembrar que chamamos o este display de  LCD de 16x2, pois ele tem 16 colunas de dígitos e duas linhas, ou seja, você  tem duas linhas para escrever até 16 caracteres. Isto vai ficar mais óbvio  quando veremos o dispositivo funcionando no LabVad. </p>
        <h2>//*****************************************************<br>
            // GERADOR de MENSAGENS com Display 16x2<br>
            // By S.Brandao <br>
            // 25/07/2014 - 3792 bytes <br>
            // Esta experiencia usa o DISPLAY de CARACTERES <br>
            //*****************************************************</h2>
        <p>#include &lt;LiquidCrystal.h&gt;</p>
        <p>// LiquidCrystal display with:<br>
            // rs on pin 6<br>
            // rw on pin 7<br>
            // enable on pin 8<br>
            // d4, d5, d6, d7 on pins 9, 10, 11, 12<br>
            LiquidCrystal lcd(6, 7, 8, 9, 10, 11, 12);<br>
            int brightness = 255;</p>
        <p>void setup()<br>
            {<br>
            Serial.begin(9600); <br>
            }</p>
        <p>void loop()<br>
            {<br>
            // Print a message to the LCD.<br>
            lcd.clear();<br>
            delay(500);<br>
            lcd.begin (16, 2); // Define o tamanho do DISPLAY <br>
            lcd.print(&quot;* NCE * LABVAD *&quot;);<br>
            delay(1500);<br>
            lcd.setCursor(0,1); // Escreve na Linha 1<br>
            lcd.print(&quot;Proj:  UCAnaCUCA&quot;);<br>
            brightness = 100;<br>
            delay(1500);<br>
            lcd.setCursor(0,1); // Escreve na  linha 1<br>
            lcd.print(&quot;* AGOSTO__2014 *&quot;);<br>
            delay(1500);<br>
            lcd.setCursor(0,0); // Escreve na linha 0<br>
            lcd.print(&quot;*  VISITANTES  *&quot;);<br>
            delay(1500);<br>
            lcd.setCursor(0,1); // Escreve na linha 1<br>
            lcd.print(&quot;Sejam Bem-vindos&quot;);<br>
            delay(1500);<br>
            }</p>
    </blockquote>
    <blockquote>
        <p><span class="animated"><a href="labvad.php">| Introdução</a> | <a href="sete.php">Próxima Lição</a> | <a href="img/LabVad_Guia.pdf">Baixar Guia do LabVad</a> | <a href="laboratorio.php">Experimentos</a> |</span></p>
    </blockquote>

    <?php require_once 'app.include/footer.inc.php'; ?>
