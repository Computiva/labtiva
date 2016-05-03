
function CarregaExemplo(xmlFile) {
  // The loadXmlBlockFile loads the file asynchronously and needs a callback
  var loadXmlCallback = function(sucess) {
    if (sucess) {
      Ardublockly.renderContent();
    } else {
      Ardublockly.materialAlert('Este não é um programa de Blocos DuinoBlocks for Kids',
          false);
    }
  };
  var callbackConnectionError = function() {
        alert("erro de conexão");
  };
  Ardublockly.loadXmlBlockFile(
      xmlFile, loadXmlCallback, callbackConnectionError);
};


function db4k_replaceBlocksfromXml(codigoXML) {
  var success = Ardublockly.replaceBlocksfromXml(codigoXML);
   if (success) {
    Ardublockly.renderContent();
    } else {
         alert('Este não é um Programa de Blocos do DuinoBlocks for Kids',false)
    }
};

//-----------------------------------------------------

function SalvaArquivoXMLComo() {
  var xmlName = document.getElementById('txtNomeCodigo').value;
  var blob = new Blob(
      [Ardublockly.generateXml()],
      {type: 'text/plain;charset=utf-8'});
  saveAs(blob, xmlName + '.xml');
};


function mostrarXMLBlocos() {
	var xmlBlocos = Ardublockly.generateXml();
  alert(xmlBlocos);
};

function mostrarCodigoWiring(){
      alert ("oi");
	//var codigoWiring = Blockly.Arduino.workspaceToCode(Ardublockly.workspace);
	//alert(codigoWiring);
};

function carregaAreaDeTextoXML(){
  var success = Ardublockly.replaceBlocksfromXml(
      document.getElementById('conteudo_xml').value);
  if (success) {
    Ardublockly.renderContent();
  } else {
        alert(
            'Conteúdo XML Inválido',
            'O Código XML não pôde ser convertido em Blocos' +
            'Por favor reveja seu código XML e tente novamente.',
            false)
  }
};


function renderizaArquivoXML(){
  alert ("oi");
 /** var success = Ardublockly.replaceBlocksfromXml(codigoXML);
  if (success) {
    Ardublockly.renderContent();
  } else {
        alert(
            'Conteúdo XML Inválido',
            'O Código XML não pôde ser convertido em Blocos' +
            'Por favor reveja seu código XML e tente novamente.',
            false)
  } **/
};


function carregarArquivoXml() {
  // Create event listener function
  var parseInputXMLfile = function(e) {
    var files = e.target.files;
    var reader = new FileReader();
    reader.onload = function() {
      var success = Ardublockly.replaceBlocksfromXml(reader.result);
      if (success) {
	var nomeCodigo = document.getElementById("txtNomeCodigo");
        //nomeCodigo.value = "";
        Ardublockly.renderContent();
      } else {
        alert(
            'Arquivo XML Inválido',
            'O arquivo  XML não pôde ser convertido em Blocos' +
            'Por favor reveja seu código XML e tente novamente.',
            false);
      }
    };
    reader.readAsText(files[0]);
  };
  // Create once invisible browse button with event listener, and click it
  var selectFile = document.getElementById('select_file');
  if (selectFile == null) {
    var selectFileDom = document.createElement('INPUT');
    selectFileDom.type = 'file';
    selectFileDom.id = 'select_file';

    var selectFileWrapperDom = document.createElement('DIV');
    selectFileWrapperDom.id = 'select_file_wrapper';
    selectFileWrapperDom.style.display = 'none';
    selectFileWrapperDom.appendChild(selectFileDom);

    document.body.appendChild(selectFileWrapperDom);
    selectFile = document.getElementById('select_file');
    selectFile.addEventListener('change', parseInputXMLfile, false);
  }
  selectFile.click();
};


