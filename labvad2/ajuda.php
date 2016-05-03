<?php
$acaoLog = 'Ajuda';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
require_once 'app.include/header.inc.php';
?>

<style type="text/css">
    .white.inner-page .container.padding-inner #filterSection_menu .span12 .sort-wrap {
        color: #06F;
    }
    h1 {
        font-size: 16px;
    }
    .white.inner-page .container.padding-inner #filterSection_menu .span12 .sort-wrap h1 {
        font-size: 16px;
    }
    .white.inner-page .container.padding-inner #filterSection_menu .span12 .sort-wrap h1 {
        font-size: 36px;
    }
    .white.inner-page .container.padding-inner #filterSection_menu .span12 .sort-wrap h1 {
        font-size: 16px;
    }
    .white.inner-page .container.padding-inner #filterSection_menu .span12 .sort-wrap h1 {
        font-size: 18px;
    }
    .white.inner-page .container.padding-inner #filterSection_menu .span12 .sort-wrap h1 {
        font-size: 24px;
    }
    .white.inner-page .container.padding-inner #filterSection_menu .span12 .sort-wrap h1 {
        font-size: 18px;
    }
</style>
<title>Ajuda</title>
<body>
    <div class="white inner-page">

        <div class="container padding-inner">
            <div class="blog-content">
                <div class="blog-content">
                    <div class="caption">
                        <h1>O LabVad possui, em seu hardware,  diversos dispositivos de robótica. Para programá-lo basta entender como os dispositivos estão conectados no Hardware. Veja nas seções abaixo, como cada dispositivo funciona. </h1>
                        <p>&nbsp;</p>
                        <p>Agendar Experimentos                            </p>
                        <p><a href="agendahelp.php"><em>Leia mais</em></a><a href="#"><em>&rarr;</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="agendahelp.php"><img src="img/ajudaag.png" alt="Image" class="overlay-image" data-overlaytext="LEDs" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <h6>&nbsp;</h6>
                        <p>Programação dos LEDs                            </p>
                        <table border="1">
                        <tr><td><b>&nbsp;Variáveis do arquivo Labvad.h&nbsp;</b></td></tr>
                        <tr><td>&nbsp;LED_VM1</td></tr>
                        <tr><td>&nbsp;LED_AM1</td></tr>
                        <tr><td>&nbsp;LED_VD1</td></tr>
                        <tr><td>&nbsp;LED_AZ1</td></tr>
                        <tr><td>&nbsp;LED_VM2</td></tr>
                        <tr><td>&nbsp;LED_AM2</td></tr>
                        <tr><td>&nbsp;LED_VD2</td></tr>
                        <tr><td>&nbsp;LED_AZ2</td></tr>
                        </table>
                        <p><a href="led.php"><em>Leia mais</em></a><a href="#"><em>&rarr;</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="led.php"><img src="img/led.png" alt="Image" class="overlay-image" data-overlaytext="LEDs" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <h6>&nbsp;</h6>
                        <p>Display de Caracteres </p>
                        <table border="1">
                        <tr><td><b>&nbsp;Variáveis do arquivo Labvad.h&nbsp;</b></td></tr>
                        <tr><td>&nbsp;RS</td></tr>
                        <tr><td>&nbsp;RW</td></tr>
                        <tr><td>&nbsp;EN</td></tr>
                        <tr><td>&nbsp;D4</td></tr>
                        <tr><td>&nbsp;D5</td></tr>
                        <tr><td>&nbsp;D6</td></tr>
                        <tr><td>&nbsp;D7</td></tr>
                        </table>
                        <p><a href="caracteres.php"><em>Leia mais</em></a><a href="#"><em>&rarr;</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="caracteres.php"><img src="img/carac.png" alt="Image" class="overlay-image" data-overlaytext="Display Caracteres" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <h6>&nbsp;</h6>
                        <p>Display de 7 segmentos</p>
                        <table border="1">
                        <tr><td><b>&nbsp;Variáveis do arquivo Labvad.h&nbsp;</b></td></tr>
                        <tr><td>&nbsp;SEG_A</td></tr>
                        <tr><td>&nbsp;SEG_B</td></tr>
                        <tr><td>&nbsp;SEG_C</td></tr>
                        <tr><td>&nbsp;SEG_D</td></tr>
                        <tr><td>&nbsp;SEG_E</td></tr>
                        <tr><td>&nbsp;SEG_F</td></tr>
                        <tr><td>&nbsp;SEG_G</td></tr>
                        <tr><td>&nbsp;PD</td></tr>
                        </table>
                        <p><a href="sete.php"><em>Leia mais</em></a><a href="#"><em>&rarr;</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="sete.php"><img src="img/7.png" alt="Image" class="overlay-image" data-overlaytext="7 Segmentos" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <p>&nbsp;</p>
                        <p>LED RGB</p>
                        <table border="1">
                        <tr><td><b>&nbsp;Variáveis do arquivo Labvad.h&nbsp;</b></td></tr>
                        <tr><td>&nbsp;RGB_VM</td></tr>
                        <tr><td>&nbsp;RGB_VD</td></tr>
                        <tr><td>&nbsp;RGB_AZ</td></tr>
                        </table>
                        <p><a href="rgb.php"><em>Leia mais</em></a><a href="#"><em>&rarr;</em></a></p>
                        <h6>&nbsp;</h6>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="rgb.php"><img src="img/rgb.png" alt="Image" class="overlay-image" data-overlaytext="RGB" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <h6>&nbsp;</h6>
                        <p>Servo Motor</p>
                        <table border="1">
                        <tr><td><b>&nbsp;Variáveis do arquivo Labvad.h&nbsp;</b></td></tr>
                        <tr><td>&nbsp;SERVO</td></tr>
                        </table>
                        <p><a href="servo.php"><em>Leia mais</em></a><a href="#"><em>&rarr;</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="servo.php"><img src="img/servo.png" alt="Image" class="overlay-image" data-overlaytext="Servo Motor" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <h6>&nbsp;</h6>
                        <p>Relé e Motor DC</p>
                        <table border="1">
                        <tr><td><b>&nbsp;Variáveis do arquivo Labvad.h&nbsp;</b></td></tr>
                        <tr><td>&nbsp;RELE</td></tr>
                        <tr><td>&nbsp;MOTOR_DC</td></tr>
                        </table>
                        <p><a href="rele.php"><em>Leia mais</em></a><a href="#"><em>&rarr;</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="rele.php"><img src="img/rele.png" alt="Image" class="overlay-image" data-overlaytext="RELÉ" /></a>
                    <div class="overlay"></div>
                </div>
            </div>
            <div id="filterSection" data-perrow="4">
                <div class="row-fluid">
                    <div class="overlay-wrapper">
                        <div class="overlay"></div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="footer-meta">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </div>

<?php require_once 'app.include/footer.inc.php'; ?>
