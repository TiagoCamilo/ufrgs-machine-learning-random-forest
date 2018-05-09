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

$foldsNumber = 10;
//$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");
//$fileHandler = new FileManager('dados/teste2.csv', true, ";");
$fileHandler = new FileManager('dados/pima.tsv', true, "\t");

echo '<pre>';
$data = $fileHandler->getDataAsArray();

$folds = new Kfold($data, $foldsNumber);
$foldsList = $folds->getFolds();

for ($testFold = 0; $testFold <= $foldsNumber; $testFold++) {

	foreach ($foldsList as $foldIndex => $fold) {

		if ($foldIndex == $testFold) {
			continue;
		}

		$booststrap = new Bootstrap($fold);
		$dataTraining = $booststrap->getTrainingData();
		$dataTest = $booststrap->getTestData();

		$tree = new DecisionTree($dataTraining, range(0, count($data[0]) - 2));
		$tree->build();
		$tree->debug();
	} //Fim Treinamento

	foreach ($foldsList[$testFold] as $instancia) {
		$classifier = new Classifier($tree, $instancia);
		$result = $classifier->execute();
		echo "\n" . implode(";", $instancia) . "\t => \t" . $result . "\n";
		if ($instancia[count($instancia) - 1] == $result) {
			if ($result == 1) {
				@$truePositive++;
			} else {
				@$trueNegative++;

			}
			@$certos++;
		} else {
			if ($result == 1) {
				@$falsePositive++;
			} else {
				@$falseNegative++;
			}
			@$errados++;
		}
	} //Fim Testes

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