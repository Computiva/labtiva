/**
 * @license
 * Visual Blocks Language
 *
 * Copyright 2012 Google Inc.
 * https://developers.google.com/blockly/
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @fileoverview Generating Code for DBK special Blocks.
 * @author Rubens Queiroz
 */
'use strict';

goog.provide('Blockly.dbk.componentes');

goog.require('Blockly.Arduino');


//*******************************************************
//Inicializa Pinos etc.
//*******************************************************


/** 
//Definição das constantes lendo do arquivo de setup (pinosdb4k.ini)- //retirado para evitar algum  problema com o load da página.


// Carrega os valores dos pinos do arquivo pinos_arduino.ini 
//para as variáveis globais DB4k_pino_xxxxxx

//Define os pinos utilizados pelos LEDs
var pinoLedVermelho = DB4K_pino_LED_vermelho;
var pinoLedAmarelo = DB4K_pino_LED_amarelo;
var pinoLedVerde = DB4K_pino_LED_verde;
var pinoLedBranco = DB4K_pino_LED_branco;

//Define o pino utilizado pelo servo motor
var pinoServoMotor = DB4K_pino_Servo_Motor;

//define o pino utilizado pelo Modot DC (pino analógico)

var pinoMotorDC = DB4K_pino_MotorDC;

//Define pinos e outras variáveis utilizadas para o display LCD

var nomeLCD = 'lcd';
var nomeSerial = 'Serial'
var velocidadeSerial = DB4K_velocidade_serial;

var pino_rs = DB4K_pino_rs;
var pino_rw = DB4K_pino_rw;
var pino_enable = DB4K_pino_enable;
var pino_dados_4 = DB4K_pino_dados_4;
var pino_dados_5 = DB4K_pino_dados_5; 
var pino_dados_6 = DB4K_pino_dados_6;
var pino_dados_7 = DB4K_pino_dados_7;

var tamanho_linha_lcd = DB4K_tamanho_linha_LCD;
var numero_linhas_lcd = '2';

//Define pinos no Display de 7 seguimentos

var pino_seguimento_F = DB4K_pino_seguimento_F;
var pino_seguimento_G = DB4K_pino_seguimento_G;
var pino_seguimento_E = DB4K_pino_seguimento_E;
var pino_seguimento_D = DB4K_pino_seguimento_D;
var pino_seguimento_A = DB4K_pino_seguimento_A;
var pino_seguimento_B = DB4K_pino_seguimento_B;
var pino_seguimento_C = DB4K_pino_seguimento_D;
var pino_Ponto_Decimal = DB4K_pino_ponto_decimal;

**/


//Define os pinos utilizados pelos LEDs
var pinoLedVermelho = 'LED_VM1';
var pinoLedAmarelo = 'LED_AM1';
var pinoLedVerde = 'LED_VD1';
var pinoLedAzul = 'LED_AZ1';


//Define o pino utilizado pelo servo motor
var pinoServoMotor = 'SERVO';

//define o pino utilizado pelo Modot DC (pino analógico)

var pinoMotorDC = 'MOTOR_DC';

//Define pinos e outras variáveis utilizadas para o display LCD

var nomeLCD = 'lcd';
var nomeSerial = 'Serial';
var velocidadeSerial = '9600';

var pino_rs = 'RS';
var pino_rw = 'RW';
var pino_enable = 'EN';
var pino_dados_4 = 'D4';
var pino_dados_5 = 'D5'; 
var pino_dados_6 = 'D6';
var pino_dados_7 = 'D7';

var tamanho_linha_lcd = '16';
var numero_linhas_lcd = '2';

//Define pinos do Display de 7 seguimentos

var pino_seguimento_F = 'SEG_F';
var pino_seguimento_G = 'SEG_G';
var pino_seguimento_E = 'SEG_E';
var pino_seguimento_D = 'SEG_D';
var pino_seguimento_A = 'SEG_A';
var pino_seguimento_B = 'SEG_B';
var pino_seguimento_C = 'SEG_C';
var pino_Ponto_Decimal = 'PD';

//Define pinos do LED RGB

var pino_rgb_vermelho = 'RGB_VM';
var pino_rgb_verde = 'RGB_VD';
var pino_rgb_azul = 'RGB_AZ';


//*******************************************************
//Acende LED
//*******************************************************

