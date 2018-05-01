<?php

require_once 'classes/FileManager.php';
require_once 'classes/ArvoreDecisao.php';

$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");
//$fileHandler = new FileManager('dados/wine.data', false, ",");

$dados = $fileHandler->getDataAsArray();
$arvore = new ArvoreDecisao($dados, "Joga");
//$arvore = new ArvoreDecisao($dados, 0);

echo '<pre>';
$arvoreGerada = $arvore->build();
//print_r($arvoreGerada);