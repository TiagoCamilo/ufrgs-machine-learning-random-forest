<?php
// Configuracao e includes
ini_set('memory_limit', '1024M');
require_once 'classes/DataHelper.php';
require_once 'classes/FileManager.php';
require_once 'classes/Bootstrap.php';
require_once 'classes/Node.php';
require_once 'classes/InformationGain.php';
require_once 'classes/DecisionTree.php';
require_once 'classes/Classifier.php';
require_once 'classes/Kfold.php';
require_once 'classes/RandomForest.php';
require_once 'classes/FMeasure.php';

// Tratamento de variaveis de command line
if (isset($argv[1]) == false || isset($argv[2]) == false) {
	die("Invalid Execution. \n php main.php X Y \n X = Tree Number \n Y = Index of file");
}

// Valor padrao de numero de arvores, caso nao seja especificado na chamada
$treeNumber = 10;
if (isset($argv[1]) && is_numeric($argv[1])) {
	$treeNumber = $argv[1];
}

// Define valores para parameters nessa execucao
switch ($argv[2]) {
case 0:
	$parameters = ['hasHeader' => true, 'delimiters' => ";", 'positiveValue' => "Sim", 'fileName' => "dados/dadosBenchmark_validacaoAlgoritmoAD.csv"];
	break;
case 1:
	$parameters = ['hasHeader' => true, 'delimiters' => "\t", 'positiveValue' => 1, 'fileName' => "dados/pima.tsv"];
	break;
case 2:
	$parameters = ['hasHeader' => false, 'delimiters' => ",", 'positiveValue' => 1, 'fileName' => "dados/wine.data", 'labelColumn' => 0];
	break;
case 3:
	$parameters = ['hasHeader' => false, 'delimiters' => ",", 'positiveValue' => "g", 'fileName' => "dados/ionosphere.data"];
	break;
default:
	die("Invalid Execution. \n php main.php X Y \n X = Tree Number \n Y = Index of file");
}