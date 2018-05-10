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

$foldsNumber = 10;
$treeNumber = 15;
$positiveValue = 'g';
//$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");
//$fileHandler = new FileManager('dados/teste2.csv', true, ";");
//$fileHandler = new FileManager('dados/pima.tsv', true, "\t");
$fileHandler = new FileManager('dados/ionosphere.data', false, ",");

echo '<pre>';
$data = $fileHandler->getDataAsArray();

$folds = new Kfold($data, $foldsNumber);
$foldsList = $folds->getFolds();

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
		if ($instancia[count($instancia) - 1] == $result) {
			if ($result == $positiveValue) {
				@$truePositive++;
			} else {
				@$trueNegative++;

			}
			@$certos++;
		} else {
			if ($result == $positiveValue) {
				@$falsePositive++;
			} else {
				@$falseNegative++;
			}
			@$errados++;
		}
	} //Fim Etapa Testes

} // Fim Folds

$rev = $truePositive / ($truePositive + $falseNegative);
$prec = $truePositive / ($truePositive + $falsePositive);

$fMeasure = (2 * ($prec * $rev)) / ($prec + $rev);
echo "\n--------------------\n";
echo "\nTotal - Certo:" . $certos;
echo "\nTotal - Errado:" . $errados;
echo "\n--------------------\n";
echo "\nVerdadeiros Positivos:" . $truePositive;
echo "\nVerdadeiros Negativos:" . $trueNegative;
echo "\nFalsos Positivos:" . $falsePositive;
echo "\nFalsos Negativos:" . $falseNegative;
echo "\n--------------------\n";
echo "\nReccal:" . $rev;
echo "\nPrecisão:" . $prec;

echo "\nF-Measure:" . $fMeasure;
echo "\n--------------------\n";
echo "\nTotal - Geral:" . ($certos + $errados);