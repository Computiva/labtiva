<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>LabVad - Laboratório Virtual de Atividades Didáticas com Robótica</title>

    <script type="text/javascript">
    $(function() {	
        // $( "#fake_form" ).submit();
        
	    $.ajax({url: "index.php",
		    type: "POST",
		    // async: false,
		    // crossDomain: true,
		    data: {"txtEmail": "<?= $_POST['txtEmail'] ?>", 
                   "txtSenha": "<?= $_POST['txtSenha'] ?>",
                   "acao": "activufrj"},
		    success: function(str_response) {
         		response = JSON && JSON.parse(str_response) || $.parseJSON(str_response);
         		alert ("retornou erro="+response.erro);
		
			    if (response.erro>0) {
				    alert ("msg="+response.msg);
			    }
			    else {
				    alert ("msg="+response.msg);

				    // abre aba com LABVAD_URL
				    // window.open("{{LABVAD_URL}}");
				    location.href = "labvad.php";
				    //self.close();
			    }
		    },
		    error: function() {
			    alert ("ERRO...");
		    }
	    });

    });
    </script> 

</head>

<body>
    <p>LabVad - Laboratório Virtual de Atividades Didáticas com Robótica</p>

    <!-- {% if MSG %} <div class="tnmMSG">{{ MSG }}</div> <br/>{% end %} -->

    <form name="login_labvad" class="form-horizontal" role="form" name="formLogin" id="formLogin" method="post" action="http://localhost:8888/task/labvad">
        <div class="form-group">
            <label for="txtEmail" class="col-sm-2 control-label">Email:</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="txtEmail" id="txtEmail" placeholder="Email" value="<?= $_POST['txtEmail'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txtSenha" class="col-sm-2 control-label">Senha:</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="txtSenha" id="txtSenha" placeholder="Senha">
            </div>

            <a id="lkRecuperar" href="#" title="Recuperar senha" style="font: 13px Arial; color: #FFF; padding-left: 20px">Recuperar Senha</a>
            <a id="lkCadastrarNovo" href="#page5" title="Cadastro de novo usuário" style="font: 13px Arial; color: #FFF; padding-left: 20px">Cadastro</a>
        </div>
        <input type="submit" name="Fazer Login no Labvad"/>
    </form>   
 
</body>
</html>

