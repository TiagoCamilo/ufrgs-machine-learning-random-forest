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
require_once 'classes/RandomForest.php';
require_once 'classes/FMeasure.php';

$foldsNumber = 10;
$treeNumber = 15;
$positiveValue = 'g';
//$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");
//$fileHandler = new FileManager('dados/teste2.csv', true, ";");
$fileHandler = new FileManager('dados/pima.tsv', true, "\t");
//$fileHandler = new FileManager('dados/wine3.data', false, "\t");
//$fileHandler = new FileManager('dados/ionosphere.data', false, ",");

echo '<pre>';
$data = $fileHandler->getDataAsArray();

$folds = new Kfold($data, $foldsNumber);
$foldsList = $folds->getFolds();

$measure = new FMeasure();

$randomForest = new RandomForest();

for ($testFold = 0; $testFold < $foldsNumber; $testFold++) {

	$trainingFold = [];

	foreach ($foldsList as $foldIndex => $fold) {

		if ($foldIndex == $testFold) {
			continue;
		}

		$trainingFold = array_merge($trainingFold, $fold);

	} //Fim da analise dos folds

	for ($i = 0; $i < $treeNumber; $i++) {
		$booststrap = new Bootstrap($trainingFold);
		$dataTraining = $booststrap->getTrainingData();

		$tree = new DecisionTree($dataTraining, range(0, count($data[0]) - 2));
		$tree->build();
		//$tree->debug();

		$randomForest->addTree($tree);
	} //Fim Etapa Treinamento | Floresta pronta

	foreach ($foldsList[$testFold] as $instancia) {
		$classifier = new Classifier($randomForest, $instancia);
		$result = $classifier->execute();

		echo "\n" . implode(";", $instancia) . "=>" . $result . "\n";

		$measure->compute($instancia, $result, $positiveValue);
	} //Fim Etapa Testes

} // Fim Folds

$fMeasure = $measure->calcMeasure();

echo "\n--------------------\n";

echo "\nVerdadeiros Positivos:" . $measure->getTruePositive();
echo "\nVerdadeiros Negativos:" . $measure->getTrueNegative();
echo "\nFalsos Positivos:" . $measure->getFalsePositive();
echo "\nFalsos Negativos:" . $measure->getFalseNegative();

echo "\nF-Measure:" . $fMeasure;
