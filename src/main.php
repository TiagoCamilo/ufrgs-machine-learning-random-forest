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
require_once 'classes/Kfold.php';

//$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");
//$fileHandler = new FileManager('dados/teste2.csv', true, ";");
$fileHandler = new FileManager('dados/pima.tsv', true, "\t");

echo '<pre>';
$data = $fileHandler->getDataAsArray();

$folds = new Kfold($data, 10);

foreach ($folds->getFolds() as $fold) {

	$booststrap = new Bootstrap($fold);
	$dataTraining = $booststrap->getTrainingData();
	$dataTest = $booststrap->getTestData();

	$tree = new DecisionTree($dataTraining, range(0, count($data[0]) - 2));
	$tree->build();
	$tree->debug();

	foreach ($dataTest as $instancia) {
		$classifier = new Classifier($tree, $instancia);
		$result = $classifier->execute();
		echo "\n" . implode(";", $instancia) . "\t => \t" . $result . "\n";
		if ($instancia[count($instancia) - 1] == $result) {
			@$certos++;
		} else {
			@$errados++;
		}

	}
	echo "\nFold - Certo:" . $certos;
	echo "\nFold - Errado:" . $errados;
}
echo "\n--------------------\n";
echo "\nTotal - Certo:" . $certos;
echo "\nTotal - Errado:" . $errados;
echo "\nTotal - Geral:" . ($certos + $errados);