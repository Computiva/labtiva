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

goog.provide('Blockly.dbk.controles');

goog.require('Blockly.Arduino');


Blockly.Arduino['delay'] = function(block) {
  var dropdown_milisegundos = block.getFieldValue('milisegundos');
  
  // TODO: Assemble Arduino into code variable.
  var code = 'delay (' + dropdown_milisegundos + ');\n';
  return code;
};


Blockly.Arduino['repetir'] = function(block) {
  var branch= Blockly.Arduino.statementToCode(block, 'blocos_dbk');
  var repeats = block.getFieldValue('numero_repeticoes');
  // TODO: Assemble Arduino into code variable.
  branch = Blockly.Arduino.addLoopTrap(branch, block.id);
  var loopVar = Blockly.Arduino.variableDB_.getDistinctName(
      'count', Blockly.Variables.NAME_TYPE);
  var code = 'for (int ' + loopVar + ' = 0; ' +
      loopVar + ' < ' + repeats + '; ' +
      loopVar + '++) {\n' +
      branch + '}\n';
  return code;
};
