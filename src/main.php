<?php
// setup.php
// Codigo executado para tratamento de parametros command line e configuracao de variveis de ambiente
// Configuracao da variavel parameters que esta sendo utilizada na execucao
require_once 'setup.php';

@$fileHandler = new FileManager($parameters['fileName'], $parameters['hasHeader'], $parameters['delimiters'], $parameters['labelColumn']);

$data = $fileHandler->getDataAsArray();

$foldsNumber = 10; // Valor fixo, mais utilizado normalmente
$folds = new Kfold($data, $foldsNumber);
$foldsList = $folds->getFolds();

$randomForest = new RandomForest();
$measure = new FMeasure();
$positiveValue = $parameters['positiveValue'];

for ($testFold = 0; $testFold < $foldsNumber; $testFold++) {

	$trainingFold = [];

	foreach ($foldsList as $foldIndex => $fold) {

		if ($foldIndex == $testFold) {
			continue;
		}

		$trainingFold = array_merge($trainingFold, $fold);

	} //Fim da concatenacao dos n-1 folds

	for ($i = 0; $i < $treeNumber; $i++) {
		$booststrap = new Bootstrap($trainingFold);
		$dataTraining = $booststrap->getTrainingData();

		$tree = new DecisionTree($dataTraining, range(0, count($data[0]) - 2));
		$tree->build();
		//$tree->debug(); //Mostra arvore gerada na tela

		$randomForest->addTree($tree);
	} //Fim Etapa Treinamento | Floresta pronta

	foreach ($foldsList[$testFold] as $instancia) {
		$classifier = new Classifier($randomForest, $instancia);
		$result = $classifier->execute();

		$measure->compute($instancia, $result, $positiveValue);
	} //Fim Etapa Testes

} // Fim Folds

// Exibe resultados
echo "\n-----------------------------------------------------------\n";
echo "\nArquivo: " . $parameters['fileName'] . "\n";
echo "\nNumero de Arvores: " . $treeNumber . "\n";
echo "\n-----------------------------------------------------------\n";

echo "\nVerdadeiros Positivos:" . $measure->getTruePositive();
echo "\nVerdadeiros Negativos:" . $measure->getTrueNegative();
echo "\nFalsos Positivos:" . $measure->getFalsePositive();
echo "\nFalsos Negativos:" . $measure->getFalseNegative();

echo "\nF-Measure:" . $measure->calcMeasure();
echo "\n";