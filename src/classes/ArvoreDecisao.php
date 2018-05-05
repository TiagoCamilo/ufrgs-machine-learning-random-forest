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
		foreach ($this->data as $line) {
			$newLine = $line;
			unset($newLine[$bestAttr]);
			$subDatas[$line[$bestAttr]][] = $newLine;
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