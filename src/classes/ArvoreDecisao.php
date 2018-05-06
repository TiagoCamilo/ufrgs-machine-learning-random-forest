<?php

class ArvoreDecisao {
	private $data;
	private $node;
	private $attrList;

	public function __construct($data, $attrList) {
		$this->data = $data;
		$this->attrList = $attrList;
	}

	public function build() {

		$labelListCounter = Util::labelCounter($this->data);

		if (count($labelListCounter) == 1) {
			return key($labelListCounter);
		}

		if (empty($this->attrList)) {
			return Util::labelMostCommon($labelListCounter);
		}

		$bestAttr = $this->findBestAttr();

		$this->attrList = array_diff($this->attrList, [$bestAttr]);

		//Se nao é valor numerico(continuo)
		if (is_numeric($this->data[Util::FIRST_LINE][$bestAttr]) == false) {
			return $this->buildNominalNode($bestAttr);
		} else {
			return $this->buildContinuousNode($bestAttr);
		}

	}

	private function buildNominalNode($bestAttr) {
		$subDatas = array();

		foreach ($this->data as $line) {
			$newLine = $line;
			unset($newLine[$bestAttr]);
			$subDatas[$line[$bestAttr]][] = $newLine;
		}

		foreach ($subDatas as $attrValue => $subData) {
			$arvore = new ArvoreDecisao($subData, $this->attrList);
			$newNode = Node::createNode($bestAttr, $attrValue);
			$newNode->nodes = $arvore->build();
			$this->node[] = $newNode;
		}

		return $this->node;
	}

	private function buildContinuousNode($bestAttr) {
		$subDatas = array();

		$cutValue = Util::getCutValue($this->data, $bestAttr);
		foreach ($this->data as $line) {
			$newLine = $line;
			unset($newLine[$bestAttr]);
			if ($line[$bestAttr] > $cutValue) {
				$subDatas[">"][] = $newLine;
			} else {
				$subDatas["<="][] = $newLine;
			}
		}

		foreach ($subDatas as $attrValue => $subData) {
			$arvore = new ArvoreDecisao($subData, $this->attrList);
			$newNode = Node::createNode($bestAttr, $cutValue, $attrValue);
			$newNode->nodes = $arvore->build();
			$this->node[] = $newNode;
		}

		return $this->node;

	}

	private function findBestAttr() {
		$informationGain = new InformationGain($this->data, $this->attrList);
		return $informationGain->compute();
	}

	public function debug() {
		foreach ($this->node as $node) {
			echo "\n";
			$this->debugNode($node);
		}
	}

	public function debugNode($node, $nivel = 0) {
		for ($i = 0; $i <= $nivel; $i++) {
			echo "\t";
		}

		echo $node->attr . " " . $node->operator . " " . $node->value;
		$nivel++;

		if (is_array($node->nodes)) {
			foreach ($node->nodes as $node) {
				echo "\n";
				$this->debugNode($node, $nivel);
			}
		} else {
			echo "\t\t => ";
			echo $node->nodes;
		}

	}

}