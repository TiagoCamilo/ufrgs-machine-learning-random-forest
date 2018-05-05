<?php

class ArvoreDecisao {
	private $data;
	private $node;
	private $attrList;

	public function __construct($data, $attrList) {
		$this->data = $data;
		$this->attrList = $attrList;
		$this->node = array();
	}

	public function build() {

		$labelListCounter = Util::labelCounter($this->data);

		if (count($labelListCounter) == 1) {
			return key($labelListCounter) . " Origem1";
		}

		if (empty($this->attrList)) {
			return Util::labelMostCommon($labelListCounter) . " Origem2";
		}

		$bestAttr = $this->findBestAttr();

		$this->attrList = array_diff($this->attrList, [$bestAttr]);

		$subDatas = array();
		//Se nao é valor numerico(continuo)
		if (is_numeric($this->data[0][$bestAttr]) == false) {
			foreach ($this->data as $line) {
				$newLine = $line;
				unset($newLine[$bestAttr]);
				$subDatas[$line[$bestAttr]][] = $newLine;
			}
		} else {
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
		}

		foreach ($subDatas as $attr => $subData) {
			$arvore = new ArvoreDecisao($subData, $this->attrList);
			$this->node[$attr][] = $arvore->build();
		}

		return $this->node;
	}

	private function findBestAttr() {
		$informationGain = new InformationGain($this->data, $this->attrList);
		return $informationGain->compute();
	}

}