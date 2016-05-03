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

goog.provide('Blockly.generator.dbk');

goog.require('Blockly.Arduino');


var cor_controles = 32;


  Blockly.Blocks['delay'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_controles);
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/delay.png", 40, 40, "*"))
        .appendField("Esperar")
        .appendField(new Blockly.FieldDropdown([["1 segundo", "1000"], ["3 segundos", "3000"], ["5 segundos", "5000"], ["10 segundos", "10000"]]), "milisegundos");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('Faz o programa esperar alguns segundos antes de executar o próximo comando');
  }
};


Blockly.Blocks['repetir'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(cor_controles );
    this.appendDummyInput()
        .appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/loop.png", 40, 40, "*"))
        .appendField("Repetir")
        .appendField(new Blockly.FieldDropdown([["2", "2"], ["3", "3"], ["4", "4"], ["5", "5"], ["6", "6"], ["7", "7"], ["8", "8"], ["9", "9"], ["10", "10"]]), "numero_repeticoes")
        .appendField("vezes")
		.appendField(new Blockly.FieldImage("db4kfiles/ardublockly/img/db4kimages/invisible.png", 7, 40, "*"));
    this.appendStatementInput("blocos_dbk");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('Faz o programa repetir algumas vezes os comandos colocados dendro desse bloco');
  }
};



