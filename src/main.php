<?php
require_once 'classes/Util.php';
require_once 'classes/FileManager.php';
require_once 'classes/Node.php';
require_once 'classes/InformationGain.php';
require_once 'classes/ArvoreDecisao.php';

//$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");
$fileHandler = new FileManager('dados/teste2.csv', true, ";");

$data = $fileHandler->getDataAsArray();
echo '<pre>';
$arvore = new ArvoreDecisao($data, array(0, 1, 2, 3));
$arvore->build();
$arvore->debug();
//var_dump($arvore->build());
