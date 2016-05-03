/**
 * @license
 * Visual Blocks Editor
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
 * @fileoverview dbkBlocks On Blockly.
 * @author Rubens Queiroz
 */
'use strict';

goog.provide('Blockly.Blocks.dbk');

goog.require('Blockly.Blocks');


Blockly.FieldColour.COLOURS = ['#0f0','#ff0','#f00','#00f'];
Blockly.FieldColour.COLUMNS = 4;

var cor_led = 156;
var cor_ledRGB = 140;
var cor_motor = 196;
var cor_servo = 174;
var cor_lcd = 328;
var cor_7Seg = 344; 


Blockly.Blocks['acender_led'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_led);
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/led_on.png", 40, 40, "*"))
        .appendField("Acender o LED")
        .appendField(new Blockly.FieldColour("#ff0000"), "cor_led");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('Acende o LED com a cor indicada.');
  }
};

Blockly.Blocks['apagar_led'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_led);
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/led_off_long.png", 48, 40, "*"))
        .appendField("Apagar o LED")
        .appendField(new Blockly.FieldColour("#ff0000"), "cor_led");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('Apaga o LED com a cor indicada.');
  }
};

Blockly.Blocks['girar_motor'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_motor);
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/motor_move_2.png", 40, 40, "*"))
        .appendField("Girar Motor")
        .appendField(new Blockly.FieldDropdown([["Devagar", "low"], ["Velocidade Média", "middle"], ["Rápido", "high"]]), "velocidade_motor");    
	this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('Faz o motor DC girar na velocidade indicada.');
  }
};

Blockly.Blocks['parar_motor'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_motor);
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/helice.png", 40, 40, "*"))
		.appendField("Parar Motor")
		.appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/invisible.png", 69, 40, "*"));
	this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('Faz o Motor DC  parar');
  }
};

Blockly.Blocks['mover_servomotor'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_servo);
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/servo_move.png", 40, 40, "*"))
        .appendField("Mover Servo Motor para")
        .appendField(new Blockly.FieldDropdown([["0", "0"], ["30", "30"],["60", "60"],["90", "90"], ["120", "120"], ["150", "150"],["180", "180"]]), "posicao_ponteiro_servo")
		.appendField("graus");
	this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('Faz o ponteiro do Servo Motor mover-se para a posição indicada');
  }
};


Blockly.Blocks['escrever_lcd'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_lcd);
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/lcd4.png", 40, 40, "*"))
        .appendField("Escrever")
        .appendField(new Blockly.FieldTextInput("Ola Mundo"), "texto")
        .appendField("na")
        .appendField(new Blockly.FieldDropdown([["linha 1", "1"], ["linha 2", "2"]]), "numero_linha");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('Escreve um texto no display de LCD na linha indicada (Máximo de 16 letras e espaços em cada linha)');
  }
};

Blockly.Blocks['limpar_lcd'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_lcd);
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/limpar_lcd4.png", 40, 40, "*"))
		.appendField("Limpar Display LCD")
		.appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/invisible.png", 63, 40, "*"));
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('Limpa o texto das duas linhas do discplay de LCD');
  }
};


Blockly.Blocks['escrever_display_7s']={
init:function(){
this.setHelpUrl('http://www.example.com/');
this.setColour(cor_7Seg);
this.appendDummyInput()
.appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/display7SegOn.png", 40, 40, "*"))
.appendField("Escrever ")
.appendField(new Blockly.FieldDropdown([["0","0"],["1","1"],["2","2"],["3","3"],["4","4"],["5","5"],["6","6"],["7","7"],["8","8"],["9","9"]]),"numerosD7S")
.appendField("no Display de 7 Segmentos");
this.setPreviousStatement(true);
this.setNextStatement(true);
this.setTooltip('');
}
};


Blockly.Blocks['limpar_display_7s']={
init:function(){
this.setHelpUrl('http://www.example.com/');
this.setColour(cor_7Seg);
this.appendDummyInput()
.appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/display7SegOff.png", 40, 40,"*"))
.appendField("Limpar Display de 7 Segmentos")
.appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/invisible.png", 63, 40, "*"));
this.setPreviousStatement(true);
this.setNextStatement(true);
this.setTooltip('');
}
};


Blockly.Blocks['led_rgb'] = {
  init: function() {
var colourRed = new Blockly.FieldColour('#ffffff');
colourRed.setColours(['#f00','#fff']).setColumns(2);
var colourGreen = new Blockly.FieldColour('#ffffff');
colourGreen.setColours(['#0f0','#fff']).setColumns(2);
var colourBlue = new Blockly.FieldColour('#ffffff');
colourBlue.setColours(['#00f','#fff']).setColumns(2); 
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_ledRGB);
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/led_RGB.png", 40, 40, "*"))
        .appendField("Acender Luz  ")
		.appendField(colourRed, "luzVermelha")
		.appendField(colourGreen, "luzVerde")
		.appendField(colourBlue, "luzAzul")
        .appendField(" do LED RGB");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('');
  }
}; 


