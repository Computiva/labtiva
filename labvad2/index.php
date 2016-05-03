<?php
require_once 'app.ado/TConnection.class.php';

session_start();
$tela = '';
$acao = filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_STRING);
if (empty($acao)) {
    $acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);
}

/*
if ($acao == 'activufrj') {
    $txtEmail = isset($_POST['txtEmail']) ? $_POST['txtEmail'] : '';
    $txtSenha = isset($_POST['txtSenha']) ? $_POST['txtSenha'] : '';

    if (($txtEmail != '') && ($txtSenha != '')) {
        $sql       = $conn->prepare("SELECT 
                                        pessoas.id, 
                                        pessoas.nome, 
                                        pessoas.email, 
                                        pessoas.tipo,
                                        pessoas.senha,
                                        pessoas.recuperar_senha 
                                     FROM 
                                        pessoas 
                                     WHERE
                                        (pessoas.email = :email)
                                     LIMIT 1 ");
        $sql->bindParam(':email', $txtEmail);
        //$sql->bindParam(':senha', $txtSenha);
        $sql->execute();
        $resultado = $sql->fetchObject();
        if ($resultado) {
            if (($resultado->email == $txtEmail) && (($resultado->senha == $txtSenha) || ($resultado->recuperar_senha == $txtSenha))) {

                $_SESSION['id_usuario']   = $resultado->id;
                $_SESSION['nome_usuario'] = $resultado->nome;
                $_SESSION['tipo']         = $resultado->tipo;
                $_SESSION['id_sessao']    = rand(0, 2000) . '00' . session_id();

                // Experimento default
                $_SESSION['id_tipo']   = '1';
                $_SESSION['nome_tipo'] = 'Programação Arduíno';                
                // session_write_close();

                // header('Location: labvad.php');
                $r = array("erro" => 0,
                           "msg" => "Usuário autenticado!".$_SESSION['id_usuario'].$_SESSION['nome_usuario'].$_SESSION['tipo'].session_id());
                echo json_encode($r);
                exit;
            } 
            else {
                $r = array("erro" => 1,
                           "msg" => "Senha não confere!");
                echo json_encode($r);
                exit;
            }
        } 
        else {
            $r = array("erro" => 2,
                       "msg" => "Usuário não encontrado!");
            echo json_encode($r);
            exit;
        }
    }
    else {
        $r = array("erro" => 3,
                   "msg" => "Usuário ou senha não preenchidos!");
        echo json_encode($r);
        exit;
    }
}
*/

