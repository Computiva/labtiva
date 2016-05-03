<?php
$acaoLog = 'Help - Relé';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.include/header.inc.php';
?>

<title>Servo Motor</title>

<body>
    <blockquote>
        <h1>Relé <img src="img/rele2.png" width="140" height="150"></h1>
        <h2>O que é? O que faz?                    </h2>
        <p><strong>O  relé é um dispositivo eletromecânico, que serve para ligar ou desligar outros dispositivos.  </strong> Podemos utilizar um pequeno  sinal de corrente contínua de 5V para acionar uma lâmpada de 110V.  Poemos elaborar projetos mais robustos como automatizar toda uma residência com uma simples placa Arduino. Por  isso, no LabVad, utilizamos a ilustração de uma cafeteira.</blockquote>
    <blockquote>
        <h2>Motor DC<img src="img/dcmotor.png" alt="" width="239" height="175"></h2>
        <h2>O que é? O que faz?</h2>
        <p>Motores são usados em aplicações  fixas e móveis. São encontrados em processos de fabricação (em esteiras, por  exemplo), em sistemas de locomoção de robôs, cadeiras de roda motorizada, além  de muitos outros projetos. Sabe aquele projeto bacana com chassis e robôs segue  linha? Isso tudo só é possível através de motores DC. Esperamos que os  exercícios feitos no LabVad estimule você a criar muitos robôs que funcionam  através de motores.</p>
<h2>Veja como o Relé e o Motor DC funcionam no LabVad?</h2>
        <p>Como podemos ver na figura abaixo, o Motor DC do LabVad está conectado na entrada 6 e o Relé na entrada 8.</p>


        <p><img src="img/rele.png" width="540" height="340"></p>
    </blockquote>
    <blockquote>
        <h2>Vamos ver um código de como testar nosso Relé.</h2>
        <p>/* Experimento _ RELE - Acionando uma carga com tempo programado<br>
* By S.Brandao <br>
* Em: 25/07/2014  - <br>
*/</p>
        <p>int RELE = 8;        // Seleciona o pino da saida 8 <br>
          int Timer = 15000;   // Tempo de acionamento e desligamento</p>
        <p>// Saídas não utilizadas neste experimento da cat: MISCELANEA<br>
          int saida_6 = 6;<br>
          int saida_7 = 7;<br>
          int LED_VM = 9;<br>
          int LED_VD = 10;<br>
          int LED_AZ = 11;</p>
        <p>void setup() {<br>
          <br>
          pinMode(RELE, OUTPUT); // configura a porta do RELE como saida.<br>
  <br>
          // Decodificador do MUX.*********************************************<br>
          pinMode(LED_VM0,OUTPUT);  // Pino_3 - Decodificador do Conjunto bit_0<br>
          pinMode(LED_VM1,OUTPUT);  // Pino_5 - Decodificador do Conjunto bit_1<br>
          // Habilita MUX - 01 - Conjunto de LEDs - LED0 a LED7<br>
          digitalWrite(LED_VM0, HIGH);   // LED VM0 <br>
          digitalWrite(LED_VM1, HIGH);   // LED VM1<br>
          // ****************************************************************** <br>
  <br>
          // Resete das saidas NAO utilizadas neste experimento<br>
          pinMode(saida_6,OUTPUT);  // Pino_6 MOTOR<br>
          pinMode(saida_7,OUTPUT);  // Pino_7 SERVO<br>
          pinMode(LED_VM, OUTPUT);  // Pino_9<br>
          pinMode(LED_VD, OUTPUT);  // Pino_10 <br>
          pinMode(LED_AZ, OUTPUT);  // Pino_11<br>
          }</p>
        <p>void loop() {<br>
          <br>
          digitalWrite(RELE, LOW); <br>
          delay(Timer);<br>
          digitalWrite(RELE, HIGH); // Aciona o RELE por um tempo pre-definido<br>
          delay(Timer);<br>
          // F I M <br>
          }</p>
        <h2>Veja um exemplo de como programar o Motor DC.</h2>
        <p>/* Experimento _ Motor Dc com velocidade controlada<br>
            * By S.Brandao <br>
            * Em: 25/07/2014  - <br>
            */<br>
        </p>
        <p>int Motor_DC = 6;  // Seleciona o pino de saída PWM para o motor<br>
            int Valor = 125;   // Valor  de 0 a 45 (parado) de 50(vel.min.) a 255(vel. max)</p>
        <p>void setup() {<br>
            <br>
            pinMode(Motor_DC, OUTPUT); // configura a porta do motor como saída<br>
            <br>
            // Resete das saidas NAO utilizadas neste experimento<br>
            pinMode(saida_7,OUTPUT);  // Pino_7 SERVO<br>
            pinMode(saida_8,OUTPUT);  // Pino_8 RELE<br>
            pinMode(LED_VM, OUTPUT);  // Pino_9<br>
            pinMode(LED_VD, OUTPUT);  // Pino_10 <br>
            pinMode(LED_AZ, OUTPUT);  // Pino_11<br>
            }</p>
        <p>void loop() {<br>
            <br>
            analogWrite(Motor_DC, Valor); // Aciona o Motor<br>
            }</p>
    </blockquote><blockquote>
        <p><span class="animated"><a href="labvad.php">| Introdução</a> | <a href="img/LabVad_Guia.pdf">Baixar Guia do LabVad</a> | <a href="laboratorio.php">Experimentos</a> |</span></p>
    </blockquote>

<?php require_once 'app.include/footer.inc.php'; ?>
