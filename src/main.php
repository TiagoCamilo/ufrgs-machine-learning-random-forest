<?php
require_once 'classes/Util.php';
require_once 'classes/FileManager.php';
require_once 'classes/Node.php';
require_once 'classes/InformationGain.php';
require_once 'classes/DecisionTree.php';
require_once 'classes/Classifier.php';

//$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");
$fileHandler = new FileManager('dados/teste2.csv', true, ";");

$data = $fileHandler->getDataAsArray();
echo '<pre>';

$tree = new DecisionTree($data, array(0, 1, 2, 3));
$tree->build();
$tree->debug();

$instancia = explode(";", "Chuvoso;Quente;Normal;Verdadeiro");
$classifier = new Classifier($tree, $instancia);

var_dump($classifier->execute());