if ($acao == 'logar') {
    $txtEmail = isset($_POST['txtEmail']) ? $_POST['txtEmail'] : '';
    $txtSenha = isset($_POST['txtSenha']) ? $_POST['txtSenha'] : '';
    if (($txtEmail != '') && ($txtSenha != '')) {
        $sql       = $conn->prepare("SELECT 
                                        pessoas.id, 
                                        pessoas.nome, 
                                        pessoas.email, 
                                        pessoas.tipo,
                                        pessoas.senha,
                                        pessoas.recuperar_senha 
                                     FROM 
                                        pessoas 
                                     WHERE
                                        (pessoas.email = :email)
                                     LIMIT 1 ");
        $sql->bindParam(':email', $txtEmail);
        //$sql->bindParam(':senha', $txtSenha);
        $sql->execute();
        $resultado = $sql->fetchObject();
        if ($resultado) {
            if (($resultado->email == $txtEmail) && (($resultado->senha == MD5($txtSenha)) || ($resultado->recuperar_senha == MD5($txtSenha)))) {

                $_SESSION['id_usuario']   = $resultado->id;
                $_SESSION['nome_usuario'] = $resultado->nome;
                $_SESSION['tipo']         = $resultado->tipo;
                $_SESSION['id_sessao']    = rand(0, 2000) . '00' . session_id();

                // Experimento default
                $_SESSION['id_tipo']   = '1';
                $_SESSION['nome_tipo'] = 'Programação Arduíno';

                header('Location: labvad.php');
                exit;
            } 
            else {
                $tela = "Senha não confere!";
            }
        } 
        else {
            $tela = "Usuário não encontrado!";
        }
    }
}

else if ($acao == "cadastro") {
    $txtCadastroNome   = filter_input(INPUT_POST, 'txtCadastroNome', FILTER_SANITIZE_STRING);
    $txtCadastroEscola = filter_input(INPUT_POST, 'txtCadastroEscola', FILTER_SANITIZE_STRING);
    $txtCadastroEmail  = filter_input(INPUT_POST, 'txtCadastroEmail', FILTER_SANITIZE_STRING);
    $txtCadastroSenha  = filter_input(INPUT_POST, 'txtCadastroSenha', FILTER_SANITIZE_STRING);
    $txtCadastroConfSenha  = filter_input(INPUT_POST, 'txtCadastroConfSenha', FILTER_SANITIZE_STRING);

    if ($txtCadastroSenha != $txtCadastroConfSenha) {
        $tela = "Cadastro não realizado! Senha diferente da confirmação.";
    }
    else {
        if ((! empty($txtCadastroNome)) && (! empty($txtCadastroEscola)) && (! empty($txtCadastroEmail)) && (! empty($txtCadastroSenha))) {
            try {
                require_once 'app.ado/TConnection.class.php';
                $conn = TConnection::open();
                            
                // $novaSenha = rand(11998844, 78963214565);
                $novaSenha = $txtCadastroSenha;
                
                $sql  = $conn->prepare("SELECT pessoas.email FROM pessoas WHERE (pessoas.email = :email) LIMIT 1 ");
                $sql->bindParam(':email', $txtCadastroEmail);
                $sql->execute();		
                $resultado = $sql->fetchAll();	
                if (count($resultado) > 0) {
                    $tela = "Email já utilizado! Tente recuperar sua senha!";
                }
                else {                

                    $sql  = $conn->prepare("INSERT INTO pessoas (
                                                id, 
                                                nome,
                                                email,
                                                dt_cadastro,
                                                ativo,
                                                tipo,
                                                senha,
                                                escola
                                            )
                                            VALUES (
                                                NULL, 
                                                :nome,
                                                :email,
                                                NOW(),
                                                's',
                                                'u',
                                                MD5(:senha),
                                                :escola
                                            ) ");
                    $sql->bindParam(':nome', $txtCadastroNome);
                    $sql->bindParam(':email', $txtCadastroEmail);
                    $sql->bindParam(':senha', $novaSenha);
                    $sql->bindParam(':escola', $txtCadastroEscola);
                    $sql->execute();
                    $iId = $conn->lastInsertId();
                    if ($iId > 0) {
                        /*
                        $conteudo = http_build_query(array(
                                'email' => $txtCadastroEmail,
                                'senha' => $novaSenha,
                                'escola' => $txtCadastroEscola,
                                'nome' => $txtCadastroNome
                         ));

                        $context = stream_context_create(array(
                                'http' => array(
                                        'method'  => 'POST',
                                        'content' => $conteudo
                                )
                        ));

                        $resultado = @file_get_contents('http://labvad.com/enviarusuario.php', null, $context, -1, 40000);
                        */

                        $to      = $txtCadastroEmail;
                        $subject = 'LabVad - Cadastro de usuário';
                        $message = "Prezado $txtCadastroNome, seu cadastro no LabVad foi criado com sucesso. Sua senha é $novaSenha.";
                        $headers = 'From: noreply@nce.ufrj.br' . "\r\n" .
                            'X-Mailer: PHP/' . phpversion();

                        if (mail($to, $subject, $message, $headers)) {
                            $tela = "Solicitação de cadastro enviada com sucesso. Você receberá um email confirmando esta operação.";
                        }
                        else {
                            $tela = "Sua solicitação de cadastro foi processada mas ocorreu erro ao tentar enviar email.";
                        }
                        
                    }
                }
            }
            catch (Exception $e) {
                $tela = "Cadastro não realizado! Tente novamente em instantes!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
        <title>LabVad</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="set/js/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="set/js/bootstrap/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="set/css/font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" href="set/css/style.css" />
        <link href='http://fonts.googleapis.com/css?family=Economica:400,700,700italic' rel='stylesheet' type='text/css'>

    </head>
    <body>

        <!-- Home -->
        <div id="page1" class="page bgcolor center">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="caption"> 

                            <?php
                            if (!empty($tela)) {
                                echo '<div class="alert alert-danger">' . $tela . '</div>';
                            }
                            ?>
                            <h1><img src="set/img/ufrj.gif"></h1>
                            <h1>LabVad: Laboratório Virtual de Atividades Didáticas em Ciências e Robótica</h1>
                            <h1>
                                <p>Login
                                <div style="width: 570px">
                                    <form class="form-horizontal" role="form" name="formLogin" id="formLogin" method="post" action="?acao=logar">
                                        </p>
                                        </p>
                                        <div class="form-group">
                                            <label for="txtEmail" class="col-sm-2 control-label">Email:</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="txtEmail" id="txtEmail" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtSenha" class="col-sm-2 control-label">Senha:</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="txtSenha" id="txtSenha" placeholder="Senha">

                                            </div>
                                            <button class="btn-theme pull-left">Acessar</button>
                                            <a id="lkRecuperar" href="#" title="Recuperar senha" style="font: 13px Arial; color: #FFF; padding-left: 20px">Recuperar Senha</a>
                                            <a id="lkCadastrarNovo" href="#page5" title="Cadastro de novo usuário" style="font: 13px Arial; color: #FFF; padding-left: 20px">Cadastro</a>
                                            </h1>
                                        </div>
                                    </form>    
                                </div>
                        </div>

                    <!- Créditos -->
                    <img src="img/NCE.png"  />
                    <img src="img/PAIRG.png"  />
                    <img src="img/RNP.png"  />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="img/CAPES.png" width="100" />
                    </div>
                </div>
            </div>



            <!-- /Home-->

            <!-- LabVad-->
            <div id="page3" class="page center">
                <div class="container">
                    <div class="row">
                        <div class="span12">
                            <h1 class=""><span class="center">Siga três passos para usar o LabVad</span></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span4">
                            <div class="well well-large pricing">
                                <hr>
                                <h2>Faça seu cadastro</h2>
                                <div class="roundimg"><img src="set/img/cadastro.png" alt="" />
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <div class="span4">
                            <div class="well well-large pricing">
                                <hr>
                                <h2>Agende suas Aulas</h2>
                                <div class="roundimg"><img src="set/img/agenda.png" alt="" />
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <div class="span4">
                            <div class="well well-large pricing">
                                <hr>
                                <h2>Execute Experimentos</h2>
                                <div class="roundimg"><img src="set/img/aula.png" alt="" /></div>
                                <hr>
                            </div></div>
                    </div>

                    <div class="row vspace50">
                        <div class="span12">
                            <h3 class="center">Solicite seu cadastro no formulário abaixo</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div id="window2" class="window">
                <div class="container">
                    <div class="row">
                        <div class="span12">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /LabVad-->
            <!-- Contato -->
            
            <div id="page5" class="page">
                <div class="container">
                    <div class="row">
                        <div class="span12 center">
                            <h3><span><i class="icon-envelope-alt"></i>&nbsp;</span>Cadastro</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span8 center">
                            <form name="formCadastro" method="post" id="formCadastro" action="?acao=cadastro">
                                <div class="controls">
                                    <input id="txtCadastroNome" name="txtCadastroNome" type="text" placeholder="Nome do Usuário" class="span8" required />
                                </div>
                                <div class="controls">
                                    <input id="txtCadastroEscola" name="txtCadastroEscola" type="text" placeholder="Instituição" class="span8" required />
                                </div>
                                <div class="controls">
                                    <input id="txtCadastroEmail" name="txtCadastroEmail" type="email" placeholder="Email" class="span8" required />
                                </div>

                                <div class="controls">
                                    <input id="txtCadastroSenha" name="txtCadastroSenha" type="password" placeholder="Senha" class="span8" required />
                                </div>
                                <div class="controls">
                                    <input id="txtCadastroConfSenha" name="txtCadastroConfSenha" type="password" placeholder="Confirmação da Senha" class="span8" required />
                                </div>

                                <button id="formEnviarCadastro" class="btn-theme pull-left" type="submit" >Enviar</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            
            <!-- /Contato -->

            <footer>
                &copy;&nbsp;Copyright 2014 - GINAPE - NCE/UFRJ<br /><br />
                <a href="#"><img src="set/img/socials/32/Facebook.png" alt="" /></a>
                <a href="#"><img src="set/img/socials/32/Twitter.png" alt="" /></a>
                <a href="#"><img src="set/img/socials/32/Linkedin.png" alt="" /></a>
                <a href="#"><img src="set/img/socials/32/Pinterest.png" alt="" /></a>
                <a href="#"><img src="set/img/socials/32/Google+.png" alt="" /></a>
                <a href="#"><img src="set/img/socials/32/Youtube.png" alt="" /></a>
            </footer>

            <a id="scrollToTop"><i class="icon-caret-up"></i></a>


            <script src="set/js/jquery-1.10.0.min.js"></script>
            <script src="set/js/bootstrap/js/bootstrap.min.js"></script>
            <script src="set/js/script.js"></script>
            <script>
                $(document).ready(function () {
 
                    function validatePassword() {
                        if(password.value != confirm_password.value) {
                            //console.log("diferente");
                            confirm_password.setCustomValidity("Senha diferente da confirmação");
                        } else {
                            //console.log("igual");
                            confirm_password.setCustomValidity("");
                        }
                    }

                    var password = document.getElementById("txtCadastroSenha"),
                        confirm_password = document.getElementById("txtCadastroConfSenha");
                   
                    password.onchange = validatePassword;
                    confirm_password.onkeyup = validatePassword;


                    $("#lkRecuperar").on("click", function () {
                        var meuemail = $("#txtEmail").val();
                        if (meuemail === "") {
                            alert("Informe o email!");
                            $("#txtEmail").focus();
                        }
                        else {
                            $.post('recuperar_senha.php',
                                    {
                                        email: meuemail
                                    },
                            function (retorno) {
                                alert(retorno)
                            }
                            );
                        }
                    });

                    localStorage.setItem("nomeCodigo", "");
                    localStorage.setItem("codigo", "");
                    localStorage.setItem("comparaCodigo", "");
                    localStorage.setItem("alterado", "");
                    localStorage.setItem("idCodigo", "");
                });
            </script>
    </body>
</html>
