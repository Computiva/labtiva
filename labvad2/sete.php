<?php
$acaoLog = 'Help - Display de 7 Segmentos';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.include/header.inc.php';
?>

<title>Display de 7 Segmentos</title>

<body>
    <blockquote>
        <h1>Display de 7 Segmentos<img src="img/7segment.png" width="200" height="200"></h1>
        <h2>O que é? O que faz?                    </h2>
        <p><strong>É um circuito encapsulado LEDs  interligados</strong>. Este tipo de display é amplamente utilizado  eletrodomésticos, relógios e equipamentos industriais. Trata-se de 8 LEDs  ligados com um ponto em comum. Para entender melhor o funcionamento veja a  imagem autoexplicativa acima.</blockquote>
    <blockquote>
        <h2>Como funciona no LabVad?</h2>


        <p><img src="img/7.png" width="540" height="340"></p>
    </blockquote>
    <blockquote>
        <h2>Veja um exemplo onde fica bem claro como você irá programá-lo.</h2>
        <p>/*********************************************     <br>
            * // DISPLAY 7 SEGMENTOS                          *     *<br>
            *                                                 <br>
            * // By S.Brandao                                 *     *<br>
            * // 25/07/2014 - 1670 bytes                      ***G***<br>
            * // Experiencia com o Display de 7 Segmentos     *     *<br>
            *                                                 <br>
            *<br>
            **********************************************    <br>
            */</p>
        <p>int timer = 2000;      // Tempo = 2 segundos.<br>
            <br>
            int Seg_F =  7;       // Segmento F.<br>
            int Seg_G =  6;       // Segmento G.<br>
            int Seg_E = 13;       // Segmento E.<br>
            int Seg_D = 12;        // Segmento D.<br>
            int Seg_A =  8;        // Segmento A.<br>
            int Seg_B =  9;        // Segmento B.<br>
            int Seg_C = 11;        // Segmento C.<br>
            int PD    = 10;       // Ponto Decimal.</p>
        <p> </p>
        <p>void setup()<br>
            {<br>
            pinMode(Seg_F,OUTPUT);  // Segmento F.<br>
            pinMode(Seg_G,OUTPUT);  // Segmento G.<br>
            pinMode(Seg_E,OUTPUT);  // Segmento E.<br>
            pinMode(Seg_D,OUTPUT);  // Segmento D.<br>
            pinMode(Seg_A,OUTPUT);  // Segmento A.<br>
            pinMode(Seg_B,OUTPUT);  // Segmento B. <br>
            pinMode(Seg_C,OUTPUT);  // Segmento C.<br>
            pinMode(PD,OUTPUT);     // Ponto Decimal.</p>
        <p> <br>
            }<br>
            void loop()</p>
        <p>{ <br>
            // DIGITO 1<br>
            digitalWrite(Seg_F,LOW);         // Segmento F OFF<br>
            digitalWrite(Seg_G,LOW);         // Segmento G OFF<br>
            digitalWrite(Seg_E,LOW);         // Segmento E OFF<br>
            digitalWrite(Seg_D,LOW);         // Segmento D OFF<br>
            digitalWrite(Seg_A,LOW);         // Segmento A OFF<br>
            digitalWrite(Seg_B,HIGH);        // Segmento B ON<br>
            digitalWrite(Seg_C,HIGH);        // Segmento C ON<br>
            digitalWrite(PD,HIGH);           //PONTO DEC. ON<br>
            delay(timer); <br>
            <br>
            // ------------------------------------------------- </p>
        <p> // DIGITO 2<br>
            digitalWrite(Seg_F,LOW);          // Segmento F OFF<br>
            digitalWrite(Seg_G,HIGH);         // Segmento G ON<br>
            digitalWrite(Seg_E,HIGH);         // Segmento E ON<br>
            digitalWrite(Seg_D,HIGH);         // Segmento D ON<br>
            digitalWrite(Seg_A,HIGH);         // Segmento A ON<br>
            digitalWrite(Seg_B,HIGH);         // Segmento B ON<br>
            digitalWrite(Seg_C,LOW);        // Segmento C OFF<br>
            digitalWrite(PD,LOW);            // PONTO DEC. OFF<br>
            delay(timer);</p>
        <p> // DIGITO 3<br>
            digitalWrite(Seg_F,LOW);          // Segmento F OFF<br>
            digitalWrite(Seg_G,HIGH);         // Segmento G ON<br>
            digitalWrite(Seg_E,LOW);          // Segmento E OFF<br>
            digitalWrite(Seg_D,HIGH);         // Segmento D ON<br>
            digitalWrite(Seg_A,HIGH);         // Segmento A ON<br>
            digitalWrite(Seg_B,HIGH);         // Segmento B ON<br>
            digitalWrite(Seg_C,HIGH);         // Segmento C ON<br>
            digitalWrite(PD,HIGH);             // PONTO DEC. ON<br>
            delay(timer);</p>
        <p>// DIGITO 4<br>
            digitalWrite(Seg_F,HIGH);        // Segmento F ON<br>
            digitalWrite(Seg_G,HIGH);        // Segmento G ON<br>
            digitalWrite(Seg_E,LOW);         // Segmento E OFF<br>
            digitalWrite(Seg_D,LOW);         // Segmento D OFF<br>
            digitalWrite(Seg_A,LOW);         // Segmento A OFF<br>
            digitalWrite(Seg_B,HIGH);        // Segmento B ON<br>
            digitalWrite(Seg_C,HIGH);        // Segmento C ON<br>
            digitalWrite(PD,LOW);            // PONTO DEC. OFF<br>
            delay(timer);<br>
            // DIGITO 5<br>
            digitalWrite(Seg_F,HIGH);        // Segmento F ON<br>
            digitalWrite(Seg_G,HIGH);        // Segmento G ON<br>
            digitalWrite(Seg_E,LOW);         // Segmento E OFF<br>
            digitalWrite(Seg_D,HIGH);         // Segmento D OFF<br>
            digitalWrite(Seg_A,HIGH);         // Segmento A OFF<br>
            digitalWrite(Seg_B,LOW);        // Segmento B ON<br>
            digitalWrite(Seg_C,HIGH);        // Segmento C ON<br>
            digitalWrite(PD,HIGH);            // PONTO DEC. OFF<br>
            delay(timer); <br>
            <br>
            }  // THE END</p>
    </blockquote>
    <blockquote>
        <p>&nbsp;</p>
        <p><span class="animated"><a href="labvad.php">| Introdução</a> | <a href="rgb.php">Próxima Lição</a> | <a href="img/LabVad_Guia.pdf">Baixar Guia do LabVad</a> | <a href="laboratorio.php">Experimentos</a> |</span></p>
    </blockquote>

<?php require_once 'app.include/footer.inc.php'; ?>
