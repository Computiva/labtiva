<?php

    if ((isset($_POST)) && ($_POST)) {
        try {
            $txtEmail    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $txtEscola   = filter_input(INPUT_POST, 'escola', FILTER_SANITIZE_STRING);
            $txtSenha    = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
            $txtNome     = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
            
            //Enviando o email
            $msg = '';	  
	    if (PHP_OS == "Linux") {
	      $quebra_linha = "\n"; //Se for Linux
	    }
	    else if (PHP_OS == "WINNT") {
	      $quebra_linha = "\r\n"; // Se for Windows
	    }
	    
	    //define os dados do remetente (deve ser um e-mail do seu domínio conforme determina a RFC 822)
	    $email_from = $txtEmail;
	     
	    //pego os dados enviados pelo formulário
	    $nome_para = 'Acesso ao LabVad!';
	    $email     = 'leonardolp@gmail.com,' . $txtEmail; //,prasouza@gmail.com,labvadppgi@gmail.com,
            $mensagem  = "<p>Olá {$txtNome}! Os seus dados para acessar o LabVad são <b>{$txtEmail}</b> é <b>{$txtSenha}</b></p>";
	    $assunto   = 'Primeiro acesso!';
	     
	    //formato o campo da mensagem
	    $mensagem = wordwrap($mensagem, 50, "<br />", 1);
	     
	    $headers = 'MIME-Version: 1.0' . $quebra_linha;
	    $headers .= 'Content-type: text/html; charset=iso-8859-1' . $quebra_linha;
	    $headers .= 'From: ' . $email_from . $quebra_linha;
	    
	    $mensagem = '<div style="width: 90%; border: 2px solid #0086c6; padding: 5px; margin: 5px auto;"><h3>' . $nome . '</h3>' . $mensagem . '</div>';
	     
	    //envia o email sem anexo
	    mail($email, $assunto, $mensagem, $headers);
	    echo "Email enviado! Verifique sua caixa de entrada!!";
        }
        catch (Exception $e) {
            
        }
        
        
        exit;
    }
?>