<?php
//ini_set('memory_limit', '4096M');
ini_set('display_errors', 1);

require_once 'classes/DataHelper.php';
require_once 'classes/FileManager.php';
require_once 'classes/Bootstrap.php';
require_once 'classes/Node.php';
require_once 'classes/InformationGain.php';
require_once 'classes/DecisionTree.php';
require_once 'classes/Classifier.php';

$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");
//$fileHandler = new FileManager('dados/teste2.csv', true, ";");
//$fileHandler = new FileManager('dados/pima.tsv', true, "\t");

$data = $fileHandler->getDataAsArray();
echo '<pre>';

$booststrap = new Bootstrap($data);
$dataTraining = $booststrap->getTrainingData();
$dataTest = $booststrap->getTestData();

$tree = new DecisionTree($dataTraining, array(0, 1, 2, 3));
$tree->build();
$tree->debug();

foreach ($dataTest as $instancia) {
	$classifier = new Classifier($tree, $instancia);
	echo "\n" . implode(";", $instancia) . "\t => \t" . $classifier->execute() . "\n";
}
