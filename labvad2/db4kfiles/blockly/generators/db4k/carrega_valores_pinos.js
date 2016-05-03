
var requestResult="";
var vetorParametros=null;
var DB4K_pino_LED_verde= null;
var DB4K_pino_LED_amarelo=null;
var DB4K_pino_LED_vermelho=null;
var DB4K_pino_LED_branco=null;
var DB4K_pino_Servo_Motor=null;
var DB4K_pino_MotorDC=null;

var DB4K_pino_rs=null;
var DB4K_pino_rw=null;
var DB4K_pino_enable=null;
var DB4K_pino_dados_4=null;
var DB4K_pino_dados_5=null;
var DB4K_pino_dados_6=null;
var DB4K_pino_dados_7=null;
var DB4K_tamanho_linha_LCD=null;
var DB4K_velocidade_serial=null;

var DB4K_pino_seguimento_F=null;
var DB4K_pino_seguimento_G=null;
var DB4K_pino_seguimento_E=null;
var DB4K_pino_seguimento_D=null;
var DB4K_pino_seguimento_A=null;
var DB4K_pino_seguimento_B=null;
var DB4K_pino_seguimento_C=null;
var DB4K_pino_ponto_decimal=null;
	
	
	
function serviceCall(requestedURL,functionReturn){
   requestResult="";
   ajaxCaller(requestedURL,functionReturn);
}

function ajaxCaller(requestedURL,functionReturn){
    var target=requestedURL;//+"&rnd="+Math.random();
    var ajaxRequest;  // The variable that makes Ajax possible!
    try{
        ajaxRequest = new XMLHttpRequest(); // Opera 8.0+, Firefox, Safari
    }catch(e){
        try{
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer Browsers
        }catch(e) {
            try{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(e){
                alert("Your browser broke!"); // Something went wrong
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function(){
        if(ajaxRequest.readyState == 4){
            var result=ajaxRequest.responseText;
            requestResult=result;
            eval(functionReturn);
			carrega_variaveis();
    }
    }
    //send data
    try{
       var parameters=false;
        var url=target;
        if(target.indexOf("?")!=-1){
            var data=target.split("?");
            url=data[0];
            parameters=data[1];
        }
        ajaxRequest.open("GET",url, true);
		//ajaxRequest.open("GET",url, false);
        ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajaxRequest.setRequestHeader("Content-length", parameters.length);
        ajaxRequest.setRequestHeader("Connection", "close");
        ajaxRequest.send(parameters);
		//console.log('ajax novo');
		//console.log(target);
    }catch(e){
      window.alert(e);
    }
}

//*****************************************************************
	
	function carrega_valores_pinos_arduino(){	
	    busca_paramentros();
	};
	
	function busca_paramentros(){
		console.log('busca parâmetros');
		//serviceCall("http://localhost:8000/blockly/generators/dbk/pinos_arduino.txt","inicializa_variaveis_valores_pinos()");
		serviceCall("pinos_db4k.ini","inicializa_variaveis_valores_pinos()");		
	};
	
	function inicializa_variaveis_valores_pinos(){
		vetorParametros=requestResult.split(";");
	};
	
	function trim(x) {
		return x.replace(/^\s+|\s+$/gm,'');
	};
	
	function buscaParametro(nomeParametro){
		if(vetorParametros!=null){
			for(i=0;i<vetorParametros.length;i++){
				var valores=vetorParametros[i].split("=");
				if(trim(valores[0]) == nomeParametro){
					return valores[1];
				}
			}
		}else{
			window.alert("Dados não carregados pela função ajax.");
		}
	};
	
	function carrega_variaveis(){
		DB4K_pino_LED_verde=buscaParametro("pino_LED_verde");
		DB4K_pino_LED_amarelo=buscaParametro("pino_LED_amarelo");
		DB4K_pino_LED_vermelho=buscaParametro("pino_LED_vermelho");
		DB4K_pino_LED_branco=buscaParametro("pino_LED_branco");	
		DB4K_pino_Servo_Motor=buscaParametro("pino_Servo_Motor");
		DB4K_pino_MotorDC=buscaParametro("pino_MotorDC");
		DB4K_pino_rs=buscaParametro("pino_rs");
		DB4K_pino_rw=buscaParametro("pino_rw");
		DB4K_pino_enable=buscaParametro("pino_enable");
		DB4K_pino_dados_4=buscaParametro("pino_dados_4");
		DB4K_pino_dados_5=buscaParametro("pino_dados_5");
		DB4K_pino_dados_6=buscaParametro("pino_dados_6");
		DB4K_pino_dados_7=buscaParametro("pino_dados_7");
		DB4K_tamanho_linha_LCD=buscaParametro("tamanho_linha_LCD");
		DB4K_velocidade_serial=buscaParametro("velocidade_serial");	
		
		DB4K_pino_seguimento_F=buscaParametro("pino_seguimento_F");	
		DB4K_pino_seguimento_G=buscaParametro("pino_seguimento_G");	
		DB4K_pino_seguimento_E=buscaParametro("pino_seguimento_E");	
		DB4K_pino_seguimento_D=buscaParametro("pino_seguimento_D");	
		DB4K_pino_seguimento_A=buscaParametro("pino_seguimento_A");	
		DB4K_pino_seguimento_B=buscaParametro("pino_seguimento_B");	
		DB4K_pino_seguimento_C=buscaParametro("pino_seguimento_C");	
		DB4K_pino_ponto_decimal=buscaParametro("pino_ponto_decimal");	
		
		
		console.log("fim js:"+DB4K_pino_LED_verde);
		console.log("fim js:"+DB4K_pino_LED_amarelo);
		console.log("fim js:"+DB4K_pino_LED_vermelho);
		console.log("fim js:"+DB4K_pino_LED_branco);
		console.log("fim js:"+DB4K_pino_Servo_Motor);
		console.log("fim js:"+DB4K_pino_MotorDC);
		console.log("fim js:"+DB4K_pino_rs);
		console.log("fim js:"+DB4K_pino_rw);
		console.log("fim js:"+DB4K_pino_enable);
		console.log("fim js:"+DB4K_pino_dados_4);
		console.log("fim js:"+DB4K_pino_dados_5);
		console.log("fim js:"+DB4K_pino_dados_6);
		console.log("fim js:"+DB4K_pino_dados_7);
		console.log("fim js:"+DB4K_tamanho_linha_LCD);
		console.log("fim js:"+DB4K_velocidade_serial);
		console.log("fim js:"+DB4K_pino_seguimento_F);
		console.log("fim js:"+DB4K_pino_seguimento_G);
		console.log("fim js:"+DB4K_pino_seguimento_E);
		console.log("fim js:"+DB4K_pino_seguimento_D);
		console.log("fim js:"+DB4K_pino_seguimento_A);
		console.log("fim js:"+DB4K_pino_seguimento_B);
		console.log("fim js:"+DB4K_pino_seguimento_C);
		console.log("fim js:"+DB4K_pino_ponto_decimal);


};

