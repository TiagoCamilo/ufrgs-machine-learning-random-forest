<?php

require_once 'classes/FileManager.php';
require_once 'classes/ArvoreDecisao.php';

$fileHandler = new FileManager('dados/dadosBenchmark_validacaoAlgoritmoAD.csv', true, ";");
//$fileHandler = new FileManager('dados/wine.data', false, ",");

$data = $fileHandler->getDataAsArray();
echo '<pre>';
var_dump(arvoreDeDecisao($data, array(0, 1, 2, 3)));

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

function arvoreDeDecisao($data, $attrList) {
	$no = array();

	$labelListCounter = labelCounter($data);

	if (count($labelListCounter) == 1) {
		return key($labelListCounter) . " Origem1";
	}

	if (empty($attrList)) {
		return labelCommon($labelListCounter) . " Origem2";
	}

	$bestAttr = findBestAttr($data, $attrList);

	$attrList = array_diff($attrList, [$bestAttr]);

	$subDatas = array();
	foreach ($data as $line) {
		$newLine = $line;
		unset($newLine[$bestAttr]);
		$subDatas[$line[$bestAttr]][] = $newLine;
	}

	foreach ($subDatas as $attr => $subData) {
		$no[$attr][] = arvoreDeDecisao($subData, $attrList);
	}

	return $no;
}

function labelCounter($data) {
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
	return $labelListCounter;
}

function labelCommon($labelListCounter) {
	$labelBigger = 0;
	$maxValue = 0;
	foreach ($labelListCounter as $label => $counter) {
		if ($counter > $maxValue) {
			$labelBigger = $label;
			$maxValue = $counter;
		}
	}
	return $labelBigger;
}

function findBestAttr($data, $attrList) {
	foreach ($attrList as $key => $value) {
		return $value;
	}
}