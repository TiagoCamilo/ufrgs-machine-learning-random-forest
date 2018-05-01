<?php

require_once 'classes/FileManager.php';

$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true);

echo '<pre>';
var_dump($fileHandler->getDataAsArray());