Blockly.Arduino['acender_led'] = function(block) {
  var colour_cor_led = block.getFieldValue('cor_led');
  
//Troca o Valor Hexadecimal da Cor pelo "pino" Referente à cor
	switch(colour_cor_led) {
		case '#ff0000':
			var pinKey = pinoLedVermelho;
			break;
		case '#00ff00':
			var pinKey = pinoLedVerde;
			break;
		case '#0000ff':
			var pinKey = pinoLedAzul;
			break;		
		case '#ffff00':
			var pinKey = pinoLedAmarelo;
			break;		
	} 
  // TODO: Assemble Arduino into code variable.
  var stateOutput = 'HIGH';

  Blockly.Arduino.reservePin(
      block, pinKey, Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');

  var pinSetupCode = 'pinMode(' + pinKey + ', OUTPUT);';
  Blockly.Arduino.addSetup('io_' + pinKey, pinSetupCode, false);

  var code = 'digitalWrite(' + pinKey + ',' + stateOutput + ');\n';
  return code;
 
};


//*******************************************************
//Apaga LED
//*******************************************************

Blockly.Arduino['apagar_led'] = function(block) {
  var colour_cor_led = block.getFieldValue('cor_led');
  
//Troca o Valor Hexadecimal da Cor pelo "pino" Referente à cor
	switch(colour_cor_led) {
		case '#ff0000':
			var pinKey = pinoLedVermelho;
			break;
		case '#00ff00':
			var pinKey = pinoLedVerde;
			break;
		case '#0000ff':
			var pinKey = pinoLedAzul;
			break;		
		case '#ffff00':
			var pinKey = pinoLedAmarelo;
			break;		
	} 
  // TODO: Assemble Arduino into code variable.
  var stateOutput = 'LOW';

  Blockly.Arduino.reservePin(
      block, pinKey, Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');

  var pinSetupCode = 'pinMode(' + pinKey + ', OUTPUT);';
  Blockly.Arduino.addSetup('io_' + pinKey, pinSetupCode, false);

  var code = 'digitalWrite(' + pinKey + ',' + stateOutput + ');\n';
  return code;
};


//*******************************************************
//Gira Motor DC
//*******************************************************

Blockly.Arduino['girar_motor'] = function(block) {
  var dropdown_velocidade_motor = block.getFieldValue('velocidade_motor');
  var pinKey = pinoMotorDC;
  
  switch(dropdown_velocidade_motor) {
		case 'low':
			var velocidade = '100';
			break;
		case 'middle':
			var velocidade = '170';
			break;
		case 'high':
			var velocidade = '250';
			break;	
  }
  var value_num = velocidade;


  Blockly.Arduino.reservePin(
      block, pinKey, Blockly.Arduino.PinTypes.OUTPUT, 'Analogue Write');

  var pinSetupCode = 'pinMode(' + pinKey + ', OUTPUT);';
  Blockly.Arduino.addSetup('io_' + pinKey, pinSetupCode, false);

  var code = 'analogWrite(' + pinKey + ',' + value_num + ');\n'
  return code;
  
  

};


//*******************************************************
//Para Motor DC
//*******************************************************


Blockly.Arduino['parar_motor'] = function(block) {
  // TODO: Assemble Arduino into code variable.
  	var pinKey = pinoMotorDC;
	var value_num = '0';
	
  Blockly.Arduino.reservePin(
      block, pinKey, Blockly.Arduino.PinTypes.OUTPUT, 'Analogue Write');

  var pinSetupCode = 'pinMode(' + pinKey + ', OUTPUT);';
  Blockly.Arduino.addSetup('io_' + pinKey, pinSetupCode, false);

  var code = 'analogWrite(' + pinKey + ',' + value_num + ');\n'
  return code;
  
};


//*******************************************************
//Move Servo Motor
//*******************************************************

Blockly.Arduino['mover_servomotor'] = function(block) {
  var pinKey = pinoServoMotor;
  var dropdown_posicao_ponteiro_servo = block.getFieldValue('posicao_ponteiro_servo');
  var angulo = dropdown_posicao_ponteiro_servo;
  var servoName = 'myServo_' + pinKey;

  Blockly.Arduino.reservePin(
      block, pinKey, Blockly.Arduino.PinTypes.SERVO, 'Servo Write');

  Blockly.Arduino.addInclude('servo', '#include <Servo.h>');
  Blockly.Arduino.addDeclaration('servo_' + pinKey, 'Servo ' + servoName + ';');

  var setupCode = servoName + '.attach(' + pinKey + ');';
  Blockly.Arduino.addSetup('servo_' + pinKey, setupCode, true);

  var code = servoName + '.write(' + angulo + ');\n';
  return code;

};

 
//*******************************************************
//Escreve no LCD
//******************************************************* 

Blockly.Arduino['escrever_lcd'] = function(block) {
  var texto = block.getFieldValue('texto');
  var numero_linha = block.getFieldValue('numero_linha');
  var lcdName = nomeLCD;
 
  // TODO: Assemble Arduino into code variable.

  
  switch(numero_linha) {
	case '1':
		var posicao_cursor = lcdName + '.setCursor(0,0);\n';
		break;
	case '2':
		var posicao_cursor = lcdName + '.setCursor(0,1);\n';
		break;		
  } 
   
  Blockly.Arduino.addInclude('lcd', '#include <LiquidCrystal.h>');
  Blockly.Arduino.addDeclaration('lcd','LiquidCrystal ' + lcdName + '(' 
  + pino_rs + ',' + pino_rw + ',' + pino_enable + ',' + pino_dados_4 + ',' + pino_dados_5 + ',' + pino_dados_6 + ',' + pino_dados_7 +
  ');');
  
  var SetupCode1 = nomeSerial + '.begin(' + velocidadeSerial + ');';
  var SetupCode2 = lcdName + '.begin(' + tamanho_linha_lcd + ',' + numero_linhas_lcd +');'; 
  Blockly.Arduino.addSetup('lcd',SetupCode1, true);
  Blockly.Arduino.addSetup('lcd',SetupCode2, true);
  

  var code = posicao_cursor + lcdName + '.print("' + texto + '");\n';
  return code;
  
};


//*******************************************************
//Apaga o LCD
//*******************************************************


Blockly.Arduino['limpar_lcd'] = function(block) {
  // TODO: Assemble JavaScript into code variable.
  var lcdName = nomeLCD
 
  Blockly.Arduino.addInclude('lcd', '#include <LiquidCrystal.h>');
  Blockly.Arduino.addDeclaration('lcd','LiquidCrystal ' + lcdName + '(' 
  + pino_rs + ',' + pino_rw + ',' + pino_enable + ',' + pino_dados_4 + ',' + pino_dados_5 + ',' + pino_dados_6 + ',' + pino_dados_7 +
  ');');
  
  var SetupCode1 = nomeSerial + '.begin(' + velocidadeSerial + ');';
  var SetupCode2 = lcdName + '.begin(' + tamanho_linha_lcd + ',' + numero_linhas_lcd +');'; 
  Blockly.Arduino.addSetup('lcd',SetupCode1, true);
  Blockly.Arduino.addSetup('lcd',SetupCode2, true);
  
  var code = lcdName +'.clear();\n';
  return code;

  };
  
//*******************************************************
// Escreve no Display de 7 Seguimentos
//*******************************************************

Blockly.Arduino['escrever_display_7s']=function(block){
var numeroDisplay7s =block.getFieldValue('numerosD7S');
	switch(numeroDisplay7s) {
		case '0':
			var outF = 'HIGH';
			var outG = 'LOW';
			var outE = 'HIGH';
			var outD = 'HIGH';
			var outA = 'HIGH';
			var outB = 'HIGH';
			var outC = 'HIGH';
			var outPD = 'LOW';
			break;
	    case '1':
			var outF = 'LOW';
			var outG = 'LOW';
			var outE = 'LOW';
			var outD = 'LOW';
			var outA = 'LOW';
			var outB = 'HIGH';
			var outC = 'HIGH';
			var outPD = 'LOW';
			break;
		case '2':
			var outF = 'LOW';
			var outG = 'HIGH';
			var outE = 'HIGH';
			var outD = 'HIGH';
			var outA = 'HIGH';
			var outB = 'HIGH';
			var outC = 'LOW';
			var outPD = 'LOW';
			break;
		case '3':
			var outF = 'LOW';
			var outG = 'HIGH';
			var outE = 'LOW';
			var outD = 'HIGH';
			var outA = 'HIGH';
			var outB = 'HIGH';
			var outC = 'HIGH';
			var outPD = 'LOW';
			break;
		case '4':
			var outF = 'HIGH';
			var outG = 'HIGH';
			var outE = 'LOW';
			var outD = 'LOW';
			var outA = 'LOW';
			var outB = 'HIGH';
			var outC = 'HIGH';
			var outPD = 'LOW';
			break;
		case '5':
			var outF = 'HIGH';
			var outG = 'HIGH';
			var outE = 'LOW';
			var outD = 'HIGH';
			var outA = 'HIGH';
			var outB = 'LOW';
			var outC = 'HIGH';
			var outPD = 'LOW';
			break;
		case '6':
			var outF = 'HIGH';
			var outG = 'HIGH';
			var outE = 'HIGH';
			var outD = 'HIGH';
			var outA = 'HIGH';
			var outB = 'LOW';
			var outC = 'HIGH';
			var outPD = 'LOW';
			break;
		case '7':
			var outF = 'LOW';
			var outG = 'LOW';
			var outE = 'LOW';
			var outD = 'LOW';
			var outA = 'HIGH';
			var outB = 'HIGH';
			var outC = 'HIGH';
			var outPD = 'LOW';
			break;
		case '8':
			var outF = 'HIGH';
			var outG = 'HIGH';
			var outE = 'HIGH';
			var outD = 'HIGH';
			var outA = 'HIGH';
			var outB = 'HIGH';
			var outC = 'HIGH';
			var outPD = 'LOW';
			break
		case '9':
			var outF = 'HIGH';
			var outG = 'HIGH';
			var outE = 'LOW';
			var outD = 'HIGH';
			var outA = 'HIGH';
			var outB = 'HIGH';
			var outC = 'HIGH';
			var outPD = 'LOW';
			break;
	}

	
  // TODO: Assemble Arduino into code variable.
  //Setup
Blockly.Arduino.reservePin(
      block, pino_seguimento_F , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_G , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_E , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_D , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_A , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_B , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_C , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_Ponto_Decimal, Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');

  var pinSetupCodeF = 'pinMode(' + pino_seguimento_F + ', OUTPUT);';
  var pinSetupCodeG = 'pinMode(' + pino_seguimento_G + ', OUTPUT);';
  var pinSetupCodeE = 'pinMode(' + pino_seguimento_E + ', OUTPUT);';
  var pinSetupCodeD = 'pinMode(' + pino_seguimento_D + ', OUTPUT);';
  var pinSetupCodeA = 'pinMode(' + pino_seguimento_A + ', OUTPUT);';
  var pinSetupCodeB = 'pinMode(' + pino_seguimento_B + ', OUTPUT);';
  var pinSetupCodeC = 'pinMode(' + pino_seguimento_C + ', OUTPUT);';
  var pinSetupCodePD = 'pinMode(' + pino_Ponto_Decimal + ', OUTPUT);';
  
									
  Blockly.Arduino.addSetup('io_' + pino_seguimento_F, pinSetupCodeF, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_G, pinSetupCodeG, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_E, pinSetupCodeE, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_D, pinSetupCodeD, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_A, pinSetupCodeA, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_B, pinSetupCodeB, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_C, pinSetupCodeC, true);
  Blockly.Arduino.addSetup('io_' + pino_Ponto_Decimal, pinSetupCodePD, true);

  
  //Código
  var code = 'digitalWrite(' + pino_seguimento_F + ',' + outF + ');\n'
			+ 'digitalWrite(' + pino_seguimento_G + ',' + outG + ');\n'
			+ 'digitalWrite(' + pino_seguimento_E + ',' + outE + ');\n'
			+ 'digitalWrite(' + pino_seguimento_D + ',' + outD + ');\n'
			+ 'digitalWrite(' + pino_seguimento_A + ',' + outA + ');\n'
			+ 'digitalWrite(' + pino_seguimento_B + ',' + outB + ');\n'
			+ 'digitalWrite(' + pino_seguimento_C + ',' + outC + ');\n'
			+ 'digitalWrite(' + pino_Ponto_Decimal + ',' + outPD + ');\n';
			
  return code;
 
};

//*******************************************************
// Linpa Display de 7 Seguimentos
//*******************************************************

Blockly.Arduino['limpar_display_7s']=function(block){

			var outF = 'LOW';
			var outG = 'LOW';
			var outE = 'LOW';
			var outD = 'LOW';
			var outA = 'LOW';
			var outB = 'LOW';
			var outC = 'LOW';
			var outPD = 'LOW';

	
  // TODO: Assemble Arduino into code variable.
  //Setup
Blockly.Arduino.reservePin(
      block, pino_seguimento_F , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_G , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_E , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_D , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_A , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_B , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_seguimento_C , Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');
Blockly.Arduino.reservePin(
      block, pino_Ponto_Decimal, Blockly.Arduino.PinTypes.OUTPUT, 'Digital Write');

  var pinSetupCodeF = 'pinMode(' + pino_seguimento_F + ', OUTPUT);';
  var pinSetupCodeG = 'pinMode(' + pino_seguimento_G + ', OUTPUT);';
  var pinSetupCodeE = 'pinMode(' + pino_seguimento_E + ', OUTPUT);';
  var pinSetupCodeD = 'pinMode(' + pino_seguimento_D + ', OUTPUT);';
  var pinSetupCodeA = 'pinMode(' + pino_seguimento_A + ', OUTPUT);';
  var pinSetupCodeB = 'pinMode(' + pino_seguimento_B + ', OUTPUT);';
  var pinSetupCodeC = 'pinMode(' + pino_seguimento_C + ', OUTPUT);';
  var pinSetupCodePD = 'pinMode(' + pino_Ponto_Decimal + ', OUTPUT);';
  
									
  Blockly.Arduino.addSetup('io_' + pino_seguimento_F, pinSetupCodeF, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_G, pinSetupCodeG, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_E, pinSetupCodeE, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_D, pinSetupCodeD, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_A, pinSetupCodeA, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_B, pinSetupCodeB, true);
  Blockly.Arduino.addSetup('io_' + pino_seguimento_C, pinSetupCodeC, true);
  Blockly.Arduino.addSetup('io_' + pino_Ponto_Decimal, pinSetupCodePD, true);

  
  //Código
  var code = 'digitalWrite(' + pino_seguimento_F + ',' + outF + ');\n'
			+ 'digitalWrite(' + pino_seguimento_G + ',' + outG + ');\n'
			+ 'digitalWrite(' + pino_seguimento_E + ',' + outE + ');\n'
			+ 'digitalWrite(' + pino_seguimento_D + ',' + outD + ');\n'
			+ 'digitalWrite(' + pino_seguimento_A + ',' + outA + ');\n'
			+ 'digitalWrite(' + pino_seguimento_B + ',' + outB + ');\n'
			+ 'digitalWrite(' + pino_seguimento_C + ',' + outC + ');\n'
			+ 'digitalWrite(' + pino_Ponto_Decimal + ',' + outPD + ');\n';
			
  return code;
 
};


//*******************************************************
// LED RGB
//*******************************************************

Blockly.Arduino['led_rgb'] = function(block) {
var colour_luzvermelha = block.getFieldValue('luzVermelha');
var colour_luzverde = block.getFieldValue('luzVerde');
var colour_luzazul = block.getFieldValue('luzAzul');

//Define se a cor vai estar acesa ou apagada

switch(colour_luzvermelha) {
		case '#ff0000':
			var val_luzvermelha = '255';
			break;
	    case '#ffffff':
			var val_luzvermelha = '0';
			break;
}

switch(colour_luzverde) {
		case '#00ff00':
			var val_luzverde = '255';
			break;
	    case '#ffffff':
			var val_luzverde = '0';
			break;
}

switch(colour_luzazul) {
		case '#0000ff':
			var val_luzazul = '255';
			break;
	    case '#ffffff':
			var val_luzazul = '0';
			break;
}


// TODO: Assemble Arduino into code variable.
  
//SETUP
  
Blockly.Arduino.reservePin(
      block, pino_rgb_vermelho, Blockly.Arduino.PinTypes.OUTPUT, 'Analog Write');
Blockly.Arduino.reservePin(
      block, pino_rgb_verde, Blockly.Arduino.PinTypes.OUTPUT, 'Analog Write');
Blockly.Arduino.reservePin(
      block, pino_rgb_azul , Blockly.Arduino.PinTypes.OUTPUT, 'Analog Write');
  
  
  var pinSetupCodeVermelho = 'pinMode(' + pino_rgb_vermelho + ', OUTPUT);';
  var pinSetupCodeVerde = 'pinMode(' + pino_rgb_verde + ', OUTPUT);';
  var pinSetupCodeAzul = 'pinMode(' + pino_rgb_azul + ', OUTPUT);';

									
  Blockly.Arduino.addSetup('io_' + pino_rgb_vermelho, pinSetupCodeVermelho, false);
  Blockly.Arduino.addSetup('io_' + pino_rgb_verde, pinSetupCodeVerde, false);
  Blockly.Arduino.addSetup('io_' + pino_rgb_azul, pinSetupCodeAzul, false);

   //Código
 
  var code = 'analogWrite(' + pino_rgb_vermelho + ',' + val_luzvermelha + ');\n'
			+ 'analogWrite(' + pino_rgb_verde + ',' + val_luzverde  + ');\n'
			+ 'analogWrite(' + pino_rgb_azul + ',' + val_luzazul  + ');\n';

 return code;
 
};

