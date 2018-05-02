<?php

require_once 'classes/FileManager.php';
require_once 'classes/ArvoreDecisao.php';

$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");

$data = $fileHandler->getDataAsArray();
echo '<pre>';
$arvore = new ArvoreDecisao($data, array(0, 1, 2, 3));
var_dump($arvore->build());
//die();
/*
//Entradas:
Conjunto de treinamento, D = {(xk, f (xk)), k =1,...n}
Lista L de d atributos preditivos em D
Saída:
Árvore de decisão
Função arvoreDeDecisao(D,L)
1. Crie um nó N
2. Se todos os exemplos em D possuem a mesma classe yi, então retorne N como um nó folha rotulado com yi
3. Se L é vazia, então retorne N como um nó folha rotulado com a classe yi mais frequente em D.
4. Senão
4.1 A = atributo preditivo em L que apresenta "melhor" critério de divisão
4.2 Associe A ao nó N
4.3 L = L - A
4.4 Para cada valor v distinto do atributo A, considerando os exemplos em D, faça
4.4.1 Dv = subconjunto dos dados de treinamento em que A = v
4.4.2 Se Dv vazio, então retorne N como um nó folha rotulado com a classe yi mais frequente em Dv.
4.4.3 Senão, associe N a uma subárvore retornada por arvoreDeDecisao(Dv,L)
4.5 retorne N
 */

var_dump(informationGain($data, array(0, 1, 2, 3)));

function informationGain($data, $attrList) {
	$value = 0;

	$reverseLine = array_flip($data[0]);
	$labelIndex = array_pop($reverseLine);

	$labelListCounter = array();
	foreach ($data as $line) {
		if (isset($labelListCounter[$line[$labelIndex]]) == false) {
			$labelListCounter[$line[$labelIndex]] = 1;
			continue;
		}
		$labelListCounter[$line[$labelIndex]] += 1;
	}

	$sizeData = count($data);
	$infoD = 0;
	foreach ($labelListCounter as $counter) {
		$infoD -= (($counter / $sizeData) * log(($counter / $sizeData), 2));

	}

	$infoDAttr = array();
	$lowerEntropy = 100;

	foreach ($attrList as $valueAttr) {
		$counter = attrCounter($data, $valueAttr);
		$avgEntropy = 0;
		foreach ($counter as $key => $value) {
			$sizeAttr = 0;
			foreach ($value as $index) {
				$sizeAttr += $index;
			}

			$infoDAttr[$key] = 0;
			foreach ($value as $index) {
				$infoDAttr[$key] -= (($index / $sizeAttr) * log(($index / $sizeAttr), 2));

			}
			$avgEntropy += ($sizeAttr / $sizeData) * $infoDAttr[$key];
		}
		if ($avgEntropy < $lowerEntropy) {
			$lowerEntropy = $avgEntropy;
			$bestAttr = $valueAttr;
		}

		$diff = $infoD - $avgEntropy;

	}

	return $bestAttr;
}

function attrCounter($data, $attrIndex) {
	$reverseLine = array_flip($data[0]);
	$labelIndex = array_pop($reverseLine);

	$labelListCounter = array();
	foreach ($data as $line) {

		if (isset($labelListCounter[$line[$attrIndex]][$line[$labelIndex]]) == false) {
			$labelListCounter[$line[$attrIndex]][$line[$labelIndex]] = 1;
			continue;
		}
		$labelListCounter[$line[$attrIndex]][$line[$labelIndex]] += 1;
	}
	return $labelListCounter;
}