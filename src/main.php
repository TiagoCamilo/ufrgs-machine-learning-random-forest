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

//Carol
//
echo '<pre>';
$data = $fileHandler->getDataAsArray();
$indexLabel = count($data[0]) - 1;

foreach ($data as $key => $value) {
	$classifica[$value[$indexLabel]][] = $value;
}

function kFolds($classifica, $nFolds) {
	$i = 0;
	$j = 0;

	foreach ($classifica as $key => $value) {
		foreach ($value as $teste) {
			$fold[$i][$j] = $teste;
			echo "<br>Posição[" . $i . "][" . $j . "]";
			if ($i == $nFolds - 1) {
				$i = 0;
				$j++;
			} else {
				$i++;
			}
		}
	}
	var_dump($fold);
	return 0;
}

$teste = kFolds($classifica, 10);

//fim Carol

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
